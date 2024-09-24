<?php
session_start();
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";
$product_id = $_GET["product_id"];
$selectQuery = "SELECT * FROM `tbl_courses` WHERE `product_id` = '$product_id'";
$result = mysqli_query($conn,$selectQuery);
$data = mysqli_fetch_array($result);
if(isset($_POST["product_save"])){
    $product_code = $_POST["product_code"];
    $product_name = $_POST["product_name"];
    $product_type = $_POST["product_type"];
    $product_total = $_POST["product_total"]; 
    $insertQuery = "UPDATE `tbl_courses` SET `product_code` = '$product_code',`product_name` = '$product_name',`product_type` = '$product_type',`product_total` = '$product_total' WHERE `product_id` = '$product_id'";
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
                        <input type="text" value="<?= $data["product_code"]?>" class="form-control font-weight-bold" name="product_code" id="product_code" placeholder="Course Code">
                    </div>
                    <div class="col-6">
                        <label for="">Course Name <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" value="<?= $data["product_name"]?>" class="form-control font-weight-bold" name="product_name" id="product_name" placeholder="Course Name">
                    </div>
                    <div class="col-6 mt-3">
                        <label for="">Semester Or Year <span class="text-danger font-weight-bold"> *</span></label>
                        <select class="form-control font-weight-bold" name="product_type" id="product_type">
                            <option value="">Select</option>
                            <option value="1" <?= $data["product_type"] == 1 ? "selected" :"" ?> >Semester</option>
                            <option value="2" <?= $data["product_type"] == 2 ? "selected" :"" ?> >Year</option>
                        </select>
                    </div>
                    <div class="col-6 mt-3">
                        <label for="">No. of Semester / Year <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="number" value="<?= $data["product_total"]?>" class="form-control font-weight-bold" name="product_total" id="product_total" placeholder="No. of Semester / Year">
                    </div>
                </div>
            </div>
            <div class="card-footer ">
                <div class="d-flex p-2 justify-content-end">
                    <button name="product_save" type="submit" class="btn btn-primary shadow font-weight-bold"> <i class="fa fa-save "></i>&nbsp; Update</button>
                    &nbsp;
                    <button type="reset" class="btn btn-danger shadow font-weight-bold"> <i class="fas fa-sync "></i>&nbsp; Reset</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    function validation(){
        var product_code = document.getElementById("product_code");
        var product_name = document.getElementById("product_name");
        var product_type = document.getElementById("product_type");
        var product_total = document.getElementById("product_total");
        if(product_code.value == ""){
            product_code.focus();
            event.preventDefault();
        }else if(product_name.value == ""){
            product_name.focus();
            event.preventDefault();
        }else if(product_type.value == ""){
            product_type.focus();
            event.preventDefault();
        }else if(product_total.value == ""){
            product_total.focus();
            event.preventDefault();
        }
    }
</script>
<?php
include "../component/footer.php";
?>