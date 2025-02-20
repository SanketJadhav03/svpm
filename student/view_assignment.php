<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php"; 
if (!isset($_SESSION['student_id'])) {
    echo "<script>alert('Unauthorized Access!'); window.location.href='../login.php';</script>";
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch student details
$student_query = "SELECT * FROM tbl_students WHERE student_id = '$student_id'";
$student_result = mysqli_fetch_assoc(mysqli_query($conn, $student_query));

$department_id = $student_result['department_id'];
$course_id = $student_result['course_id'];
$semester = $student_result['semester'];

// Fetch assignments
$assignment_query = "SELECT a.*, f.faculty_name FROM tbl_assignments a
                     INNER JOIN tbl_faculty f ON a.faculty_id = f.faculty_id
                     WHERE a.department_id = '$department_id' 
                     AND a.course_id = '$course_id' 
                     AND a.semester = '$semester' 
                     ORDER BY a.created_at DESC";
$assignments = mysqli_query($conn, $assignment_query);
?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header text-center">
                <h3 class="font-weight-bold">Assignments</h3>
            </div>
            <div class="card-body">
                <?php if (mysqli_num_rows($assignments) > 0) { ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Faculty</th>
                                <th>Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($assignments)) { ?>
                                <tr>
                                    <td><?= $row['assignment_title'] ?></td>
                                    <td><?= $row['assignment_description'] ?></td>
                                    <td><?= $row['faculty_name'] ?></td>
                                    <td>
                                        <a href="../uploads/assignments/<?= $row['assignment_file'] ?>" target="_blank">Download</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <div class="alert alert-warning text-center">No assignments found!</div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php include "../component/footer.php"; ?>
