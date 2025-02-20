<?php
include "../config/connection.php";

if (!isset($_GET["result_id"]) || !isset($_GET["student_id"])) {
    echo "<script>alert('Invalid Request'); window.location.href='index.php';</script>";
    exit();
}

$result_id = mysqli_real_escape_string($conn, $_GET["result_id"]);
$student_id = mysqli_real_escape_string($conn, $_GET["student_id"]);

// Fetch result file path
$resultQuery = "SELECT result_file FROM tbl_results WHERE result_id = '$result_id'";
$resultRes = mysqli_query($conn, $resultQuery);
$resultData = mysqli_fetch_assoc($resultRes);

if ($resultData) {
    $filePath = "../uploads/results/" . $resultData["result_file"];
    
    // Delete file from server
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // Delete from database
    $deleteQuery = "DELETE FROM tbl_results WHERE result_id = '$result_id'";
    if (mysqli_query($conn, $deleteQuery)) {
        echo "<script>alert('Result Deleted Successfully!'); window.location.href='view.php?student_id=$student_id';</script>";
    } else {
        echo "<script>alert('Database Error!');</script>";
    }
} else {
    echo "<script>alert('Result Not Found!'); window.location.href='view.php?student_id=$student_id';</script>";
}
?>
