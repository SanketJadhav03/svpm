<?php
include "../config/connection.php";
if (isset($_POST["product_save"])) { 

     
    $product_image =  $_FILES["product_image"]["name"];
    $product_name = $_POST["product_name"];  
    $product_qty = $_POST["product_qty"];  
    $product_price = $_POST["product_price"];  
    $product_brand = $_POST["product_brand"];  
    $product_category = $_POST["product_category"];  
    $product_total_price = $_POST["product_total_price"];  
    $product_discount = $_POST["product_discount"];  
    $product_discount_price = $_POST["product_discount_price"];  
    $product_final_price = $_POST["product_final_price"];   
    $insertQuery ="INSERT INTO `tbl_products`( `product_name`, `product_price`, `product_qty`, `product_total_price`, `product_discount`, `product_discount_price`, `product_final_price`, `product_brand`, `product_category`, `product_image`) VALUES ( '$product_name', '$product_price', '$product_qty', '$product_total_price', '$product_discount', '$product_discount_price', '$product_final_price', '$product_brand', '$product_category', '$product_image')";
    if (mysqli_query($conn, $insertQuery)) {
        $_SESSION["success"] = "Product Created Successfully!";
        echo "<script>window.location = 'index.php';</script>";
    }
}
?>