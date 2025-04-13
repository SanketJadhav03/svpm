<?php
session_start();
include "../config/connection.php"; 
// Check if faculty ID is set
if (isset($_GET['faculty_leave_id'])) {
    $faculty_leave_id = $_GET['faculty_leave_id'];

    // Delete the faculty from the database
    $deleteQuery = "DELETE FROM tbl_faculty_leave WHERE faculty_leave_id = '$faculty_leave_id'";
    if (mysqli_query($conn, $deleteQuery)) {
        $_SESSION['success'] = "Faculty Leave deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting faculty: " . mysqli_error($conn);
    }
} else {
    $_SESSION['error'] = "Invalid faculty ID!";
}

// Redirect to the facultys list page
header('Location: faculty.php');
exit;
?>
