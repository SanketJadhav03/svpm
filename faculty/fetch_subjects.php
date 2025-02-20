<?php
include "../config/connection.php";

if (isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];
    
    // Fetch subjects grouped by semester
    $query = "SELECT * FROM tbl_subjects WHERE subject_course = ? ORDER BY subject_for ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $subjects_by_semester = [];

    while ($row = $result->fetch_assoc()) {
        $semester = $row['subject_for']; // Assuming 'semester' column exists
        $subjects_by_semester[$semester][] = $row;
    }

    $stmt->close();
    $conn->close();

    if (!empty($subjects_by_semester)) {
        foreach ($subjects_by_semester as $semester => $subjects) {
            echo "<optgroup label='$semester'>";
            foreach ($subjects as $subject) {
                echo "<option value='{$subject['subject_id']}'>{$subject['subject_name']}</option>";
            }
            echo "</optgroup>";
        }
    } else {
        echo "<option value=''>No subjects found</option>";
    }
}
?>
