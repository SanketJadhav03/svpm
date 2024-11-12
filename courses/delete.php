<?php
session_start();
include "../config/connection.php";

if (isset($_GET["course_id"])) {
    $course_id = $_GET["course_id"];
    
    // Prepare the SQL statement
    $deleteQuery = "DELETE FROM `tbl_courses` WHERE `course_id` = ?";
    
    // Prepare the statement
    $stmt = mysqli_prepare($conn, $deleteQuery);
    
    if ($stmt) {
        // Bind the parameter
        mysqli_stmt_bind_param($stmt, 'i', $course_id); // Assuming course_id is an integer
        
        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION["success"] = "Deleted Course Successfully!";
        } else {
            $_SESSION["error"] = "Error deleting course. Please try again.";
        }
        
        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION["error"] = "Error preparing statement: " . mysqli_error($conn);
    }
} else {
    $_SESSION["error"] = "Invalid course ID.";
}

// Redirect back to index.php
header("Location: index.php");
exit();
?>
