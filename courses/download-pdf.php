<?php
header('Content-Type: application/json');
include "../config/connection.php";

$query = "SELECT * FROM `tbl_courses`";
$result = mysqli_query($conn, $query);

$courses = []; 
while ($data = mysqli_fetch_assoc($result)) {
    $courses[] = [ 
        'course_code' => $data["course_code"],
        'course_name' => $data["course_name"],
        'course_total' => $data["course_total"],
        'course_type' => $data["course_type"],
        'course_fees' => $data["course_fees"],
    ];
}

echo json_encode($courses);
?>
