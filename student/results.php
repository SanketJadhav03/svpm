<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php"; 

if (!isset($_SESSION['student_id'])) {
    echo "<script>alert('Unauthorized Access!'); window.location.href='../login.php';</script>";
    exit();
}

$student_id = $_SESSION['student_id'];

$query = "SELECT r.*, s.student_first_name, s.student_last_name 
          FROM tbl_results r 
          INNER JOIN tbl_students s ON s.student_id = r.student_id 
          WHERE r.student_id = '$student_id' 
          ORDER BY r.semester ASC";

$result = mysqli_query($conn, $query);
?>
<div class="content-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header text-center">
                <h3 class="font-weight-bold">Your Semester Results</h3>
            </div>
            <div class="card-body">
                <?php if (mysqli_num_rows($result) > 0) { ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Semester</th>
                                <th>Result Description</th>
                                <th>Percentage</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td>Semester <?= $row['semester'] ?></td>
                                    <td><?= $row['result_description'] ?></td>
                                    <td><?= $row['percentage'] ?>%</td>
                                    <td>
                                        <a href="../uploads/results/<?= $row['result_file'] ?>" target="_blank">View File</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <div class="alert alert-warning text-center">No results found!</div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php
include "../component/footer.php";
?>
