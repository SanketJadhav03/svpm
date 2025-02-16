<?php
include "../config/connection.php";

// Get filter parameters
$schedule_subject = isset($_GET["schedule_subject"]) ? $_GET["schedule_subject"] : '';
$schedule_date = isset($_GET["schedule_date"]) ? $_GET["schedule_date"] : '';

// Construct query with filters if provided
$whereClause = "";
if (!empty($schedule_subject)) {
    $schedule_subject = mysqli_real_escape_string($conn, $schedule_subject);
    $whereClause .= " AND tbl_exam_schedule.schedule_subject LIKE '%$schedule_subject%'";
}
if (!empty($schedule_date)) {
    $schedule_date = mysqli_real_escape_string($conn, $schedule_date);
    $whereClause .= " AND tbl_exam_schedule.schedule_date = '$schedule_date'";
}

// Query to fetch exam schedules
$query = "SELECT tbl_exam_schedule.*, tbl_course.course_name FROM `tbl_exam_schedule`
          LEFT JOIN tbl_course ON tbl_exam_schedule.schedule_course = tbl_course.course_id
          WHERE 1=1 $whereClause";

$result = mysqli_query($conn, $query);

// Prepare data for the PDF
$examSchedules = [];
while ($data = mysqli_fetch_assoc($result)) {
    $examSchedules[] = [
        'schedule_exam' => $data["schedule_exam"],
        'course_name' => $data["course_name"],
        'schedule_subject' => $data["schedule_subject"],
        'schedule_date' => $data["schedule_date"],
        'schedule_start_time' => $data["schedule_start_time"],
        'schedule_end_time' => $data["schedule_end_time"],
        'schedule_status' => $data["schedule_status"]
    ];
}

// Return the data as JSON for the PDF generation
echo json_encode($examSchedules);
?>
