<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Fetch courses from the database
$courseQuery = "SELECT course_id, course_name FROM tbl_course";
$courseResult = mysqli_query($conn, $courseQuery);

// Check if the form is submitted
if (isset($_POST["exam_save"])) {
    $course_id = mysqli_real_escape_string($conn, $_POST["course_id"]);
    $exam_title = mysqli_real_escape_string($conn, $_POST["exam_title"]);
    $exam_date = mysqli_real_escape_string($conn, $_POST["exam_date"]);
    $exam_start_time = mysqli_real_escape_string($conn, $_POST["exam_start_time"]);
    $exam_end_time = mysqli_real_escape_string($conn, $_POST["exam_end_time"]);
    $exam_status = mysqli_real_escape_string($conn, $_POST["exam_status"]);

    // Validate required fields
    if (empty($course_id) || empty($exam_title) || empty($exam_date) || empty($exam_start_time) || empty($exam_end_time)) {
        $_SESSION["error"] = "All fields are required!";
    } else {
        // Insert query
        $insertQuery = "INSERT INTO tbl_exams 
                        (course_id, exam_title, exam_date, exam_start_time, exam_end_time, exam_status) 
                        VALUES ('$course_id', '$exam_title', '$exam_date', '$exam_start_time', '$exam_end_time', '$exam_status')";

        if (mysqli_query($conn, $insertQuery)) {
            $_SESSION["success"] = "Exam Created Successfully!";
            echo "<script>window.location = 'index.php';</script>";
        } else {
            $_SESSION["error"] = "Error in creating exam: " . mysqli_error($conn);
        }
    }
}
?>

<div class="content-wrapper p-2">
    <form action="" method="post">
        <div class="card">
            <div class="card-header">
                <div class="d-flex p-2 justify-content-between">
                    <div class="h5 font-weight-bold">Create Exam</div>
                    <a href="index.php" class="btn btn-info shadow font-weight-bold">
                        <i class="fa fa-eye"></i>&nbsp; Exams List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Course Selection -->
                    <div class="col-4">
                        <label for="course_id">Select Course <span class="text-danger">*</span></label>
                        <select class="form-control font-weight-bold" name="course_id" id="course_id" required>
                            <option value="">-- Select Course --</option>
                            <?php while ($row = mysqli_fetch_assoc($courseResult)) { ?>
                                <option value="<?= $row['course_id']; ?>"><?= $row['course_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- Exam Title -->
                    <div class="col-4">
                        <label for="exam_title">Exam Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control font-weight-bold" name="exam_title" id="exam_title" placeholder="Exam Title" required>
                    </div>

                    <!-- Exam Date -->
                    <div class="col-4 ">
                        <label for="exam_date">Exam Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control font-weight-bold" name="exam_date" id="exam_date" required>
                    </div>

                    <!-- Start Time -->
                    <div class="col-4 mt-3">
                        <label for="exam_start_time">Start Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control font-weight-bold" name="exam_start_time" id="exam_start_time" required>
                    </div>

                    <!-- End Time -->
                    <div class="col-4 mt-3">
                        <label for="exam_end_time">End Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control font-weight-bold" name="exam_end_time" id="exam_end_time" required>
                    </div>

                    <!-- Exam Status -->
                    <div class="col-4 mt-3">
                        <label for="exam_status">Exam Status</label>
                        <select class="form-control font-weight-bold" name="exam_status" id="exam_status">
                            <option value="Scheduled">Scheduled</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex p-2 justify-content-end">
                    <button name="exam_save" type="submit" class="btn btn-primary shadow font-weight-bold">
                        <i class="fa fa-save"></i>&nbsp; Add Exam
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

<?php
include "../component/footer.php";
?>
