<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Check if exam_id and schedule_id are passed in the URL
$exam_id = isset($_GET["exam_id"]) ? $_GET["exam_id"] : null;
$schedule_id = isset($_GET["schedule_id"]) ? $_GET["schedule_id"] : null;

// Fetch the exam title, course name, and course_id based on exam_id
$exam_title = '';
$course_name = '';
$course_id = ''; 

// Fetch the existing timetable schedule based on schedule_id
$existing_schedule = null;
if ($schedule_id) {
    // Modified query to join tbl_exam and tbl_exam_schedule
    $scheduleQuery = "SELECT * 
                      FROM tbl_exam_schedule 
                      INNER JOIN tbl_exam ON tbl_exam.exam_id = tbl_exam_schedule.schedule_exam 
                      INNER JOIN tbl_course ON tbl_exam.exam_course_id = tbl_course.course_id 
                      WHERE tbl_exam_schedule.schedule_id = '$schedule_id'";
    
    $scheduleResult = mysqli_query($conn, $scheduleQuery);
    
    if ($scheduleResult && mysqli_num_rows($scheduleResult) > 0) {
        // Fetch the schedule and exam details
        $existing_schedule = mysqli_fetch_assoc($scheduleResult);
        
        // Extract exam title, course name, and course ID
        $exam_title = $existing_schedule["exam_title"];
        $course_name = $existing_schedule["course_name"];
        $course_id = $existing_schedule["course_id"];
        $exam_id = $existing_schedule["exam_id"];
    }
}

// Fetch subjects based on course_id
$subjectsQuery = "SELECT * FROM tbl_subjects WHERE subject_course = '$course_id'";
$subjectsResult = mysqli_query($conn, $subjectsQuery);

// Check if the form is submitted
if (isset($_POST["time_table_update"])) {
    // Sanitize and get form data
    $time_table_exam = mysqli_real_escape_string($conn, $_POST["time_table_exam"]);
    $time_table_course = mysqli_real_escape_string($conn, $_POST["time_table_course"]);
    $time_table_subject = mysqli_real_escape_string($conn, $_POST["time_table_subject"]);
    $time_table_date = mysqli_real_escape_string($conn, $_POST["time_table_date"]);
    $time_table_start_time = mysqli_real_escape_string($conn, $_POST["time_table_start_time"]);
    $time_table_end_time = mysqli_real_escape_string($conn, $_POST["time_table_end_time"]);
    $time_table_status = mysqli_real_escape_string($conn, $_POST["time_table_status"]);

    // Validate required fields
    if (empty($time_table_exam) || empty($time_table_course) || empty($time_table_subject) || empty($time_table_date)) {
        $_SESSION["error"] = "All required fields must be filled!";
    } else {
        // Update query
        $updateQuery = "UPDATE tbl_exam_schedule 
                        SET schedule_exam = '$time_table_exam', schedule_course = '$time_table_course', schedule_subject = '$time_table_subject', schedule_date = '$time_table_date', schedule_start_time = '$time_table_start_time', schedule_end_time = '$time_table_end_time', schedule_status = '$time_table_status' 
                        WHERE schedule_id = '$schedule_id'";

        // Execute query
        if (mysqli_query($conn, $updateQuery)) {
            $_SESSION["success"] = "Exam Schedule Updated Successfully!";
            echo "<script>window.location = 'time_table_list.php?exam_id=$exam_id';</script>";
        } else {
            $_SESSION["error"] = "Error in updating schedule: " . mysqli_error($conn);
        }
    }
}
?>

<div class="content-wrapper p-2">
    <form action="" method="post" onsubmit="validation();">
        <div class="card">
            <div class="card-header">
                <div class="d-flex p-2 justify-content-between">
                    <div class="h5 font-weight-bold">Edit Subject Schedule</div>
                    <a href="time_table_list.php?exam_id=<?= $exam_id ?>" class="btn btn-info shadow font-weight-bold">
                        <i class="fa fa-eye"></i>&nbsp; Subject Time Table List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Exam Name -->
                    <div class="col-4">
                        <label for="time_table_exam">Exam Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control font-weight-bold" value="<?= $exam_title ?>" readonly required>
                        <input type="hidden" class="form-control font-weight-bold" name="time_table_exam" id="time_table_exam" value="<?= $exam_id ?>" readonly required>
                    </div>

                    <!-- Course Name (From Exam) -->
                    <div class="col-4">
                        <label for="time_table_course">Course <span class="text-danger">*</span></label>
                        <input type="text" class="form-control font-weight-bold" value="<?= $course_name ?>" readonly required>
                        <input type="hidden" class="form-control font-weight-bold" name="time_table_course" id="time_table_course" value="<?= $course_id ?>" required>
                    </div>

                    <!-- Subject Selection -->
                    <div class="col-4">
                        <label for="time_table_subject">Subject <span class="text-danger">*</span></label>
                        <select class="form-control font-weight-bold" name="time_table_subject" id="time_table_subject" required>
                            <option value="">Select Subject</option>
                            <?php while ($subject = mysqli_fetch_assoc($subjectsResult)) { ?>
                                <option value="<?= $subject['subject_id'] ?>" <?= ($existing_schedule && $existing_schedule['schedule_subject'] == $subject['subject_id']) ? 'selected' : '' ?>>
                                    <?= $subject['subject_name'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-4 mt-3">
                        <label for="time_table_date">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control font-weight-bold" name="time_table_date" id="time_table_date" value="<?= $existing_schedule['schedule_date'] ?>" required>
                    </div>

                    <!-- Start Time -->
                    <div class="col-4 mt-3">
                        <label for="time_table_start_time">Start Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control font-weight-bold" name="time_table_start_time" id="time_table_start_time" value="<?= $existing_schedule['schedule_start_time'] ?>" required>
                    </div>

                    <!-- End Time -->
                    <div class="col-4 mt-3">
                        <label for="time_table_end_time">End Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control font-weight-bold" name="time_table_end_time" id="time_table_end_time" value="<?= $existing_schedule['schedule_end_time'] ?>" required>
                    </div>

                    <!-- Status -->
                    <div class="col-12 mt-3">
                        <label for="time_table_status">Status <span class="text-danger">*</span></label>
                        <select class="form-control font-weight-bold" name="time_table_status" id="time_table_status" required>
                            <option value="0" <?= ($existing_schedule && $existing_schedule['schedule_status'] == '0') ? 'selected' : '' ?>>Scheduled</option>
                            <option value="1" <?= ($existing_schedule && $existing_schedule['schedule_status'] == '1') ? 'selected' : '' ?>>Completed</option>
                            <option value="2" <?= ($existing_schedule && $existing_schedule['schedule_status'] == '2') ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex p-2 justify-content-end">
                    <button name="time_table_update" type="submit" class="btn btn-primary shadow font-weight-bold">
                        <i class="fa fa-save"></i>&nbsp; Update Schedule
                    </button>
                    &nbsp;
                    <button type="reset" class="btn btn-danger shadow font-weight-bold">
                        <i class="fas fa-times"></i>&nbsp; Clear
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Add validation here (if needed)
    function validation() {
        var time_table_exam = document.getElementById("time_table_exam");
        var time_table_course = document.getElementById("time_table_course");
        var time_table_subject = document.getElementById("time_table_subject");
        var time_table_date = document.getElementById("time_table_date");

        if (time_table_exam.value == "") {
            time_table_exam.focus();
            event.preventDefault();
        } else if (time_table_course.value == "") {
            time_table_course.focus();
            event.preventDefault();
        } else if (time_table_subject.value == "") {
            time_table_subject.focus();
            event.preventDefault();
        } else if (time_table_date.value == "") {
            time_table_date.focus();
            event.preventDefault();
        }
    }
</script>

<?php
include "../component/footer.php";
?>
