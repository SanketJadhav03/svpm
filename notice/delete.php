<?php
session_start();
include "../config/connection.php";

if (isset($_GET['notice_id'])) {
    $notice_id = $_GET['notice_id'];

    // Get the current status
    $statusQuery = "SELECT `notice_status` FROM `tbl_notices` WHERE `notice_id` = '$notice_id'";
    $statusResult = mysqli_query($conn, $statusQuery);
    
    if ($statusResult) {
        $currentStatus = mysqli_fetch_assoc($statusResult)['notice_status'];

        // Toggle the status
        $newStatus = ($currentStatus == 1) ? 0 : 1;

        // Update the notice_status
        $updateQuery = "UPDATE `tbl_notices` SET `notice_status` = '$newStatus' WHERE `notice_id` = '$notice_id'";

        if (mysqli_query($conn, $updateQuery)) {
            $_SESSION["success"] = "Notice status changed successfully!";
            echo "<script>window.location = 'index.php';</script>";
        } else {
            $_SESSION["error"] = "Error updating record: " . mysqli_error($conn);
            echo "<script>window.location = 'index.php';</script>";
        }
    } else {
        $_SESSION["error"] = "Error retrieving record: " . mysqli_error($conn);
        echo "<script>window.location = 'index.php';</script>";
    }
}
?>
