<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Check if the form is submitted
if (isset($_POST["department_save"])) {
    // Sanitize and get form data
    $department_name = mysqli_real_escape_string($conn, $_POST["department_name"]);
    $department_code = mysqli_real_escape_string($conn, $_POST["department_code"]);
    $department_description = mysqli_real_escape_string($conn, $_POST["department_description"]);
    $department_hod_name = mysqli_real_escape_string($conn, $_POST["department_hod_name"]);
    $department_hod_contact = mysqli_real_escape_string($conn, $_POST["department_hod_contact"]);
    $department_email = mysqli_real_escape_string($conn, $_POST["department_email"]);
    $department_phone = mysqli_real_escape_string($conn, $_POST["department_phone"]);
    $department_password = mysqli_real_escape_string($conn, $_POST["department_password"]);

    // Validate required fields
    if (empty($department_name) || empty($department_code)) {
        $_SESSION["error"] = "Department Name and Code are required!";
    } else {
        // Insert query according to the table structure
        $insertQuery = "INSERT INTO tbl_department 
                        (department_name, department_code, department_description, department_hod_name, department_hod_contact, department_email, department_phone,department_password) 
                        VALUES ('$department_name', '$department_code', '$department_description', '$department_hod_name', '$department_hod_contact', '$department_email', '$department_phone','$department_password')";

        // Execute query
        if (mysqli_query($conn, $insertQuery)) {
            $_SESSION["success"] = "Department Created Successfully!";
            echo "<script>window.location = 'index.php';</script>";
        } else {
            $_SESSION["error"] = "Error in creating department: " . mysqli_error($conn);
        }
    }
}
?>

<div class="content-wrapper p-2">
    <form action="" method="post" onsubmit="validation();">
        <div class="card">
            <div class="card-header">
                <div class="d-flex p-2 justify-content-between">
                    <div class="h5 font-weight-bold">Create Department</div>
                    <a href="index.php" class="btn btn-info shadow font-weight-bold"> 
                        <i class="fa fa-eye"></i>&nbsp; Departments List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Department Name -->
                    <div class="col-6">
                        <label for="department_name">Department Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control font-weight-bold" name="department_name" id="department_name" placeholder="Department Name" required>
                    </div>
                    
                    <!-- Department Code -->
                    <div class="col-6">
                        <label for="department_code">Department Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control font-weight-bold" name="department_code" id="department_code" placeholder="Department Code" required>
                    </div>


                     <!-- HOD Name -->
                     <div class="col-6 mt-3">
                        <label for="department_hod_name">HOD Name</label>
                        <input type="text" class="form-control font-weight-bold" name="department_hod_name" id="department_hod_name" placeholder="HOD Name">
                    </div>
                    
                    <!-- HOD Contact -->
                    <div class="col-6  mt-3">
                        <label for="department_hod_contact">HOD Contact</label>
                        <input type="text" class="form-control font-weight-bold" name="department_hod_contact" id="department_hod_contact" placeholder="HOD Contact">
                    </div>
                    
                     <!-- Department Email -->
                    <div class="col-4 mt-3">
                        <label for="department_email">Department Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control font-weight-bold" name="department_email" id="department_email" placeholder="Department Email">
                    </div>
                    <div class="col-4 mt-3">
                        <label for="department_password">Department Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control font-weight-bold" name="department_password" id="department_password" placeholder="Department Password" required>
                    </div>
                    <!-- Department Phone -->
                    <div class="col-4 mt-3">
                        <label for="department_phone">Department Phone</label>
                        <input type="text" class="form-control font-weight-bold" name="department_phone" id="department_phone" placeholder="Department Phone">
                    </div>
                        <!-- Department Description -->
                        <div class="col-12 mt-3">
                        <label for="department_description">Department Description</label>
                        <textarea class="form-control font-weight-bold" name="department_description" id="department_description" placeholder="Department Description"></textarea>
                    </div>
                    
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex p-2 justify-content-end">
                    <button name="department_save" type="submit" class="btn btn-primary shadow font-weight-bold">
                        <i class="fa fa-save"></i>&nbsp; Add Department
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
    function validation(){
        var department_name = document.getElementById("department_name");
        var department_code = document.getElementById("department_code");
        if(department_name.value == ""){
            department_name.focus();
            event.preventDefault();
        }else if(department_code.value == ""){
            department_code.focus();
            event.preventDefault();
        }
    }
</script>

<?php
include "../component/footer.php";
?>
