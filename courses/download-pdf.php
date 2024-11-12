<?php
header('Content-Type: application/json');
include "../config/connection.php";

// Adjust the query to join courses with departments
$query = "
    SELECT c.course_code, c.course_name, c.course_total, c.course_type, c.course_fees, 
           d.department_name 
    FROM tbl_courses c 
    JOIN tbl_departments d ON c.department_id = d.department_id"; // Ensure 'department_id' matches your schema

$result = mysqli_query($conn, $query);

$courses = []; 
while ($data = mysqli_fetch_assoc($result)) {
    $courses[] = [ 
        'course_code' => $data["course_code"],
        'course_name' => $data["course_name"],
        'course_total' => $data["course_total"],
        'course_type' => $data["course_type"],
        'course_fees' => $data["course_fees"],
        'department_name' => $data["department_name"], // Add department name
    ];
}

echo json_encode($courses);
?>
