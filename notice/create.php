<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Handle form submission to create notice
if (isset($_POST["notice_save"])) {
    $notice_title = $_POST["notice_title"];
    $notice_description = $_POST["notice_description"];
    $notice_date = $_POST["notice_date"];

    $insertQuery = "INSERT INTO `tbl_notices`(`notice_title`, `notice_description`, `notice_date`) VALUES('$notice_title', '$notice_description', '$notice_date')";
    if (mysqli_query($conn, $insertQuery)) {
        $_SESSION["success"] = "Notice Created Successfully!";
        echo "<script>window.location = 'index.php';</script>";
    }
}
?>

<div class="content-wrapper p-2">
    <form action="" method="post" onsubmit="validation();">
        <div class="card ">
            <div class="card-header ">
                <div class="d-flex p-2 justify-content-between">
                    <div class="h5 font-weight-bold">Create Notice</div>
                    <a href="index.php" class="btn btn-info shadow font-weight-bold"><i class="fa fa-eye"></i>&nbsp; Notices List</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <label for="">Notice Title <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" class="form-control font-weight-bold" name="notice_title" id="notice_title" placeholder="Notice Title">
                    </div>
                    <div class="col-6">
                        <label for="">Notice Date <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="datetime-local" class="form-control font-weight-bold" name="notice_date" id="notice_date">
                    </div>
                    <div class="col-12 mt-3">
                        <label for="">Notice Description <span class="text-danger font-weight-bold"> *</span></label>
                        <textarea class="form-control font-weight-bold" name="notice_description" id="notice_description" placeholder="Notice Description" rows="5"></textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex p-2 justify-content-end">
                    <button name="notice_save" type="submit" class="btn btn-primary shadow font-weight-bold"><i class="fa fa-save"></i>&nbsp; Add Notice</button>
                    &nbsp;
                    <button type="reset" class="btn btn-danger shadow font-weight-bold"><i class="fas fa-times"></i>&nbsp; Clear</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function validation() {
        var notice_title = document.getElementById("notice_title");
        var notice_description = document.getElementById("notice_description");
        var notice_date = document.getElementById("notice_date");

        if (notice_title.value == "") {
            notice_title.focus();
            event.preventDefault();
        } else if (notice_description.value == "") {
            notice_description.focus();
            event.preventDefault();
        } else if (notice_date.value == "") {
            notice_date.focus();
            event.preventDefault();
        }
    }
</script>

<?php
include "../component/footer.php";
?>
