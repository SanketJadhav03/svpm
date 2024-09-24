<?php
session_start();
include "../config/connection.php";
$product_id = $_GET["product_id"];
$deleteQuery = "DELETE FROM `tbl_courses` WHERE `product_id` = '$product_id'";
if(mysqli_query($conn,$deleteQuery)){
    $_SESSION["success"] = "Deleted Course Successfully!";
    echo "<script>window.location = 'index.php';</script>";
}

?>