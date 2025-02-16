<?php
header('Content-Type: application/json');
include "../config/connection.php"; 
$query = "SELECT * FROM `tbl_subjects` INNER JOIN `tbl_course` ON tbl_course.course_id = tbl_subjects.subject_course";
$result = mysqli_query($conn, $query);

$subjects = []; 
while ($data = mysqli_fetch_assoc($result)) {
    $subjects[] = [ 
        'subject_code' => $data["subject_code"],
        'subject_name' => $data["subject_name"],
        'subject_type' => $data["subject_type"],
        'subject_for' => $data["subject_for"],  
        'subject_theory' => $data["subject_theory"],  
        'subject_practical' => $data["subject_practical"],  
        'subject_course' => $data["subject_course"]  ,
        'course_name' => $data["course_name"]  ,
    ];
}

echo json_encode($subjects);
?>
