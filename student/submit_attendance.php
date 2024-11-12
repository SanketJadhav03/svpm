<?php
session_start(); // Ensure session is started to access session variables
include "../config/connection.php"; // Include the database connection

// Handle the image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure latitude and longitude are set
    if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

        // Get the student ID from session
        $attendance_student_id = $_SESSION["student_id"];

        // Create a unique filename based on current timestamp and student ID
        $filename = "attendance_" . $attendance_student_id . "_" . time() . ".png";
        $uploadDir = "../assets/images/studentattendence/"; // Specify your upload directory here

        // Check if the uploads directory exists, if not create it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Full path to the image
        $imagePath = $uploadDir . $filename;

        // Check if the photoData is uploaded
        if (isset($_FILES['photoData']) && $_FILES['photoData']['error'] === UPLOAD_ERR_OK) {
            // Move the uploaded file from the temporary location to the desired location
            if (move_uploaded_file($_FILES['photoData']['tmp_name'], $imagePath)) {
                // Insert attendance record into the database
                $stmt = $conn->prepare("INSERT INTO tbl_attendance (attendance_student_id, attendance_photo, attendance_latitude, attendance_longitude, attendance_date) VALUES (?, ?, ?, ?, CURDATE())");
                $stmt->bind_param("isss", $attendance_student_id, $filename, $latitude, $longitude);
                $stmt->execute();

                // Check if the record was successfully inserted
                if ($stmt->affected_rows > 0) {
                    echo "Attendance recorded successfully.";
                } else {
                    echo "Error recording attendance: " . $stmt->error; // Output error message for debugging
                }
            } else {
                echo "Error moving the uploaded file.";
            }
        } else {
            echo "Error uploading file: " . $_FILES['photoData']['error']; // Output file upload error
        }
    } else {
        echo "Latitude and longitude are required.";
    }
} else {
    echo "Invalid request method.";
}
?>
