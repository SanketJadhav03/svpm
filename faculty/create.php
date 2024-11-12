<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Fetch all departments for the dropdown
$departmentQuery = "SELECT * FROM `tbl_department`";
$departmentsResult = mysqli_query($conn, $departmentQuery);

// Check if the form is submitted
if (isset($_POST["faculty_create"])) {
    // Sanitize and get form data
    $faculty_name = mysqli_real_escape_string($conn, $_POST["faculty_name"]);
    $faculty_email = mysqli_real_escape_string($conn, $_POST["faculty_email"]);
    $faculty_password = mysqli_real_escape_string($conn, $_POST["faculty_password"]);
    $faculty_phone = mysqli_real_escape_string($conn, $_POST["faculty_phone"]);
    $faculty_designation = mysqli_real_escape_string($conn, $_POST["faculty_designation"]);
    $faculty_department_id = mysqli_real_escape_string($conn, $_POST["faculty_department_id"]);
    $faculty_specialization = mysqli_real_escape_string($conn, $_POST["faculty_specialization"]);
    $faculty_date_of_joining = mysqli_real_escape_string($conn, $_POST["faculty_date_of_joining"]);

    // Validate required fields
    if (empty($faculty_name) || empty($faculty_email) || empty($faculty_department_id)) {
        $_SESSION["error"] = "Faculty Name, Email, and Department are required!";
    } else {
        // Insert query according to the table structure
        $insertQuery = "INSERT INTO tbl_faculty (
            faculty_name, 
            faculty_email, 
            faculty_password, 
            faculty_phone, 
            faculty_designation, 
            faculty_department_id, 
            faculty_specialization, 
            faculty_date_of_joining
        ) VALUES (
            '$faculty_name', 
            '$faculty_email', 
            '$faculty_password', 
            '$faculty_phone', 
            '$faculty_designation', 
            '$faculty_department_id', 
            '$faculty_specialization', 
            '$faculty_date_of_joining'
        )";

        // Execute query
        if (mysqli_query($conn, $insertQuery)) {
            $_SESSION["success"] = "Faculty Created Successfully!";
            echo "<script>window.location = 'index.php';</script>";
        } else {
            $_SESSION["error"] = "Error in creating faculty: " . mysqli_error($conn);
        }
    }
}
?>

<div class="content-wrapper p-2">
    <form action="" method="post" onsubmit="validation();">
        <div class="card">
            <div class="card-header">
                <div class="d-flex p-2 justify-content-between">
                    <div class="h5 font-weight-bold">Add New Faculty</div>
                    <a href="index.php" class="btn btn-info shadow font-weight-bold">
                        <i class="fa fa-eye"></i>&nbsp; Faculty List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Faculty Name -->
                    <div class="col-4">
                        <label for="faculty_name">Faculty Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control font-weight-bold" name="faculty_name" id="faculty_name" required>
                    </div>

                    <!-- Faculty Email -->
                    <div class="col-4">
                        <label for="faculty_email">Faculty Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control font-weight-bold" name="faculty_email" id="faculty_email" required>
                    </div>

                    <!-- Faculty Password -->
                    <div class="col-4">
                        <label for="faculty_password">Faculty Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control font-weight-bold" name="faculty_password" id="faculty_password" required>
                    </div>

                    <!-- Faculty Phone -->
                    <div class="col-4 mt-3">
                        <label for="faculty_phone">Faculty Phone</label>
                        <input type="text" class="form-control font-weight-bold" name="faculty_phone" id="faculty_phone">
                    </div>

                    <!-- Faculty Date of Joining -->
                    <div class="col-4 mt-3">
                        <label for="faculty_date_of_joining">Date of Joining</label>
                        <input type="date" class="form-control font-weight-bold" name="faculty_date_of_joining" id="faculty_date_of_joining" required>
                    </div>

                    <!-- Faculty Designation -->
                    <div class="col-4 mt-3">
                        <label for="faculty_designation">Faculty Designation</label>
                        <input type="text" class="form-control font-weight-bold" name="faculty_designation" id="faculty_designation">
                    </div>

                    <!-- Faculty Department -->
                    <div class="col-6 mt-3">
                        <label for="faculty_department_id">Department <span class="text-danger">*</span></label>
                        <select name="faculty_department_id" class="form-control font-weight-bold" required>
                            <option value="">Select Department</option>
                            <?php while ($department = mysqli_fetch_assoc($departmentsResult)) { ?>
                                <option value="<?= $department['department_id'] ?>"><?= $department['department_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- Faculty Specialization -->
                    <div class="col-6 mt-3">
                        <label for="faculty_specialization">Faculty Specialization</label>
                        <input type="text" class="form-control font-weight-bold" name="faculty_specialization" id="faculty_specialization">
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex p-2 justify-content-end">
                    <button name="faculty_create" type="submit" class="btn btn-primary shadow font-weight-bold">
                        <i class="fa fa-save"></i>&nbsp; Add Faculty
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
        var faculty_name = document.getElementById("faculty_name");
        var faculty_email = document.getElementById("faculty_email");
        if (faculty_name.value == "") {
            faculty_name.focus();
            event.preventDefault();
        } else if (faculty_email.value == "") {
            faculty_email.focus();
            event.preventDefault();
        }
    }
</script>

<?php
include "../component/footer.php";
?>
