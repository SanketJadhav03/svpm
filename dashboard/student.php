<?php
$title = "Student Dashboard";
include "../config/connection.php";
include("../component/header.php");
include("../component/sidebar.php");

// Ensure the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch Student Course Details
$query = "SELECT *
          FROM tbl_students s
          JOIN tbl_course c ON s.student_course = c.course_id
          JOIN tbl_department d ON c.course_department_id = d.department_id
          WHERE s.student_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$course_id = $row['student_course'];

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
<style>
    .notices-marquee {
        height: 330px;
        overflow: hidden;
        position: relative;
        background-color: #f1f1f1;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 10px;
    }

    .notice-item {
        margin-bottom: 20px;
        display: block;
        padding: 10px;
        border-bottom: 1px solid #ddd;
        background-color: #ffffff;
        border-radius: 4px;
        transition: transform 0.2s ease;
        font-size: 14px;
        /* Initial font size */
    }

    .notice-item:hover {
        transform: scale(1.1);
        /* Slightly enlarge on hover */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        /* Darker shadow on hover */
        font-size: 16px;
        /* Increase font size on hover */
    }

    /* New styles for the sliding animation */
    @keyframes scroll-vertical {
        0% {
            transform: translateY(100%);
        }

        100% {
            transform: translateY(-100%);
        }
    }

    .notices-content {
        display: flex;
        flex-direction: column;
        animation: scroll-vertical 15s linear infinite;
        animation-play-state: running;
        /* Default state is running */
    }

    .notices-content:hover {
        animation-play-state: paused;
        padding: 10px;
        /* Pause animation on hover */
    }
</style>
<div class="content-wrapper">
    <div class="p-2 container-fluid">
        <div class="card">
            <div class="card-body">
                <h2 class="font-weight-bold">Student Dashboard - <?= $row["student_roll"] ?></h2>


                <table class="table table-bordered mt-3">
                    <thead class="thead- ">
                        <tr>
                            <th>Course Name</th>
                            <th>Course Code</th>
                            <th>Department</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= htmlspecialchars($row['course_name']) ?></td>
                            <td><?= htmlspecialchars($row['course_code']) ?></td>
                            <td><?= htmlspecialchars($row['department_name']) ?></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
        <?php
        $examQuery = "SELECT * FROM tbl_exam   WHERE exam_course_id = $course_id ORDER BY exam_start_date ASC";
        $examResult = mysqli_query($conn, $examQuery);
        $notices = [];
        $query = "SELECT * FROM `tbl_notices` WHERE `notice_status` = 1 ORDER BY `notice_id` DESC LIMIT 9"; // Only fetch active notices
        $noticesresult = mysqli_query($conn, $query);

        if ($noticesresult) {
            while ($row = mysqli_fetch_assoc($noticesresult)) {
                $notices[] = $row; // Store each notice in the array
            }
        }
        ?>
        <div class="card ">
            <div class="card-header">
                <h3 class="card-title">Notices</h3>
                <div class="card-tools">

                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>

                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="notices-marquee">
                    <div class="notices-content">
                        <div class="row">
                            <?php foreach ($notices as $notice): ?>
                                <div class="col-md-4">
                                    <span class="notice-item">
                                        <i class="fas fa-thumbtack"></i>&nbsp;
                                        <b><?= htmlspecialchars($notice['notice_title']) ?></b>
                                        <div class="notice-date"> - <?= date('F j, Y g:i A', strtotime($notice['notice_date'])) ?></div>
                                        <div> - <?= htmlspecialchars($notice['notice_description']) ?></div>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /.card-body -->

        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="font-weight-bold">Exam Time Table</h3>
            </div>
            <div class="card-body">
                <?php if (mysqli_num_rows($examResult) > 0): ?>
                    <?php while ($exam = mysqli_fetch_assoc($examResult)): ?>
                        <div class="mb-4">
                            <h4 class="font-weight-bold text-primary"><?php echo htmlspecialchars($exam['exam_title']); ?></h4>
                            <p><?php echo htmlspecialchars($exam['exam_description']); ?></p>
                            <p><strong>Duration:</strong> <?php echo date("d-m-Y", strtotime($exam['exam_start_date'])); ?> to <?php echo date("d-m-Y", strtotime($exam['exam_end_date'])); ?></p>

                            <?php
                            $scheduleQuery = "SELECT * FROM tbl_exam_schedule INNER JOIN tbl_course ON tbl_exam_schedule.schedule_course = tbl_course.course_id INNER JOIN tbl_subjects ON tbl_exam_schedule.schedule_subject = tbl_subjects.subject_id WHERE schedule_exam = '" . $exam['exam_id'] . "'    ORDER BY schedule_date ASC, schedule_start_time ASC";
                            $scheduleResult = mysqli_query($conn, $scheduleQuery);
                            ?>

                            <?php if (mysqli_num_rows($scheduleResult) > 0): ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Course</th>
                                            <th>Subject</th>
                                            <th>Date</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($schedule = mysqli_fetch_assoc($scheduleResult)): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($schedule['course_name']); ?></td>
                                                <td><?php echo htmlspecialchars($schedule['subject_name']); ?></td>
                                                <td><?php echo date("d-m-Y", strtotime($schedule['schedule_date'])); ?></td>
                                                <td><?php echo date("h:i A", strtotime($schedule['schedule_start_time'])); ?></td>
                                                <td><?php echo date("h:i A", strtotime($schedule['schedule_end_time'])); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p class="text-muted">No schedule available for this exam.</p>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">No upcoming exams.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="font-weight-bold">Lectures Time Table</h3>
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

    </div>
</div>
<script>
    window.onload = function() {
        const marquee = document.querySelector('.notices-content');
        const speed = 30000; // Speed of the animation (higher value = slower)
        marquee.style.animationDuration = `${speed / 1000}s`;
    };
</script>
<?php include "../component/footer.php"; ?>