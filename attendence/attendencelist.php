<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";
// Fetch total present students for this course
$courseId = isset($_GET['course_id']) ? $_GET['course_id'] : '';
$presentQuery = "SELECT COUNT(DISTINCT attendance_student_id) AS total_present 
 FROM tbl_attendance 
 WHERE attendance_student_id IN 
 (SELECT student_id FROM tbl_students WHERE student_course = '$courseId')";
$presentResult = mysqli_query($conn, $presentQuery);
$presentData = mysqli_fetch_assoc($presentResult);
$totalPressent = $presentData['total_present'];
// Fetch total students enrolled in this course
$totalStudentQuery = "SELECT COUNT(*) AS total_students 
      FROM tbl_students 
      WHERE student_course = '$courseId'";
$totalStudentResult = mysqli_query($conn, $totalStudentQuery);
$totalStudentData = mysqli_fetch_assoc($totalStudentResult);
$totalStudents = $totalStudentData['total_students'];
?>

<div class="content-wrapper">
    <div class="container-fluid p-2">
        <div class="card">
            <div class="card-header">
                <div class="card-title font-weight-bold">
                    Attendance List (Course-Wise)
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Form -->
                <form method="GET" action="">
                    <div class="row">
                        <div class="form-group col-3">
                            <label for="course">Select Course:</label>
                            <select name="course_id" id="course" class="form-control">
                                <option value="">All Courses</option>
                                <?php
                                // Fetch courses grouped by department
                                $deptQuery = "SELECT d.department_id, d.department_name, c.course_id, c.course_name 
                                              FROM tbl_department d
                                              LEFT JOIN tbl_course c ON d.department_id = c.course_department_id
                                              ORDER BY d.department_name, c.course_name";
                                $deptResult = mysqli_query($conn, $deptQuery);
                                $currentDept = null;

                                while ($course = mysqli_fetch_assoc($deptResult)) {
                                    if ($currentDept !== $course['department_name']) {
                                        if ($currentDept !== null) {
                                            echo "</optgroup>";
                                        }
                                        $currentDept = $course['department_name'];
                                        echo "<optgroup label='{$currentDept}'>";
                                    }
                                    $selected = isset($_GET['course_id']) && $_GET['course_id'] == $course['course_id'] ? "selected" : "";
                                    echo "<option value='{$course['course_id']}' $selected>{$course['course_name']}</option>";
                                }
                                echo "</optgroup>";
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-3">
                            <label for="start_date">Start Date:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="<?php echo isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : ''; ?>">
                        </div>

                        <div class="form-group col-3">
                            <label for="end_date">End Date:</label>
                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>">
                        </div>

                        <div class="col-3">
                            <button type="submit" class="mt-4 btn btn-primary">Filter</button>
                            <a href="attendencelist.php" class="btn mt-4 btn-secondary">Clear Filter</a>
                        </div>
                    </div>
                </form>

                <?php
                // Get selected filters
                $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
                $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';
                $courseId = isset($_GET['course_id']) ? $_GET['course_id'] : '';

                // Base Query
                $query = "SELECT a.*, s.student_first_name, c.course_name, c.course_id 
                          FROM tbl_attendance AS a
                          INNER JOIN tbl_students AS s ON a.attendance_student_id = s.student_id
                          INNER JOIN tbl_course AS c ON s.student_course = c.course_id";

                $conditions = [];

                // Apply filters
                if (!empty($startDate) && !empty($endDate)) {
                    $conditions[] = "a.attendance_date BETWEEN '$startDate' AND '$endDate'";
                } elseif (!empty($startDate)) {
                    $conditions[] = "a.attendance_date >= '$startDate'";
                } elseif (!empty($endDate)) {
                    $conditions[] = "a.attendance_date <= '$endDate'";
                }

                if (!empty($courseId)) {
                    $conditions[] = "s.student_course = '$courseId'";
                }

                // Append conditions if any
                if (!empty($conditions)) {
                    $query .= " WHERE " . implode(" AND ", $conditions);
                }

                $query .= " ORDER BY c.course_name, a.attendance_date DESC";

                $result = mysqli_query($conn, $query);
                $totalRecords = mysqli_num_rows($result);
                ?>
                <div class="container font-weight-bold text-center py-3 d-flex justify-content-between">
                    <div class="text-success">
                        Present Students: <?php echo $totalPressent; ?>/<?php echo $totalStudents; ?>
                    </div>
                    <div class="text-danger">
                        Absent Students: <?php echo $totalStudents - $totalPressent; ?>/<?php echo $totalStudents; ?>
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($totalRecords > 0) {
                                $count = 1;
                                $prevCourse = null;

                                while ($row = mysqli_fetch_assoc($result)) {
                                    $courseId = $row['course_id'];


                                    echo "<tr>
                                            <td>{$count}</td>
                                            <td><img src='{$base_url}assets/images/studentattendence/{$row['attendance_photo']}' alt='Attendance Photo' width='100'></td> 
                                            <td>{$row['student_first_name']}</td> 
                                            <td>{$row['course_name']}</td>  
                                            <td>" . date('d-m-Y h:i:s A', strtotime($row['attendance_date'])) . "</td>
                                        </tr>";
                                    $count++;
                                }
                            } else {
                                echo "<tr>
                                    <td colspan='7'>
                                        <div class='text-danger font-weight-bold'>No Attendance Found.</div>
                                    </td>
                                  </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include "../component/footer.php";
?>