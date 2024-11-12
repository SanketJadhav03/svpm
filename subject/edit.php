<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Check if subject ID is provided
if (isset($_GET['subject_id'])) {
    $subject_id = $_GET['subject_id'];

    // Fetch the subject data based on the ID
    $subjectQuery = "SELECT * FROM tbl_subjects WHERE subject_id = '$subject_id'";
    $resultSubject = mysqli_query($conn, $subjectQuery);

    // If subject exists
    if (mysqli_num_rows($resultSubject) > 0) {
        $subjectData = mysqli_fetch_array($resultSubject);
    } else {
        // Redirect to the subjects list page if no subject found
        $_SESSION["error"] = "Subject not found!";
        echo "<script>window.location = 'index.php';</script>";
    }
} else {
    // Redirect to the subjects list page if no ID is provided
    echo "<script>window.location = 'index.php';</script>";
}

// Update the subject
if (isset($_POST["subject_update"])) {
    $subject_code = $_POST["subject_code"];
    $subject_course = $_POST["subject_course"];
    $subject_for = $_POST["subject_for"];
    $subject_name = $_POST["subject_name"];
    $subject_type = $_POST["subject_type"];
    $subject_theory = $_POST["subject_theory"];
    $subject_practical = $_POST["subject_practical"];

    // Update query
    $updateQuery = "UPDATE tbl_subjects 
                    SET subject_name='$subject_name', subject_code='$subject_code', subject_type='$subject_type', 
                        subject_for='$subject_for', subject_theory='$subject_theory', subject_practical='$subject_practical', 
                        subject_course='$subject_course' 
                    WHERE subject_id='$subject_id'";

    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION["success"] = "Subject Updated Successfully!";
        echo "<script>window.location = 'index.php';</script>";
    } else {
        $_SESSION["error"] = "Error updating subject!";
    }
}
?>

<div class="content-wrapper p-2">
    <form action="" method="post" onsubmit="return validation();">
        <div class="card">
            <div class="card-header">
                <div class="d-flex p-2 justify-content-between">
                    <div class="h5 font-weight-bold">Edit Subject</div>
                    <a href="index.php" class="btn btn-info shadow font-weight-bold"> <i class="fa fa-eye"></i>&nbsp; Subjects List</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <label for="subject_course">Course Name <span class="text-danger font-weight-bold"> *</span></label>
                        <select name="subject_course" id="subject_course" class="form-control font-weight-bold" onchange="updateSubjectFor()">
                            <option value="">Select Course</option>
                            <?php
                            // Query to get all departments
                            $alldepartment = "SELECT * FROM tbl_department";
                            $departmentQuery = mysqli_query($conn, $alldepartment);

                            // Loop through each department
                            while ($department = mysqli_fetch_array($departmentQuery)) {
                                // Query to get courses for the current department
                                $courseQuery = mysqli_query($conn, "SELECT * FROM tbl_course WHERE course_department_id = " . $department['department_id']);

                                // Display department as an optgroup
                                if (mysqli_num_rows($courseQuery) > 0) {
                                    echo '<optgroup label="' . $department["department_name"] . '">';

                                    // Loop through each course within this department
                                    while ($course = mysqli_fetch_array($courseQuery)) {
                                        $selected = ($course['course_id'] == $subjectData['subject_course']) ? 'selected' : '';
                                        echo '<option value="' . $course['course_id'] . '" data-course-total="' . $course['course_duration'] . '" ' . $selected . '>' . $course["course_name"] . '</option>';
                                    }

                                    echo '</optgroup>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-3">
                        <label for="subject_for">For Semester <span class="text-danger font-weight-bold"> *</span></label>
                        <select class="form-control font-weight-bold" name="subject_for" id="subject_for">
                            <option value="">Select Semester</option>
                        </select>
                    </div>
                    <div class="col-5">
                        <label for="">Subject Code <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" class="form-control font-weight-bold" name="subject_code" id="subject_code" value="<?= $subjectData['subject_code'] ?>" placeholder="Subject Code">
                    </div>
                    <div class="col-5 mt-3">
                        <label for="">Subject Name <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" class="form-control font-weight-bold" name="subject_name" id="subject_name" value="<?= $subjectData['subject_name'] ?>" placeholder="Subject Name">
                    </div>
                    <div class="col-3 mt-3">
                        <label for="">Subject Type <span class="text-danger font-weight-bold"> *</span></label>
                        <select class="form-control font-weight-bold" name="subject_type" id="subject_type">
                            <option value="">Select</option>
                            <option value="1" <?= ($subjectData['subject_type'] == 1) ? 'selected' : '' ?>>Core</option>
                            <option value="2" <?= ($subjectData['subject_type'] == 2) ? 'selected' : '' ?>>Optional</option>
                        </select>
                    </div>
                    <div class="col-2 mt-3">
                        <label for="">Theory Marks<span class="text-danger font-weight-bold"> *</span></label>
                        <input type="number" class="form-control font-weight-bold" name="subject_theory" id="subject_theory" value="<?= $subjectData['subject_theory'] ?>" placeholder="Theory Marks">
                    </div>
                    <div class="col-2 mt-3">
                        <label for="">Practical Marks<span class="text-danger font-weight-bold"> *</span></label>
                        <input type="number" class="form-control font-weight-bold" name="subject_practical" id="subject_practical" value="<?= $subjectData['subject_practical'] ?>" placeholder="Practical Marks">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex p-2 justify-content-end">
                    <button name="subject_update" type="submit" class="btn btn-primary shadow font-weight-bold"> <i class="fa fa-save "></i>&nbsp; Update Subject</button>
                    &nbsp;
                    <a href="index.php" class="btn btn-secondary shadow font-weight-bold"> <i class="fas fa-times "></i>&nbsp; Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        updateSubjectFor();
    });

    function updateSubjectFor() {
        var selectedOption = document.getElementById("subject_course").selectedOptions[0];
        var courseTotal = selectedOption.getAttribute("data-course-total");
        var subjectForSelect = document.getElementById("subject_for");

        // Clear existing options
        subjectForSelect.innerHTML = '<option value="">Select Semester</option>';

        // Populate based on course duration (semesters/years)
        if (courseTotal) {
            for (var i = 1; i <= courseTotal; i++) {
                var option = document.createElement("option");
                option.value = i + " Semester";
                option.text = i + " Semester";
                option.selected = (option.value === "<?= $subjectData['subject_for'] ?>");
                subjectForSelect.appendChild(option);
            }
        }
    }

    function validation(event) {
        var subject_code = document.getElementById("subject_code");
        var subject_name = document.getElementById("subject_name");
        var subject_type = document.getElementById("subject_type");

        if (subject_code.value === "") {
            alert("Please enter the Subject Code.");
            subject_code.focus();
            event.preventDefault();
            return false;
        }

        if (subject_name.value === "") {
            alert("Please enter the Subject Name.");
            subject_name.focus();
            event.preventDefault();
            return false;
        }

        if (subject_type.value === "") {
            alert("Please select the Subject Type.");
            subject_type.focus();
            event.preventDefault();
            return false;
        }

        return true; // Proceed with form submission
    }
</script>

<?php
include "../component/footer.php";
?>
