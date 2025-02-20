<?php
session_start();
include "../config/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $attendance_faculty_id = $_SESSION["faculty_id"];

        // Handle the image upload
        if (!empty($_FILES['photoData']['tmp_name'])) {
            $filename = "attendance_" . $attendance_faculty_id . "_" . time() . ".png";
            $uploadDir = "../assets/images/facultyattendence/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $imagePath = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['photoData']['tmp_name'], $imagePath)) {
                $stmt = $conn->prepare("INSERT INTO tbl_faculty_attendance (attendance_faculty_id, attendance_photo, attendance_latitude, attendance_longitude, attendance_date) VALUES (?, ?, ?, ?, NOW())");
                $stmt->bind_param("isss", $attendance_faculty_id, $filename, $latitude, $longitude);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo json_encode(["status" => "success", "message" => "Attendance recorded successfully."]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Database insertion failed."]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "File upload failed."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "No image uploaded."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Latitude and Longitude are required."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
