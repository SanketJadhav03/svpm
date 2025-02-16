<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Get course_id from URL
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;

if ($course_id > 0) {
    // Fetch course name and department
    $courseQuery = "SELECT course_name, course_department_id FROM tbl_course WHERE course_id = $course_id";
    $courseResult = mysqli_query($conn, $courseQuery);
    $courseRow = mysqli_fetch_assoc($courseResult);
    $course_name = $courseRow ? $courseRow['course_name'] : "Unknown Course";
    $department_id = $courseRow ? $courseRow['course_department_id'] : 0;

    // Fetch subjects for the selected course
    $subjectQuery = "SELECT * FROM tbl_subjects WHERE subject_course = $course_id";
    $subjectResult = mysqli_query($conn, $subjectQuery);

    // Fetch faculty for the department of the selected course
    $facultyQuery = "SELECT f.*, d.department_name 
                     FROM tbl_faculty f 
                     LEFT JOIN tbl_department d ON f.faculty_department_id = d.department_id  
                     WHERE f.faculty_department_id = $department_id
                     ORDER BY f.faculty_name";
    $facultyResult = mysqli_query($conn, $facultyQuery);

    // Fetch timetable for the specific course
    $query = "
        SELECT rt.*, 
               s.subject_name, 
               f.faculty_name 
        FROM tbl_regular_time_table rt
        LEFT JOIN tbl_subjects s ON rt.subject_id = s.subject_id
        LEFT JOIN tbl_faculty f ON rt.faculty_id = f.faculty_id
        WHERE rt.course_id = $course_id
        ORDER BY FIELD(rt.period_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), rt.period_start_time
    ";
    $result = mysqli_query($conn, $query);
} else {
    $course_name = "Invalid Course";
    $result = false;
}

// Handle form submission to add a new timetable entry
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_timetable'])) {
    $subject_id = intval($_POST['subject_id']);
    $faculty_id = intval($_POST['faculty_id']);
    $period_start_time = $_POST['period_start_time'];
    $period_end_time = $_POST['period_end_time'];
    $period_day = $_POST['period_day'];
    $department_id = intval($_POST['department_id']);

    // Check if the department_id exists in the tbl_department table
    $departmentCheckQuery = "SELECT department_id FROM tbl_department WHERE department_id = $department_id";
    $departmentCheckResult = mysqli_query($conn, $departmentCheckQuery);

    if (mysqli_num_rows($departmentCheckResult) > 0) {
        // Department exists, proceed with insert
        $insertQuery = "INSERT INTO tbl_regular_time_table (department_id, course_id, subject_id, faculty_id, period_start_time, period_end_time, period_day, created_at) 
                        VALUES ($department_id, $course_id, $subject_id, $faculty_id, '$period_start_time', '$period_end_time', '$period_day', NOW())";

        if (mysqli_query($conn, $insertQuery)) {
            echo "<script>window.location.href = 'timetable.php?course_id=$course_id';</script>";
        } else {
            echo "<script>alert('Error adding timetable entry');</script>";
        }
    } else {
        // Department doesn't exist
        echo "<script>alert('Invalid Department ID');</script>";
    }
}

// Handle delete timetable entry
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $deleteQuery = "DELETE FROM tbl_regular_time_table WHERE regular_time_table_id = $delete_id AND course_id = $course_id";

    if (mysqli_query($conn, $deleteQuery)) {
        echo "<script>alert('Timetable entry deleted successfully');</script>";
        echo "<script>window.location.href = 'timetable.php?course_id=$course_id';</script>";
    } else {
        echo "<script>alert('Error deleting timetable entry');</script>";
    }
}

// Handle edit timetable entry
if (isset($_POST['edit_timetable'])) {
    $edit_id = intval($_POST['edit_id']);
    $subject_id = intval($_POST['subject_id']);
    $faculty_id = intval($_POST['faculty_id']);
    $period_start_time = $_POST['period_start_time'];
    $period_end_time = $_POST['period_end_time'];
    $period_day = $_POST['period_day'];

    $updateQuery = "UPDATE tbl_regular_time_table 
                    SET subject_id = $subject_id, faculty_id = $faculty_id, period_start_time = '$period_start_time', 
                        period_end_time = '$period_end_time', period_day = '$period_day' 
                    WHERE regular_time_table_id = $edit_id AND course_id = $course_id";

    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Timetable entry updated successfully');</script>";
        echo "<script>window.location.href = 'timetable.php?course_id=$course_id';</script>";
    } else {
        echo "<script>alert('Error updating timetable entry');</script>";
    }
}
?>

<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <div class=" d-flex justify-content-between">
                <h3 class="font-weight-bold">Timetable for <?= htmlspecialchars($course_name) ?></h3>
                <button class="btn btn-success" data-toggle="modal" data-target="#addTimeTableModal"> <i class="fa fa-plus me-1"></i> Add Time Table</button>
            </div>
        </div>
        <div class="card-body">
            <?php if ($result && mysqli_num_rows($result) > 0) { ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Subjects</th>
                                <th>Faculty</th>
                                <th>Del</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Array to hold grouped timetable entries by day
                            $timetableGrouped = [];

                            // Group timetable entries by day
                            while ($row = mysqli_fetch_assoc($result)) {
                                $timetableGrouped[$row['period_day']][] = $row;
                            }

                            // Loop through the grouped timetable entries
                            foreach ($timetableGrouped as $day => $entries) {
                                $rowspan = count($entries); // Number of subjects for this day

                                // For the first entry in this day, we show the Day and Start Time
                                $firstEntry = true;
                                foreach ($entries as $index => $entry) {
                                    if ($firstEntry) {
                                        // Display the Day cell with colspan
                                        echo "<tr>";
                                        echo "<td rowspan='{$rowspan}'>" . htmlspecialchars($day) . "</td>";
                                        $firstEntry = false; // No longer the first entry
                                    } else {
                                        // For subsequent rows, leave Day and Start Time empty
                                        echo "<tr> ";
                                    }

                                    echo "<td>" . date("h:i A", strtotime($entry['period_start_time'])) . "</td>";
                                    echo "<td>" . date("h:i A", strtotime($entry['period_end_time'])) . "</td>";

                                    echo "<td>" . htmlspecialchars($entry['subject_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($entry['faculty_name']) . "</td>";
                                    echo "<td> 
                                    <a href='timetable.php?course_id={$course_id}&delete_id={$entry['regular_time_table_id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this entry?\")'>
                                        <i class='fa fa-trash'></i>
                                    </a>
                                  </td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <p class="text-danger font-weight-bold">No timetable found for this course.</p>
            <?php } ?>
        </div>

    </div>
</div>

<!-- Modal for Adding Time Table -->
<div class="modal fade" id="addTimeTableModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Time Table Entry</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" value="<?= $department_id ?>" name="department_id">
                    <div class="form-group">
                        <label>Subject</label>
                        <select name="subject_id" class="form-control" required>
                            <option value="">Select Subject</option>
                            <?php
                            if ($course_id) {
                                $subjectQuery = "SELECT * FROM tbl_subjects WHERE subject_course = '$course_id' ORDER BY subject_for, subject_name";
                                $subjects = mysqli_query($conn, $subjectQuery);
                                if (mysqli_num_rows($subjects) > 0) {
                                    $previous_subject_for = '';
                                    while ($subject = mysqli_fetch_assoc($subjects)) {
                                        if ($subject['subject_for'] !== $previous_subject_for) {
                                            if ($previous_subject_for !== '') {
                                                echo "</optgroup>";
                                            }
                                            echo "<optgroup label='{$subject['subject_for']}'>"; // Open a new group based on 'subject_for'
                                            $previous_subject_for = $subject['subject_for']; // Update the group
                                        }
                                        echo "<option value='{$subject['subject_id']}'>" . htmlspecialchars($subject['subject_name']) . "</option>"; // Add the subject option
                                    }

                                    echo "</optgroup>"; // Close the last group
                                } else {
                                    echo '<option value="">No subjects found</option>';
                                }
                            } else {
                                echo '<option value="">Select Subject</option>';
                            }
                            ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label>Faculty</label>
                        <select name="faculty_id" class="form-control" required>
                            <option value="">Select Faculty</option>
                            <?php
                            while ($fac = mysqli_fetch_assoc($facultyResult)) {
                                echo "<option value='" . $fac['faculty_id'] . "'>" . htmlspecialchars($fac['faculty_name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Start Time</label>
                        <input type="time" name="period_start_time" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>End Time</label>
                        <input type="time" name="period_end_time" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Day</label>
                        <select name="period_day" class="form-control" required>
                            <option value="">Select Day</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>

                    <button type="submit" name="add_timetable" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Editing Time Table -->
<div class="modal fade" id="editTimeTableModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Time Table Entry</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" id="edit_id" name="edit_id">
                    <div class="form-group">
                        <label>Subject</label>
                        <select id="subject_id" name="subject_id" class="form-control" required>
                            <option value="">Select Subject</option>
                            <?php
                            mysqli_data_seek($subjectResult, 0); // Reset result pointer
                            while ($sub = mysqli_fetch_assoc($subjectResult)) {
                                echo "<option value='{$sub['subject_id']}'>" . htmlspecialchars($sub['subject_name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Faculty</label>
                        <select id="faculty_id" name="faculty_id" class="form-control" required>
                            <option value="">Select Faculty</option>
                            <?php
                            mysqli_data_seek($facultyResult, 0); // Reset result pointer
                            while ($fac = mysqli_fetch_assoc($facultyResult)) {
                                echo "<option value='" . $fac['faculty_id'] . "'>" . htmlspecialchars($fac['faculty_name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Start Time</label>
                        <input type="time" id="period_start_time" name="period_start_time" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>End Time</label>
                        <input type="time" id="period_end_time" name="period_end_time" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Day</label>
                        <select id="period_day" name="period_day" class="form-control" required>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>

                    <button type="submit" name="edit_timetable" class="btn btn-warning">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include "../component/footer.php"; ?>