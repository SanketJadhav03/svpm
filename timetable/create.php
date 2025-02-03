<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Fetch departments and faculties
$departments = mysqli_query($conn, "SELECT * FROM tbl_department");
$faculties = mysqli_query($conn, "SELECT * FROM tbl_faculty");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $department_id = $_POST['department_id'];
    $course_id = $_POST['course_id'];
    $subject_id = $_POST['subject_id'];
    $faculty_id = $_POST['faculty_id'];
    $period_day = $_POST['period_day'];
    $period_start_time = $_POST['period_start_time'];
    $period_end_time = $_POST['period_end_time'];

    if (!empty($department_id) && !empty($course_id) && !empty($subject_id) && !empty($faculty_id) && !empty($period_day) && !empty($period_start_time) && !empty($period_end_time)) {
        $query = "INSERT INTO tbl_regular_time_table (department_id, course_id, subject_id, faculty_id, period_day, period_start_time, period_end_time) 
                  VALUES ('$department_id', '$course_id', '$subject_id', '$faculty_id', '$period_day', '$period_start_time', '$period_end_time')";

        if (mysqli_query($conn, $query)) {
            $_SESSION["success"] = "Timetable entry added successfully!";
            echo "<script>window.location = 'index.php';</script>";
            exit();
        } else {
            $_SESSION["error"] = "Failed to add timetable entry!";
        }
    } else {
        $_SESSION["error"] = "All fields are required!";
    }
}
?>

<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <div class="h5 font-weight-bold">Add New Timetable Entry</div>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION["error"])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION["error"];
                    unset($_SESSION["error"]); ?>
                </div>
            <?php endif; ?>

            <form action="" class="row" method="POST">
                <div class="form-group col-4">
                    <label>Department <span class="text-danger fw-bold">*</span> </label>
                    <select name="department_id" id="department_id" class="form-control" required>
                        <option value="">Select Department</option>
                        <?php while ($dept = mysqli_fetch_assoc($departments)): ?>
                            <option value="<?= $dept['department_id']; ?>"><?= $dept['department_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group col-4">
                    <label>Course <span class="text-danger fw-bold">*</span></label>
                    <select name="course_id" id="course_id" class="form-control" required>
                        <option value="">Select Course</option>
                    </select>
                </div>

                <div class="form-group col-4">
                    <label>Subject <span class="text-danger fw-bold">*</span></label>
                    <select name="subject_id" id="subject_id" class="form-control" required>
                        <option value="">Select Subject</option>
                    </select>
                </div>
                <div class="form-group col-2">
                    <label>Day <span class="text-danger fw-bold">*</span></label>
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
                <div class="form-group col-4">
                    <label>Faculty <span class="text-danger fw-bold">*</span></label>
                    <select name="faculty_id" class="form-control" required>
                        <option value="">Select Faculty</option>
                        <?php while ($faculty = mysqli_fetch_assoc($faculties)): ?>
                            <option value="<?= $faculty['faculty_id']; ?>"><?= $faculty['faculty_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group col-3">
                    <label>Start Time <span class="text-danger fw-bold">*</span></label>
                    <input type="time" name="period_start_time" class="form-control" required>
                </div>

               



                <div class="form-group col-3">
                    <label>End Time <span class="text-danger fw-bold">*</span></label>
                    <input type="time" name="period_end_time" class="form-control" required>
                </div>

                <div class="col-12 text-right card-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i>
                        Save</button>
                    <a href="index.php" class="btn btn-danger">

                        <i class="fas fa-times "></i> Back</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Load Courses based on Department selection
        $("#department_id").change(function() {
            var department_id = $(this).val();
            if (department_id) {
                $.ajax({
                    type: "POST",
                    url: "fetch_data.php",
                    data: {
                        department_id: department_id,
                        type: "course"
                    },
                    success: function(response) {
                        $("#course_id").html(response);
                        $("#subject_id").html('<option value="">Select Subject</option>'); // Reset subject dropdown
                    }
                });
            } else {
                $("#course_id").html('<option value="">Select Course</option>');
                $("#subject_id").html('<option value="">Select Subject</option>');
            }
        });

        // Load Subjects based on Course selection
        $("#course_id").change(function() {
            var course_id = $(this).val();
            if (course_id) {
                $.ajax({
                    type: "POST",
                    url: "fetch_data.php",
                    data: {
                        course_id: course_id,
                        type: "subject"
                    },
                    success: function(response) {
                        $("#subject_id").html(response);
                    }
                });
            } else {
                $("#subject_id").html('<option value="">Select Subject</option>');
            }
        });
    });
</script>

<?php
include "../component/footer.php";
?>