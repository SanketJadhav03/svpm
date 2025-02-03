<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Check if exam_id is passed in the URL
$exam_id = isset($_GET["exam_id"]) ? $_GET["exam_id"] : null;

// Fetch the exam title and course name based on exam_id
$exam_title = '';
$course_name = '';
$course_id = '';
if ($exam_id) {
    // Join query to fetch both exam title and course name
    $examQuery = "SELECT e.exam_title, c.course_name,c.course_id 
                  FROM tbl_exam e 
                  JOIN tbl_course c ON e.exam_course_id = c.course_id 
                  WHERE e.exam_id = '$exam_id'";

    $result = mysqli_query($conn, $examQuery);

    if ($result && mysqli_num_rows($result) > 0) {
        $exam = mysqli_fetch_assoc($result);
        $exam_title = $exam["exam_title"];
        $course_name = $exam["course_name"];  // Get the course name
        $course_id = $exam["course_id"];  // Get the course name
    }
}

// Check if the form is submitted
if (isset($_POST["time_table_save"])) {
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
        // Insert query according to the table structure
        $insertQuery = "INSERT INTO `tbl_exam_schedule` (`schedule_exam`, `schedule_course`, `schedule_subject`, `schedule_date`, `schedule_start_time`, `schedule_end_time`, `schedule_status`) 
                        VALUES ('$time_table_exam', '$time_table_course', '$time_table_subject', '$time_table_date', '$time_table_start_time', '$time_table_end_time', '$time_table_status')";

        // Execute query
        if (mysqli_query($conn, $insertQuery)) {
            $_SESSION["success"] = "Exam Schedule Created Successfully!";
            echo "<script>window.location = 'time_table_list.php?exam_id=$exam_id';</script>";
        } else {
            $_SESSION["error"] = "Error in creating schedule: " . mysqli_error($conn);
        }
    }
}
?>

<div class="content-wrapper p-2">
    <form action="" method="post" onsubmit="validation();">
        <div class="card">
            <div class="card-header">
                <div class="d-flex p-2 justify-content-between">
                    <div class="h5 font-weight-bold">Create Subject Schedule</div>
                    <a href="time_table_list.php?exam_id=<?= $_GET["exam_id"] ?>" class="btn btn-info shadow font-weight-bold">
                        <i class="fa fa-eye"></i>&nbsp; Subject Time Table List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Exam Name -->
                    <div class="col-4">
                        <label for="time_table_exam">Exam Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control font-weight-bold"  placeholder="Exam Name" value="<?= $exam_title ?>" readonly required>
                        <input type="hidden" class="form-control font-weight-bold" name="time_table_exam" id="time_table_exam" placeholder="Exam Name" value="<?= $exam_id ?>" readonly required>
                    </div>

                    <!-- Course Name (From Exam) -->
                    <div class="col-4">
                        <label for="time_table_course">Course <span class="text-danger">*</span></label>
                        <input type="text" class="form-control font-weight-bold"  placeholder="Course Name" value="<?= $course_name ?>" readonly required>
                        <input type="hidden" class="form-control font-weight-bold" name="time_table_course" id="time_table_course" placeholder="Course Name" value="<?= $course_id ?>" >
                    </div>

                    <!-- Subject Selection -->
                    <div class="col-4">
                        <label for="time_table_subject">Subject <span class="text-danger">*</span></label>
                        <select class="form-control font-weight-bold" name="time_table_subject" id="time_table_subject" required>
                            <option value="">Select Subject</option>
                        </select>
                    </div>
                    <div class="col-4 mt-3">
                        <label for="time_table_date">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control font-weight-bold" name="time_table_date" id="time_table_date" required>
                    </div>

                    <!-- Start Time -->
                    <div class="col-4 mt-3">
                        <label for="time_table_start_time">Start Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control font-weight-bold" name="time_table_start_time" id="time_table_start_time" required>
                    </div>

                    <!-- End Time -->
                    <div class="col-4 mt-3">
                        <label for="time_table_end_time">End Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control font-weight-bold" name="time_table_end_time" id="time_table_end_time" required>
                    </div>

                    <!-- Status -->
                    <div class="col-12 mt-3">
                        <label for="time_table_status">Status <span class="text-danger">*</span></label>
                        <select class="form-control font-weight-bold" name="time_table_status" id="time_table_status" required>
                            <option value="0">Scheduled</option>
                            <option value="1">Completed</option>
                            <option value="2">Cancelled</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex p-2 justify-content-end">
                    <button name="time_table_save" type="submit" class="btn btn-primary shadow font-weight-bold">
                        <i class="fa fa-save"></i>&nbsp; Add Schedule
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
     // Fetch subjects based on selected course
     function fetchSubjects() {
        var courseId = document.getElementById("time_table_course").value;

        // If a course is selected, make AJAX request to fetch subjects
        if (courseId) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "fetch_subjects.php?course_id=" + courseId, true);
            xhr.onload = function () {
                if (xhr.status == 200) {
                    document.getElementById("time_table_subject").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        } else {
            // If no course is selected, reset the subject dropdown
            document.getElementById("time_table_subject").innerHTML = '<option value="">Sel ect Subject</option>';
        }
    }
    fetchSubjects();
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
