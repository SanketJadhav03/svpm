<?php
header('Content-Type: application/json');
include "../config/connection.php";

// Query to select all students
$query = "SELECT tbl_students.*, tbl_course.course_name 
          FROM tbl_students 
          INNER JOIN tbl_course ON tbl_course.course_id = tbl_students.student_course";

$result = mysqli_query($conn, $query);

// Initialize an array to hold student data
$students = []; 
while ($data = mysqli_fetch_assoc($result)) {
    $students[] = [ 
        'student_id' => $data["student_id"], // Add unique identifier
        'student_roll' => $data["student_roll"],
        'student_first_name' => $data["student_first_name"],
        'student_last_name' => $data["student_last_name"],
        'student_email' => $data["student_email"],
        'student_contact' => $data["student_contact"],
        'student_dob' => $data["student_dob"],
        'student_gender' => $data["student_gender"],
        'student_state' => $data["student_state"],
        'student_city' => $data["student_city"],
        'student_mother_name' => $data["student_mother_name"],
        'student_father_name' => $data["student_father_name"],
        'student_image' => $data["student_image"],
        'course_name' => $data["course_name"],
        'student_type' => $data["student_type"],
    ];
}

// Return the student data as a JSON response
echo json_encode($students);
?>
