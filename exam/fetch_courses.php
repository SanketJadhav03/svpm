<?php
include "../config/connection.php";

// Check if department ID is passed
if (isset($_GET['department_id'])) {
    $department_id = $_GET['department_id'];

    // Fetch courses related to the department
    $courseQuery = "SELECT * FROM tbl_course WHERE course_department_id = '$department_id'";
    $courseResult = mysqli_query($conn, $courseQuery);

    // Generate the options for the course dropdown
    if (mysqli_num_rows($courseResult) > 0) {
        echo '<option value="">Select Course</option>';
        while ($course = mysqli_fetch_assoc($courseResult)) {
            echo '<option value="' . $course['course_id'] . '">' . $course['course_name'] . '</option>';
        }
    } else {
        echo '<option value="">No courses available</option>';
    }
}
?>
