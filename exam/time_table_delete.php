<?php
session_start();
include "../config/connection.php"; 
// Check if exam ID is set
if (isset($_GET['schedule_id'])) {
    $schedule_id = $_GET['schedule_id'];

    // Delete the exam from the database
    $deleteQuery = "DELETE FROM tbl_exam_schedule WHERE schedule_id = '$schedule_id'";
    if (mysqli_query($conn, $deleteQuery)) {
        $_SESSION['success'] = "Record deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting record: " . mysqli_error($conn);
    }
} else {
    $_SESSION['error'] = "Invalid record ID!";
}

// Redirect to the exams list page
header('Location: index.php');
exit;
?>
