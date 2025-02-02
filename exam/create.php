<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Check if the form is submitted
if (isset($_POST["exam_save"])) {
    // Sanitize and get form data
    $exam_title = mysqli_real_escape_string($conn, $_POST["exam_title"]);
    $exam_description = mysqli_real_escape_string($conn, $_POST["exam_description"]);
    $exam_start_date = mysqli_real_escape_string($conn, $_POST["exam_start_date"]);
    $exam_end_date = mysqli_real_escape_string($conn, $_POST["exam_end_date"]);
    $exam_status = mysqli_real_escape_string($conn, $_POST["exam_status"]);
    $exam_department_id = mysqli_real_escape_string($conn, $_POST["exam_department_id"]);
    $exam_course_id = mysqli_real_escape_string($conn, $_POST["exam_course_id"]);

    // Validate required fields
    if (empty($exam_title) || empty($exam_start_date) || empty($exam_end_date) || empty($exam_department_id) || empty($exam_course_id)) {
        $_SESSION["error"] = "All required fields must be filled!";
    } else {
        // Insert query according to the table structure
        $insertQuery = "INSERT INTO `tbl_exam` (`exam_title`, `exam_description`, `exam_start_date`, `exam_end_date`, `exam_status`, `exam_department_id`, `exam_course_id`) 
                        VALUES ('$exam_title', '$exam_description', '$exam_start_date', '$exam_end_date', '$exam_status', '$exam_department_id', '$exam_course_id')";

        // Execute query
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
    <form action="" method="post" onsubmit="validation();">
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
                    <!-- Exam Title -->
                    <div class="col-4">
                        <label for="exam_title">Exam Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control font-weight-bold" name="exam_title" id="exam_title" placeholder="Exam Title" required>
                    </div>


                    <!-- Department Selection -->
                    <div class="col-4">
                        <label for="exam_department_id">Department <span class="text-danger">*</span></label>
                        <select class="form-control font-weight-bold" name="exam_department_id" id="exam_department_id" onchange="loadCourses(this.value);" required>
                            <option value="">Select Department</option>
                            <?php
                            $departmentQuery = "SELECT * FROM tbl_department";
                            $departments = mysqli_query($conn, $departmentQuery);
                            while ($department = mysqli_fetch_assoc($departments)) {
                                echo "<option value='{$department['department_id']}'>{$department['department_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Course Selection -->
                    <div class="col-4">
                        <label for="exam_course_id">Course <span class="text-danger">*</span></label>
                        <select class="form-control font-weight-bold" name="exam_course_id" id="exam_course_id" required>
                            <option value="">Select Course</option>
                        </select>
                    </div>
                    <!-- Exam Start Date -->
                    <div class="col-4 mt-3">
                        <label for="exam_start_date">Exam Start Date <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control font-weight-bold" name="exam_start_date" id="exam_start_date" required>
                    </div>

                    <!-- Exam End Date -->
                    <div class="col-4 mt-3">
                        <label for="exam_end_date">Exam End Date <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control font-weight-bold" name="exam_end_date" id="exam_end_date" required>
                    </div>

                    <!-- Exam Status -->
                    <div class="col-4 mt-3">
                        <label for="exam_status">Exam Status <span class="text-danger">*</span></label>
                        <select class="form-control font-weight-bold" name="exam_status" id="exam_status" required>
                            <option value="Scheduled">Scheduled</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>


                    <!-- Exam Description -->
                    <div class="col-12 mt-3">
                        <label for="exam_description">Exam Description</label>
                        <textarea class="form-control font-weight-bold" name="exam_description" id="exam_description" placeholder="Exam Description"></textarea>
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

<script>
    function validation() {
        var exam_title = document.getElementById("exam_title");
        var exam_start_date = document.getElementById("exam_start_date");
        var exam_end_date = document.getElementById("exam_end_date");
        var exam_department_id = document.getElementById("exam_department_id");
        var exam_course_id = document.getElementById("exam_course_id");

        if (exam_title.value == "") {
            exam_title.focus();
            event.preventDefault();
        } else if (exam_start_date.value == "") {
            exam_start_date.focus();
            event.preventDefault();
        } else if (exam_end_date.value == "") {
            exam_end_date.focus();
            event.preventDefault();
        } else if (exam_department_id.value == "") {
            exam_department_id.focus();
            event.preventDefault();
        } else if (exam_course_id.value == "") {
            exam_course_id.focus();
            event.preventDefault();
        }
    }

    // Load courses based on selected department
    function loadCourses(departmentId) {
        var courseSelect = document.getElementById("exam_course_id");
        courseSelect.innerHTML = "<option value=''>Select Course</option>"; // Reset courses
        if (departmentId) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_courses.php?department_id=" + departmentId, true);
            xhr.onload = function() {
                if (xhr.status == 200) {
                    var courses = JSON.parse(xhr.responseText);
                    courses.forEach(function(course) {
                        var option = document.createElement("option");
                        option.value = course.course_id;
                        option.text = course.course_name;
                        courseSelect.appendChild(option);
                    });
                }
            };
            xhr.send();
        }
    }
</script>

<?php
include "../component/footer.php";
?>