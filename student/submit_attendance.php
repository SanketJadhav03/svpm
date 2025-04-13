<?php
session_start();
require "../config/connection.php";

header('Content-Type: application/json');

try {
    // Validate required data
    if (!isset($_SESSION["student_id"])) {
        throw new Exception("Session expired. Please login again.");
    }
    
    if (empty($_POST['period_id']) || empty($_FILES['photo']) || empty($_POST['latitude']) || empty($_POST['longitude'])) {
        throw new Exception("All attendance data is required.");
    }

    // Verify period exists and get subject_id from it
    $stmt = $conn->prepare("SELECT subject_id FROM tbl_regular_time_table WHERE regular_time_table_id = ?");
    $stmt->bind_param("i", $_POST['period_id']);
    $stmt->execute();
    $period = $stmt->get_result()->fetch_assoc();
    
    if (!$period) {
        throw new Exception("Invalid class period specified.");
    }

    // Process image upload
    $uploadDir = "../assets/images/studentattendence/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $filename = "attendance_" . $_SESSION["student_id"] . "_" . time() . ".jpg";
    $filepath = $uploadDir . $filename;
    
    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $filepath)) {
        throw new Exception("Failed to save attendance photo.");
    }

    // Insert attendance record
    $stmt = $conn->prepare("INSERT INTO tbl_attendance (
        attendance_student_id,
        period_id,
        attendance_photo,
        attendance_latitude,
        attendance_longitude,
        attendance_date
    ) VALUES (?, ?, ?, ?, ?, NOW())");
    
    $stmt->bind_param("iisdd", 
        $_SESSION["student_id"],
        $_POST['period_id'],
        $filename,
        $_POST['latitude'],
        $_POST['longitude']
    );
    
    if (!$stmt->execute()) {
        // Delete the uploaded file if DB insert fails
        unlink($filepath);
        throw new Exception("Database error: " . $stmt->error);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Attendance recorded successfully'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>