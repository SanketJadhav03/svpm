<?php
header('Content-Type: application/json');
include "../config/connection.php";

$query = "SELECT * FROM `tbl_subjects`";
$result = mysqli_query($conn, $query);

$subjects = []; 
while ($data = mysqli_fetch_assoc($result)) {
    $subjects[] = [ 
        'subject_code' => $data["subject_code"],
        'subject_name' => $data["subject_name"],
        'subject_type' => $data["subject_type"],
        'subject_for' => $data["subject_for"], // Added this field
        'subject_theory' => $data["subject_theory"], // Added this field
        'subject_practical' => $data["subject_practical"], // Added this field
        'subject_course' => $data["subject_course"] // Added this field
    ];
}

echo json_encode($subjects);
?>
