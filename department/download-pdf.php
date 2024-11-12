<?php
header('Content-Type: application/json');
include "../config/connection.php";

// Fetch all departments from the tbl_department table
$query = "SELECT * FROM `tbl_department`";
$result = mysqli_query($conn, $query);

// Initialize an array to hold department data
$departments = []; 

// Fetch each row and store it in the departments array
while ($data = mysqli_fetch_assoc($result)) {
    $departments[] = [ 
        'department_id' => $data["department_id"],                   // Unique ID of the department
        'department_name' => $data["department_name"],               // Name of the department
        'department_code' => $data["department_code"],               // Code of the department
        'department_description' => $data["department_description"], // Description of the department
        'department_hod_name' => $data["department_hod_name"],       // Head of the department name
        'department_hod_contact' => $data["department_hod_contact"], // Head of the department contact
        'department_email' => $data["department_email"],             // Department email
        'department_phone' => $data["department_phone"],             // Department phone number
    ];
}

// Return the departments data as a JSON response
echo json_encode($departments);
?>
