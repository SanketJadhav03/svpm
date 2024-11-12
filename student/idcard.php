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

<!-- Print CSS styles to hide unnecessary elements and format the card -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .print-area, .print-area * {
            visibility: visible;
        }
        .print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
        }
        .card-footer {
            display: none; /* Hide buttons in print */
        }
    }
</style>

<div class="content-wrapper p-4">
    <div class="card border-dark border-0 print-area" style="max-width: 400px; margin: auto;">
        <!-- <div class="card-header text-center  ">
            <h4 class="font-weight-bold">Shivnagar Vidya Prasak Mandal
            </h4>
        </div> -->
        <div class="card-body text-center">
            <div class="mb-3">
                <img src="<?= $base_url ?>assets/images/student/<?= $student['student_image'] != "" ? $student['student_image'] : "default.png" ?>" 
                     class="img-fluid rounded-circle border border-light" 
                     alt="Student Image" 
                     style="width: 120px; height: 120px;">
            </div>
            <h4 class="font-weight-bold"><?= $student['student_first_name'] . ' ' . $student['student_last_name'] ?></h4>
            <p class="text-muted"><?= $student['student_roll'] ?></p>
            <hr>
            <table class="table table-borderless text-left">
                <tbody>
                    <tr>
                        <th>Email:</th>
                        <td><?= $student['student_email'] ?></td>
                    </tr>
                    <tr>
                        <th>Course:</th>
                        <td><?= $student['course_name'] ?></td>
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
                        <th>Contact:</th>
                        <td><?= $student['student_contact'] ?></td>
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
        <div class="card-footer text-center">
            <!-- <a href="edit.php?student_id=<?= $student['student_id'] ?>" class="btn shadow btn-warning">
                <i class="fas fa-edit"></i>&nbsp; Edit
            </a>
            <a href="index.php" class="btn shadow btn-danger">
                <i class="fas fa-times"></i>&nbsp; Back
            </a> -->
            <button onclick="window.print()" class="btn  shadow btn-success">
                <i class="fas fa-print"></i>&nbsp; Generate
            </button>
        </div>
    </div>
</div>

<?php include "../component/footer.php"; ?>
