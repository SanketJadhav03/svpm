<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";
if(isset($_POST["course_save"])){
    $course_code = $_POST["course_code"];
    $course_name = $_POST["course_name"];
    $course_type = $_POST["course_type"];
    $course_total = $_POST["course_total"]; 
    $course_fees = $_POST["course_fees"]; 
    $insertQuery = "INSERT INTO `tbl_courses`(`course_code`,`course_name`,`course_type`,`course_total`,`course_fees`) VALUES('$course_code','$course_name','$course_type','$course_total','$course_fees')";
    if(mysqli_query($conn,$insertQuery)){
        $_SESSION["success"] = "Course Created Successfully!";
        echo "<script>window.location = 'index.php';</script>";
    }
}
?>
<div class="content-wrapper p-2">
    <form action="" method="post" onsubmit="validation();">
        <div class="card ">
            <div class="card-header ">
                <div class="d-flex p-2 justify-content-between">
                    <div class="h5 font-weight-bold">Create Course</div>
                    <a href="index.php" class="btn btn-info shadow font-weight-bold"> <i class="fa fa-eye"></i>&nbsp; Courses List</a>
                </div>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <label for="">Course Code <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" class="form-control font-weight-bold" name="course_code" id="course_code" placeholder="Course Code">
                    </div>
                    <div class="col-4">
                        <label for="">Course Name <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" class="form-control font-weight-bold" name="course_name" id="course_name" placeholder="Course Name">
                    </div>
                    <div class="col-4">
                        <label for="">Course Fees ( Per Year ) <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" class="form-control font-weight-bold" name="course_fees" id="course_fees" placeholder="Course Fees">
                    </div>
                    <div class="col-6 mt-3">
                        <label for="">Semester Or Year <span class="text-danger font-weight-bold"> *</span></label>
                        <select class="form-control font-weight-bold" name="course_type" id="course_type">
                            <option value="">Select</option>
                            <option value="1">Semester</option>
                            <option value="2">Year</option>
                        </select>
                    </div>
                    <div class="col-6 mt-3">
                        <label for="">No. of Semester / Year <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="number" class="form-control font-weight-bold" name="course_total" id="course_total" placeholder="No. of Semester / Year">
                    </div>
                </div>
            </div>
            <div class="card-footer ">
                <div class="d-flex p-2 justify-content-end">
                    <button name="course_save" type="submit" class="btn btn-primary shadow font-weight-bold"> <i class="fa fa-save "></i>&nbsp; Add Course</button>
                    &nbsp;
                    <button type="reset" class="btn btn-danger shadow font-weight-bold"> <i class="fas fa-times "></i>&nbsp; Clear</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    function validation(){
        var course_code = document.getElementById("course_code");
        var course_name = document.getElementById("course_name");
        var course_type = document.getElementById("course_type");
        var course_total = document.getElementById("course_total");
        if(course_code.value == ""){
            course_code.focus();
            event.preventDefault();
        }else if(course_name.value == ""){
            course_name.focus();
            event.preventDefault();
        }else if(course_type.value == ""){
            course_type.focus();
            event.preventDefault();
        }else if(course_total.value == ""){
            course_total.focus();
            event.preventDefault();
        }
    }
</script>
<?php
include "../component/footer.php";
?>