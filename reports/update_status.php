<?php
include "../config/connection.php";
session_start();

// Check if the user is a faculty member
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] == 3) {
    echo "Unauthorized access!";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $assignment_id = $_POST['assignment_id'];
    $student_id = $_POST['student_id'];
    $status = $_POST['status'];

    // Update status query
    $updateQuery = "UPDATE tbl_uploaded_assignments SET uploaded_status = ? WHERE assignment_id = ? AND student_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("iii", $status, $assignment_id, $student_id);

    if ($stmt->execute()) {
        header("Location: studentassignment.php?student_id=" . $student_id);
        exit;
    } else {
        echo "Error updating status.";
    }

    $stmt->close();
}

$conn->close();
?>
