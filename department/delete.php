<?php
session_start();
include "../config/connection.php";

if (isset($_GET["department_id"])) {
    $department_id = $_GET["department_id"];

    // Prepare the DELETE statement
    $deleteQuery = "DELETE FROM `tbl_department` WHERE `department_id` = ?";
    $stmt = mysqli_prepare($conn, $deleteQuery);

    // Bind the department_id parameter to the statement
    mysqli_stmt_bind_param($stmt, 'i', $department_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION["success"] = "Deleted Department Successfully!";
    } else {
        $_SESSION["error"] = "Failed to delete the department. Please try again.";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    $_SESSION["error"] = "Invalid department ID.";
}

// Redirect back to the index page
echo "<script>window.location = 'index.php';</script>";
?>
