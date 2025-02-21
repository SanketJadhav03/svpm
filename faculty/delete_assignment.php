<?php
session_start();
include "../config/connection.php"; 
// Check if assignment ID is set
if (isset($_GET['id'])) {
    $assignment_id = $_GET['id'];

    // Delete the assignment from the database
    $deleteQuery = "DELETE FROM tbl_assignments WHERE assignment_id = '$assignment_id'";
    if (mysqli_query($conn, $deleteQuery)) {
        $_SESSION['success'] = "Assignment deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting assignment: " . mysqli_error($conn);
    }
} else {
    $_SESSION['error'] = "Invalid assignment ID!";
}

// Redirect to the assignments list page
header('Location: view_assignments.php');
exit;
?>
