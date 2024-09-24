<?php
session_start();
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";
$course_id = $_GET["course_id"];
$selectQuery = "SELECT * FROM `tbl_courses` WHERE `course_id` = '$course_id'";
$result = mysqli_query($conn,$selectQuery);
$data = mysqli_fetch_array($result);
if(isset($_POST["course_save"])){
    $course_code = $_POST["course_code"];
    $course_name = $_POST["course_name"];
    $course_type = $_POST["course_type"];
    $course_total = $_POST["course_total"]; 
    $insertQuery = "UPDATE `tbl_courses` SET `course_code` = '$course_code',`course_name` = '$course_name',`course_type` = '$course_type',`course_total` = '$course_total' WHERE `course_id` = '$course_id'";
    if(mysqli_query($conn,$insertQuery)){
        $_SESSION["success"] = "Course Updated Successfully!";
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
                    <div class="col-6">
                        <label for="">Course Code <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" value="<?= $data["course_code"]?>" class="form-control font-weight-bold" name="course_code" id="course_code" placeholder="Course Code">
                    </div>
                    <div class="col-6">
                        <label for="">Course Name <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" value="<?= $data["course_name"]?>" class="form-control font-weight-bold" name="course_name" id="course_name" placeholder="Course Name">
                    </div>
                    <div class="col-6 mt-3">
                        <label for="">Semester Or Year <span class="text-danger font-weight-bold"> *</span></label>
                        <select class="form-control font-weight-bold" name="course_type" id="course_type">
                            <option value="">Select</option>
                            <option value="1" <?= $data["course_type"] == 1 ? "selected" :"" ?> >Semester</option>
                            <option value="2" <?= $data["course_type"] == 2 ? "selected" :"" ?> >Year</option>
                        </select>
                    </div>
                    <div class="col-6 mt-3">
                        <label for="">No. of Semester / Year <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="number" value="<?= $data["course_total"]?>" class="form-control font-weight-bold" name="course_total" id="course_total" placeholder="No. of Semester / Year">
                    </div>
                </div>
            </div>
            <div class="card-footer ">
                <div class="d-flex p-2 justify-content-end">
                    <button name="course_save" type="submit" class="btn btn-primary shadow font-weight-bold"> <i class="fa fa-save "></i>&nbsp; Update</button>
                    &nbsp;
                    <button type="reset" class="btn btn-danger shadow font-weight-bold"> <i class="fas fa-sync "></i>&nbsp; Reset</button>
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