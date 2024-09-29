<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Check if subject ID is passed via GET
if (isset($_GET['subject_id'])) {
    $subject_id = $_GET['subject_id'];

    // Fetch subject data for the given ID
    $query = "SELECT * FROM tbl_subjects WHERE subject_id = $subject_id";
    $result = mysqli_query($conn, $query);
    $subject = mysqli_fetch_array($result);
}

// Update subject data
if (isset($_POST["subject_update"])) {
    $subject_code = $_POST["subject_code"];
    $subject_course = $_POST["subject_course"];
    $subject_for = $_POST["subject_for"];
    $subject_name = $_POST["subject_name"];
    $subject_type = $_POST["subject_type"];
    $subject_theory = $_POST["subject_theory"];
    $subject_practical = $_POST["subject_practical"];

    // Update query
    $updateQuery = "UPDATE tbl_subjects SET 
                    subject_name='$subject_name',
                    subject_code='$subject_code',
                    subject_type='$subject_type',
                    subject_for='$subject_for',
                    subject_theory='$subject_theory',
                    subject_practical='$subject_practical',
                    subject_course='$subject_course'
                    WHERE subject_id=$subject_id";

    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION["success"] = "Subject Updated Successfully!";
        echo "<script>window.location = 'index.php';</script>";
    }
}
?>

<div class="content-wrapper p-2">
    <form action="" method="post" onsubmit="validation();">
        <div class="card">
            <div class="card-header">
                <div class="d-flex p-2 justify-content-between">
                    <div class="h5 font-weight-bold">Edit Subject</div>
                    <a href="index.php" class="btn btn-info shadow font-weight-bold">
                        <i class="fa fa-eye"></i>&nbsp; Subjects List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <label for="">Course Name <span class="text-danger font-weight-bold"> *</span></label>
                        <select name="subject_course" id="subject_course" class="form-control font-weight-bold" onchange="updateSubjectFor()">
                            <option value="">Select Course</option>
                            <?php
                            $allcourse = "SELECT * FROM tbl_courses";
                            $courseQuery = mysqli_query($conn, $allcourse);
                            while ($course = mysqli_fetch_array($courseQuery)) {
                                $selected = $course["course_id"] == $subject["subject_course"] ? 'selected' : '';
                                echo "<option value='" . $course["course_id"] . "' data-course-total='" . $course['course_total'] . "' data-course-type='" . $course['course_type'] . "' $selected>" . $course["course_name"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-3">
                        <label for="">For Semester Or Year <span class="text-danger font-weight-bold"> *</span></label>
                        <select class="form-control font-weight-bold" name="subject_for" id="subject_for">
                            <option value="">Select Semester/Year</option>
                            <!-- Populate based on the selected course -->
                            <option value="<?= $subject['subject_for'] ?>" selected><?= $subject['subject_for'] ?></option>
                        </select>
                    </div>
                    <div class="col-5">
                        <label for="">Subject Code <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" class="form-control font-weight-bold" name="subject_code" id="subject_code" value="<?= $subject['subject_code'] ?>" placeholder="Subject Code">
                    </div>
                    <div class="col-5 mt-3">
                        <label for="">Subject Name <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" class="form-control font-weight-bold" name="subject_name" id="subject_name" value="<?= $subject['subject_name'] ?>" placeholder="Subject Name">
                    </div>
                    <div class="col-3 mt-3">
                        <label for="">Subject Type <span class="text-danger font-weight-bold"> *</span></label>
                        <select class="form-control font-weight-bold" name="subject_type" id="subject_type">
                            <option value="">Select</option>
                            <option value="1" <?= $subject["subject_type"] == "1" ? "selected" : "" ?>>Core</option>
                            <option value="2" <?= $subject["subject_type"] == "2" ? "selected" : "" ?>>Optional</option>
                        </select>
                    </div>
                    <div class="col-2 mt-3">
                        <label for="">Theory Marks<span class="text-danger font-weight-bold"> *</span></label>
                        <input type="number" class="form-control font-weight-bold" name="subject_theory" id="subject_theory" value="<?= $subject['subject_theory'] ?>" placeholder="Theory Marks">
                    </div>
                    <div class="col-2 mt-3">
                        <label for="">Practical Marks<span class="text-danger font-weight-bold"> *</span></label>
                        <input type="number" class="form-control font-weight-bold" name="subject_practical" id="subject_practical" value="<?= $subject['subject_practical'] ?>" placeholder="Practical Marks">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex p-2 justify-content-end">
                    <button name="subject_update" type="submit" class="btn btn-primary shadow font-weight-bold">
                        <i class="fa fa-save"></i>&nbsp; Update Subject
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
    function updateSubjectFor() {
        // Get the selected option element
        var selectedOption = document.getElementById("subject_course").selectedOptions[0];

        // Get the course total (total number of semesters/years)
        var courseTotal = selectedOption.getAttribute("data-course-total");

        // Get the 'subject_for' select element
        var subjectForSelect = document.getElementById("subject_for");

        // Get the course type (semester or year)
        var courseType = selectedOption.getAttribute("data-course-type");

        // Clear any existing options in the 'subject_for' select element
        subjectForSelect.innerHTML = '<option value="">Select Semester/Year</option>';

        // Populate 'subject_for' with options based on the course_total
        if (courseTotal) {
            for (var i = 1; i <= courseTotal; i++) {
                var option = document.createElement("option");
                option.value = i + (courseType == 1 ? " Semester" : " Year");
                option.text = i + (courseType == 1 ? " Semester" : " Year");
                subjectForSelect.appendChild(option);
            }
        }
    }

    function validation() {
        var subject_code = document.getElementById("subject_code");
        var subject_name = document.getElementById("subject_name");
        var subject_type = document.getElementById("subject_type");
        var subject_total = document.getElementById("subject_total");
        if (subject_code.value == "") {
            subject_code.focus();
            event.preventDefault();
        } else if (subject_name.value == "") {
            subject_name.focus();
            event.preventDefault();
        } else if (subject_type.value == "") {
            subject_type.focus();
            event.preventDefault();
        } else if (subject_total.value == "") {
            subject_total.focus();
            event.preventDefault();
        }
    }
</script>

<?php
include "../component/footer.php";
?>
