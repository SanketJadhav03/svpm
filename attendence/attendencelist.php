<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Get filters
$courseId = isset($_GET['course_id']) ? mysqli_real_escape_string($conn, $_GET['course_id']) : '';
$startDate = isset($_GET['start_date']) ? mysqli_real_escape_string($conn, $_GET['start_date']) : date('Y-m-d');
$endDate = isset($_GET['end_date']) ? mysqli_real_escape_string($conn, $_GET['end_date']) : date('Y-m-d');

// Fetch total present students for this course
$presentQuery = "SELECT COUNT(DISTINCT attendance_student_id) AS total_present 
                 FROM tbl_attendance 
                 WHERE attendance_student_id IN 
                 (SELECT student_id FROM tbl_students WHERE student_course = '$courseId')";

if (!empty($startDate) && !empty($endDate)) {
    $presentQuery .= " AND attendance_date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
}

$presentResult = mysqli_query($conn, $presentQuery);
$presentData = mysqli_fetch_assoc($presentResult);
$totalPresent = $presentData['total_present'] ?? 0;

// Fetch total students enrolled in this course
$totalStudentQuery = "SELECT COUNT(*) AS total_students FROM tbl_students WHERE student_course = '$courseId'";
$totalStudentResult = mysqli_query($conn, $totalStudentQuery);
$totalStudentData = mysqli_fetch_assoc($totalStudentResult);
$totalStudents = $totalStudentData['total_students'] ?? 0;
?>

<div class="content-wrapper">
    <div class="container-fluid p-2">
        <div class="card">
            <div class="card-header">
                <div class="card-title font-weight-bold">Attendance Summary</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <canvas id="attendancePieChart"></canvas>
                        <div class="container d-flex font-weight-bold  justify-content-between py-3">
                            <div class="text-success">
                                Present: <?php echo $totalPresent; ?>/<?php echo $totalStudents; ?>
                            </div>
                            <div class="text-danger">
                                Absent: <?php echo $totalStudents - $totalPresent; ?>/<?php echo $totalStudents; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-9">
                        <form method="GET" action="">
                            <div class="row">
                                <div class="form-group col-3">
                                    <label for="course">Select Course:</label>
                                    <select name="course_id" id="course" class="form-control">
                                        <option value="">All Courses</option>
                                        <?php
                                        $departmentLogin = $_SESSION['department_id'] ?? 0;
                                        $deptQuery = "SELECT * FROM tbl_department d
                                                      LEFT JOIN tbl_course c ON d.department_id = c.course_department_id";
                                        if ($departmentLogin) {
                                            $deptQuery .= " WHERE d.department_id = $departmentLogin";
                                        }
                                        $deptQuery .= " ORDER BY d.department_name, c.course_name";
                                        $deptResult = mysqli_query($conn, $deptQuery);

                                        $currentDept = null;
                                        while ($course = mysqli_fetch_assoc($deptResult)) {
                                            if ($currentDept !== $course['department_name']) {
                                                if ($currentDept !== null) echo "</optgroup>";
                                                $currentDept = $course['department_name'];
                                                echo "<optgroup label='{$currentDept}'>";
                                            }
                                            $selected = ($courseId == $course['course_id']) ? "selected" : "";
                                            echo "<option value='{$course['course_id']}' $selected>{$course['course_name']}</option>";
                                        }
                                        echo "</optgroup>";
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-3">
                                    <label for="start_date">Start Date:</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control"
                                        value="<?php echo htmlspecialchars($startDate); ?>">
                                </div>
                                <div class="form-group col-3">
                                    <label for="end_date">End Date:</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control"
                                        value="<?php echo htmlspecialchars($endDate); ?>">
                                </div>
                                <div class="col-3">
                                    <button type="submit" class="mt-4 btn btn-primary">Filter</button>
                                    <a href="attendencelist.php" class="btn mt-4 btn-secondary">Clear</a>
                                </div>
                            </div>
                        </form>

                        <?php

                        $query = "SELECT s.student_id, s.student_first_name, c.course_name, c.course_id, 
                                  a.*
                                  FROM tbl_students AS s
                                  LEFT JOIN tbl_attendance AS a 
                                  ON s.student_id = a.attendance_student_id 
                                  AND a.attendance_date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'
                                  INNER JOIN tbl_course AS c ON s.student_course = c.course_id";

                        $conditions = [];
                        if ($departmentLogin) $conditions[] = "c.course_department_id = $departmentLogin";
                        if (!empty($courseId)) $conditions[] = "s.student_course = '$courseId'";
                        if (!empty($conditions)) $query .= " WHERE " . implode(" AND ", $conditions);
                        $query .= " ORDER BY c.course_name, a.attendance_date DESC";

                        $result = mysqli_query($conn, $query);
                        $totalRecords = mysqli_num_rows($result);
                        ?>

                        <div class="table-responsive">
                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Course</th>
                                        <th>Date & Time</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($totalRecords > 0) {
                                        $count = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $image = $row['attendance_photo'] ?? "default.png";
                                            $status = $row['attendance_photo'] ? "<span class='badge bg-success'>Present</span>" : "<span class='badge bg-danger'>Absent</span>";
                                            $attendanceDate = $row['attendance_date'] ? date('d/m/Y h:i:s A', strtotime($row['attendance_date'])) : "N/A";
                                            $latitude = $row['attendance_latitude'] ?? "N/A";
                                            $longitude = $row['attendance_longitude'] ?? "N/A";

                                            echo "<tr>
                    <td>{$count}</td>
                    <td><img src='{$base_url}assets/images/studentattendence/{$image}' width='100'></td> 
                    <td>{$row['student_first_name']}</td> 
                    <td>{$row['course_name']}</td>  
                    <td>{$attendanceDate}</td>
                    <td>{$latitude}</td>
                    <td>{$longitude}</td>
                    <td>{$status}</td>  
                </tr>";
                                            $count++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='8'><div class='text-danger font-weight-bold'>No Attendance Found.</div></td></tr>";
                                    }
                                    ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('attendancePieChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Present', 'Absent'],
            datasets: [{
                data: [<?php echo $totalPresent; ?>, <?php echo $totalStudents - $totalPresent; ?>],
                backgroundColor: ['#28a745', '#dc3545']
            }]
        }
    });
</script>

<?php include "../component/footer.php"; ?>