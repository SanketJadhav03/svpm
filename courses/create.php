<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Check if the form is submitted
if (isset($_POST["course_save"])) {
    // Sanitize and get form data
    $course_name = mysqli_real_escape_string($conn, $_POST["course_name"]);
    $course_code = mysqli_real_escape_string($conn, $_POST["course_code"]);
    $course_description = mysqli_real_escape_string($conn, $_POST["course_description"]);
    $course_credits = mysqli_real_escape_string($conn, $_POST["course_credits"]);

    
    $course_department_id = mysqli_real_escape_string($conn, $_POST["course_department_id"]);
    if(isset($_SESSION["department_id"])){
        $course_department_id = $_SESSION["department_id"];

    }
    $course_duration = mysqli_real_escape_string($conn, $_POST["course_duration"]);

    // Validate required fields
    if (empty($course_name) || empty($course_code) || empty($course_department_id)) {
        $_SESSION["error"] = "All required fields must be filled!";
    } else {
        // Insert query according to the table structure
        $insertQuery = "INSERT INTO `tbl_course` (`course_name`, `course_code`, `course_description`, `course_credits`, `course_department_id`,`course_duration`) 
                        VALUES ('$course_name', '$course_code', '$course_description', '$course_credits', '$course_department_id','$course_duration')";

        // Execute query
        if (mysqli_query($conn, $insertQuery)) {
            $_SESSION["success"] = "Course Created Successfully!";
            echo "<script>window.location = 'index.php';</script>";
        } else {
            $_SESSION["error"] = "Error in creating course: " . mysqli_error($conn);
        }
    }
}
?>

<div class="content-wrapper p-2">
    <form action="" method="post" onsubmit="validation();">
        <div class="card">
            <div class="card-header">
                <div class="d-flex p-2 justify-content-between">
                    <div class="h5 font-weight-bold">Create Course</div>
                    <a href="index.php" class="btn btn-info shadow font-weight-bold">
                        <i class="fa fa-eye"></i>&nbsp; Courses List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Course Name -->
                    <div class="col-4">
                        <label for="course_name">Course Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control font-weight-bold" name="course_name" id="course_name" placeholder="Course Name" required>
                    </div>

                    <!-- Course Code -->
                    <div class="col-4">
                        <label for="course_code">Course Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control font-weight-bold" name="course_code" id="course_code" placeholder="Course Code" required>
                    </div>

                    <!-- Course Duration -->
                    <div class="col-4">
                        <label for="course_duration">Course Duration ( Semester's ) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control font-weight-bold" name="course_duration" id="course_duration" placeholder="Course Duration" required>
                    </div>

                    <!-- Course Description -->
                    <div class="col-12 mt-3">
                        <label for="course_description">Course Description</label>
                        <textarea class="form-control font-weight-bold" name="course_description" id="course_description" placeholder="Course Description"></textarea>
                    </div>

                    <!-- Course Credits -->
                    <div class="col-6 mt-3">
                        <label for="course_credits">Course Credits</label>
                        <input type="number" class="form-control font-weight-bold" name="course_credits" id="course_credits" placeholder="Course Credits">
                    </div>

                    <!-- Department Selection -->
                    <div class="col-6 mt-3">
                        <label for="course_department_id">Department <span class="text-danger">*</span></label>
                        <select <?= isset($_SESSION["department_id"]) ? "disabled" : "" ?> class="form-control font-weight-bold" name="course_department_id" id="course_department_id" required>
                            <option value="">Select Department</option>
                            <?php
                            $departmentQuery = "SELECT * FROM tbl_department";
                            $departments = mysqli_query($conn, $departmentQuery);

                            // Check if department_id exists in session
                            if (isset($_SESSION["department_id"])) {
                                // Fetch the department corresponding to department_id
                                $selectedDepartmentId = $_SESSION["department_id"];
                                while ($department = mysqli_fetch_assoc($departments)) {
                                    // Set the option as selected if it matches the session's department_id
                                    $selected = ($department['department_id'] == $selectedDepartmentId) ? "selected" : "";
                                    echo "<option value='{$department['department_id']}' $selected>{$department['department_name']}</option>";
                                }
                            } else {
                                // If department_id is not in session, show all departments
                                while ($department = mysqli_fetch_assoc($departments)) {
                                    echo "<option value='{$department['department_id']}'>{$department['department_name']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>



                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex p-2 justify-content-end">
                    <button name="course_save" type="submit" class="btn btn-primary shadow font-weight-bold">
                        <i class="fa fa-save"></i>&nbsp; Add Course
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
        var course_name = document.getElementById("course_name");
        var course_code = document.getElementById("course_code");
        var course_department_id = document.getElementById("course_department_id");
        if (course_name.value == "") {
            course_name.focus();
            event.preventDefault();
        } else if (course_code.value == "") {
            course_code.focus();
            event.preventDefault();
        } else if (course_department_id.value == "") {
            course_department_id.focus();
            event.preventDefault();
        }
    }
</script>

<?php
include "../component/footer.php";
?>