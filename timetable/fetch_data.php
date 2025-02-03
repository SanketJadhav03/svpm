<?php
include "../config/connection.php";

if (isset($_POST["type"])) {
    if ($_POST["type"] == "course" && isset($_POST["department_id"])) {
        $department_id = $_POST["department_id"];
        $query = "SELECT * FROM tbl_course WHERE course_department_id = '$department_id'";
        $result = mysqli_query($conn, $query);

        echo '<option value="">Select Course</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row["course_id"] . '">' . $row["course_name"] . '</option>';
        }
    }

    if ($_POST["type"] == "subject" && isset($_POST["course_id"])) {
        $course_id = $_POST["course_id"];
        if ($course_id) {
            // Fetch the subjects for the selected course grouped by 'subject_for'
            $subjectQuery = "SELECT * FROM tbl_subjects WHERE subject_course = '$course_id' ORDER BY subject_for, subject_name";
            $subjects = mysqli_query($conn, $subjectQuery);

            if (mysqli_num_rows($subjects) > 0) {
                $previous_subject_for = ''; // Variable to track the group

                echo "<option value=''>Select the Subject</option>";
                while ($subject = mysqli_fetch_assoc($subjects)) {
                    // Check if the 'subject_for' has changed to create a new group
                    if ($subject['subject_for'] !== $previous_subject_for) {
                        if ($previous_subject_for !== '') {
                            echo "</optgroup>"; // Close the previous group
                        }
                        echo "<optgroup label='{$subject['subject_for']}'>"; // Open a new group
                        $previous_subject_for = $subject['subject_for']; // Update the group
                    }
                    echo "<option value='{$subject['subject_id']}'>{$subject['subject_name']}</option>"; // Subject option
                }

                echo "</optgroup>"; // Close the last group
            } else {
                echo '<option value="">No subjects found</option>';
            }
        } else {
            echo '<option value="">Select Subject</option>';
        }
    }
}
