<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Get the student ID from the URL
$student_id = $_GET['student_id'];

// Fetch the student details from the database
$query = "SELECT * FROM tbl_students INNER JOIN tbl_course ON tbl_course.course_id = tbl_students.student_course WHERE student_id = $student_id";
$student = mysqli_fetch_array(mysqli_query($conn, $query));

if (!$student) {
    echo "<script>alert('Student not found!'); window.location='index.php';</script>";
    exit;
}
?>

<div class="content-wrapper p-4">
    <div class="card shadow border-0">
        <div class="card-header  text-center">
            <h3 class="font-weight-bold p-2">
                <?= $student["student_first_name"]." ".$student["student_last_name"]?>
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    <img src="<?= $base_url ?>assets/images/student/<?= $student['student_image'] != ""?$student['student_image']:"default.png" ?>" 
                         class="img-fluid rounded-circle border border-light" 
                         alt="Student Image" 
                         style="width: 200px; height: 170px;">
                    <h4 class="mt-3"><?= $student['student_first_name'] . ' ' . $student['student_last_name'] ?></h4>
                    <p class="text-muted"><?= $student['student_roll'] ?></p>
                </div>
                <div class="col-md-8">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>Email:</th>
                                <td><?= $student['student_email'] ?></td>
                            </tr>
                            <tr>
                                <th>Contact Number:</th>
                                <td><?= $student['student_contact'] ?></td>
                            </tr>
                            <tr>
                                <th>Course:</th>
                                <td><?= $student['course_name'] ?></td>
                            </tr>
                            <tr>
                                <th>Type:</th>
                                <td><?= $student['student_type'] ?></td>
                            </tr>
                            <tr>
                                <th>Date of Birth:</th>
                                <td><?= date('d M Y', strtotime($student['student_dob'])) ?></td>
                            </tr>
                            <tr>
                                <th>Gender:</th>
                                <td><?= $student['student_gender'] ?></td>
                            </tr>
                            <tr>
                                <th>Mother's Name:</th>
                                <td><?= $student['student_mother_name'] ?></td>
                            </tr>
                            <tr>
                                <th>Father's Name:</th>
                                <td><?= $student['student_father_name'] ?></td>
                            </tr>
                            <tr>
                                <th>Mother's Occupation:</th>
                                <td><?= $student['student_mother_occupation'] ?></td>
                            </tr>
                            <tr>
                                <th>Father's Occupation:</th>
                                <td><?= $student['student_father_occupation'] ?></td>
                            </tr>
                            <tr>
                                <th>State:</th>
                                <td><?= $student['student_state'] ?></td>
                            </tr>
                            <tr>
                                <th>City:</th>
                                <td><?= $student['student_city'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="edit.php?student_id=<?= $student['student_id'] ?>" class="btn shadow btn-warning"> <i class="fas fa-edit"></i>&nbsp; Edit</a>
            <a href="index.php" class="btn shadow btn-danger"> <i class="fas fa-times"></i>&nbsp; Back</a>
        </div>
    </div>
</div>

<?php include "../component/footer.php"; ?>
