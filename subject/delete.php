<?php
session_start();
include "../config/connection.php"; 
// Check if subject ID is set
if (isset($_GET['subject_id'])) {
    $subject_id = $_GET['subject_id'];

    // Delete the subject from the database
    $deleteQuery = "DELETE FROM tbl_subjects WHERE subject_id = '$subject_id'";
    if (mysqli_query($conn, $deleteQuery)) {
        $_SESSION['success'] = "subject deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting subject: " . mysqli_error($conn);
    }
} else {
    $_SESSION['error'] = "Invalid subject ID!";
}

// Redirect to the subjects list page
header('Location: index.php');
exit;
?>
