<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Fetch departments and faculties
$departments = mysqli_query($conn, "SELECT * FROM tbl_department");
$faculties = mysqli_query($conn, "SELECT * FROM tbl_faculty");

// Fetch the timetable entry to edit
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM tbl_regular_time_table WHERE regular_time_table_id = '$id'";
    $result = mysqli_query($conn, $query);
    $entry = mysqli_fetch_assoc($result);
    if (!$entry) {
        $_SESSION["error"] = "Timetable entry not found!";
        header("Location: index.php");
        exit();
    }
} else {
    $_SESSION["error"] = "Invalid ID!";
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $department_id = $_POST['department_id'];
    $course_id = $_POST['course_id'];
    $subject_id = $_POST['subject_id'];
    $faculty_id = $_POST['faculty_id'];
    $period_day = $_POST['period_day'];
    $period_start_time = $_POST['period_start_time'];
    $period_end_time = $_POST['period_end_time'];

    if (!empty($department_id) && !empty($course_id) && !empty($subject_id) && !empty($faculty_id) && !empty($period_day) && !empty($period_start_time) && !empty($period_end_time)) {
        $query = "UPDATE tbl_regular_time_table 
                  SET department_id = '$department_id', course_id = '$course_id', subject_id = '$subject_id', 
                      faculty_id = '$faculty_id', period_day = '$period_day', period_start_time = '$period_start_time', 
                      period_end_time = '$period_end_time' 
                  WHERE regular_time_table_id = '$id'";

        if (mysqli_query($conn, $query)) {
            $_SESSION["success"] = "Timetable entry updated successfully!";
            echo "<script>window.location = 'index.php';</script>";
            exit();
        } else {
            $_SESSION["error"] = "Failed to update timetable entry!";
        }
    } else {
        $_SESSION["error"] = "All fields are required!";
    }
}
?>

<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <div class="h5 font-weight-bold">Edit Timetable Entry</div>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION["error"])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION["error"];
                    unset($_SESSION["error"]); ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="row">
                <div class="form-group col-4">
                    <label>Department <span class="text-danger fw-bold"> *</span> </label>
                    <select name="department_id" id="department_id" class="form-control" required>
                        <option value="">Select Department</option>
                        <?php while ($dept = mysqli_fetch_assoc($departments)): ?>
                            <option value="<?= $dept['department_id']; ?>" <?= $entry['department_id'] == $dept['department_id'] ? 'selected' : ''; ?>>
                                <?= $dept['department_name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group col-4">
                    <label>Course <span class="text-danger fw-bold"> *</span> </label>
                    <select name="course_id" id="course_id" class="form-control" required>
                        <option value="">Select Course</option>
                    </select>
                </div>

                <div class="form-group col-4">
                    <label>Subject <span class="text-danger fw-bold"> *</span> </label>
                    <select name="subject_id" id="subject_id" class="form-control" required>
                        <option value="">Select Subject</option>
                    </select>
                </div>

                <div class="form-group col-2">
                    <label>Day <span class="text-danger fw-bold"> *</span> </label>
                    <select name="period_day" class="form-control" required>
                        <option value="">Select Day</option>
                        <?php
                        $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
                        foreach ($days as $day) {
                            echo "<option value='$day' " . ($entry['period_day'] == $day ? 'selected' : '') . ">$day</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group col-4">
                    <label>Faculty <span class="text-danger fw-bold"> *</span> </label>
                    <select name="faculty_id" class="form-control" required>
                        <option value="">Select Faculty</option>
                        <?php while ($faculty = mysqli_fetch_assoc($faculties)): ?>
                            <option value="<?= $faculty['faculty_id']; ?>" <?= $entry['faculty_id'] == $faculty['faculty_id'] ? 'selected' : ''; ?>>
                                <?= $faculty['faculty_name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group col-3">
                    <label>Start Time <span class="text-danger fw-bold"> *</span> </label>
                    <input type="time" name="period_start_time" class="form-control" value="<?= $entry['period_start_time']; ?>" required>
                </div>

                <div class="form-group col-3">
                    <label>End Time <span class="text-danger fw-bold"> *</span> </label>
                    <input type="time" name="period_end_time" class="form-control" value="<?= $entry['period_end_time']; ?>" required>
                </div>

                <div class="card-footer col-12 text-right">
                    <button type="submit" class="btn btn-success"> <i class="fa fa-save"></i> Save Changes</button>
                    <a href="index.php" class="btn btn-danger"> <i class="fa fa-times"></i> Back</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var department_id = "<?= $entry['department_id'] ?>";
        var course_id = "<?= $entry['course_id'] ?>";
        var subject_id = "<?= $entry['subject_id'] ?>";

        function fetchCourses() {
            if (department_id) {
                $.post("fetch_data.php", { department_id: department_id, type: "course" }, function(response) {
                    $("#course_id").html(response).val(course_id);
                    fetchSubjects();
                });
            }
        }

        function fetchSubjects() {
            if (course_id) {
                $.post("fetch_data.php", { course_id: course_id, type: "subject" }, function(response) {
                    $("#subject_id").html(response).val(subject_id);
                });
            }
        }

        fetchCourses();

        $("#department_id").change(function() {
            department_id = $(this).val();
            fetchCourses();
        });

        $("#course_id").change(function() {
            course_id = $(this).val();
            fetchSubjects();
        });
    });
</script>

<?php
include "../component/footer.php";
?>
