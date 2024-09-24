<?php
header('Content-Type: application/json');
include "../config/connection.php";

$query = "SELECT * FROM `tbl_products`";
$result = mysqli_query($conn, $query);

$courses = []; 
while ($data = mysqli_fetch_assoc($result)) {
    $courses[] = [ 
        'product_name' => $data["product_name"],
        'product_price' => $data["product_price"],
        'product_final_price' => $data["product_final_price"], 
    ];
}

echo json_encode($courses);
?>
