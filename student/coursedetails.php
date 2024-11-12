<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

$studentID = $_SESSION["student_id"];

// Query to get student course details
$studentCourseQuery = "SELECT * FROM tbl_students 
                        INNER JOIN tbl_course ON tbl_students.student_course = tbl_course.course_id  
                        WHERE student_id = '$studentID'";
$resultstudentCourse = mysqli_query($conn, $studentCourseQuery);
$courseData = mysqli_fetch_array($resultstudentCourse);

// Query to get subjects for the student's course, ordered by semester
$subjectsQuery = "SELECT * FROM tbl_subjects 
                    WHERE subject_course = '" . $courseData['course_id'] . "' 
                    ORDER BY subject_for ASC"; // Order by semester (subject_for)
$resultSubjects = mysqli_query($conn, $subjectsQuery);

// Create an array to group subjects by their semester and type (Core/Optional)
$subjectsBySemester = [];

// Sort subjects into the semesters and by type
while ($subject = mysqli_fetch_assoc($resultSubjects)) {
    if ($subject['subject_type'] == 1) {
        $subjectsBySemester[$subject['subject_for']]['core'][] = $subject;
    } else {
        $subjectsBySemester[$subject['subject_for']]['optional'][] = $subject;
    }
}
?>

<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <!-- Display the course code and name -->
                <?= $courseData["course_code"] ?> - <?= $courseData["course_name"] ?>
            </div>
        </div>
        <div class="card-body">
            <h5 class="font-weight-bold">Course Details</h5>
            <p>Course Code: <?= $courseData["course_code"] ?></p>
            <p>Course Name: <?= $courseData["course_name"] ?></p>
            <p>Duration: <?= $courseData["course_duration"] ?> Semesters</p>
            <p>Total Credits: <?= $courseData["course_credits"] ?></p>
            
            <hr>

            <!-- Loop through each semester -->
            <?php foreach ($subjectsBySemester as $semester => $subjects): ?>
                <h6 class="font-weight-bold mt-4">Semester <?= $semester ?> Subjects</h6>

                <!-- Core Subjects Section -->
                <?php if (!empty($subjects['core'])): ?>
                    <h6 class="font-weight-bold text-primary">Core Subjects</h6>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Theory Marks</th>
                                <th>Practical Marks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subjects['core'] as $subject): ?>
                                <tr>
                                    <td><?= $subject["subject_code"] ?></td>
                                    <td><?= $subject["subject_name"] ?></td>
                                    <td><?= $subject["subject_theory"] ?></td>
                                    <td><?= $subject["subject_practical"] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <!-- Optional Subjects Section -->
                <?php if (!empty($subjects['optional'])): ?>
                    <h6 class="font-weight-bold text-success">Optional Subjects</h6>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Theory Marks</th>
                                <th>Practical Marks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subjects['optional'] as $subject): ?>
                                <tr>
                                    <td><?= $subject["subject_code"] ?></td>
                                    <td><?= $subject["subject_name"] ?></td>
                                    <td><?= $subject["subject_theory"] ?></td>
                                    <td><?= $subject["subject_practical"] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
include "../component/footer.php";
?>
