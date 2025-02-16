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
 
?>
 
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