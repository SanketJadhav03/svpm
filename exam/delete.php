<?php
session_start();
include "../config/connection.php"; 
// Check if exam ID is set
if (isset($_GET['exam_id'])) {
    $exam_id = $_GET['exam_id'];

    // Delete the exam from the database
    $deleteQuery = "DELETE FROM tbl_exam WHERE exam_id = '$exam_id'";
    if (mysqli_query($conn, $deleteQuery)) {
        $_SESSION['success'] = "Exam deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting exam: " . mysqli_error($conn);
    }
} else {
    $_SESSION['error'] = "Invalid exam ID!";
}

// Redirect to the exams list page
header('Location: index.php');
exit;
?>
