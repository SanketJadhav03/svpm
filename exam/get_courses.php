<?php
include "../config/connection.php";

if (isset($_GET['department_id'])) {
    $department_id = mysqli_real_escape_string($conn, $_GET['department_id']);
    $query = "SELECT * FROM tbl_course WHERE course_department_id = '$department_id'";
    $result = mysqli_query($conn, $query);

    $courses = [];
    while ($course = mysqli_fetch_assoc($result)) {
        $courses[] = $course;
    }

    echo json_encode($courses);
}
?>
