<?php
session_start();
include "../config/connection.php"; 
// Check if student ID is set
if (isset($_GET['student_leave_id'])) {
    $student_leave_id = $_GET['student_leave_id'];

    // Delete the student from the database
    $deleteQuery = "DELETE FROM tbl_student_leave WHERE student_leave_id = '$student_leave_id'";
    if (mysqli_query($conn, $deleteQuery)) {
        $_SESSION['success'] = "Student Leave deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting student: " . mysqli_error($conn);
    }
} else {
    $_SESSION['error'] = "Invalid student ID!";
}

// Redirect to the students list page
header('Location: student.php');
exit;
?>
