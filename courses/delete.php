<?php
session_start();
include "../config/connection.php";
$course_id = $_GET["course_id"];
$deleteQuery = "DELETE FROM `tbl_courses` WHERE `course_id` = '$course_id'";
if(mysqli_query($conn,$deleteQuery)){
    $_SESSION["success"] = "Deleted Course Successfully!";
    echo "<script>window.location = 'index.php';</script>";
}

?>