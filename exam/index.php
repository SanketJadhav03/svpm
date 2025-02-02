<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Fetch exams
$examQuery = "SELECT e.*, c.course_name FROM tbl_exams e 
              JOIN tbl_course c ON e.course_id = c.course_id 
              ORDER BY e.exam_date DESC";
$examResult = mysqli_query($conn, $examQuery);

// Handle deletion
if (isset($_GET["delete"])) {
    $exam_id = mysqli_real_escape_string($conn, $_GET["delete"]);
    $deleteQuery = "DELETE FROM tbl_exams WHERE exam_id = '$exam_id'";

    if (mysqli_query($conn, $deleteQuery)) {
        $_SESSION["success"] = "Exam deleted successfully!";
        echo "<script>window.location = 'index.php';</script>";
    } else {
        $_SESSION["error"] = "Error deleting exam: " . mysqli_error($conn);
    }
}
?>

<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <div class="d-flex p-2 justify-content-between">
                <div class="h5 font-weight-bold">Manage Exams</div>
                <a href="create.php" class="btn btn-primary shadow font-weight-bold">
                    <i class="fa fa-plus"></i>&nbsp; Add Exam
                </a>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Course</th>
                        <th>Exam Title</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($examResult) > 0) {
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($examResult)) { ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $row['course_name']; ?></td>
                                <td><?= $row['exam_title']; ?></td>
                                <td><?= date("d-m-Y", strtotime($row['exam_date'])); ?></td>
                                <td><?= date("h:i A", strtotime($row['exam_start_time'])); ?></td>
                                <td><?= date("h:i A", strtotime($row['exam_end_time'])); ?></td>
                                <td>
                                    <span class="badge <?= ($row['exam_status'] == 'Scheduled') ? 'bg-success' : 'bg-danger'; ?>">
                                        <?= $row['exam_status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit.php?id=<?= $row['exam_id']; ?>" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="index.php?delete=<?= $row['exam_id']; ?>" 
                                       onclick="return confirm('Are you sure you want to delete this exam?');"
                                       class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="8" class="text-center text-danger">No exams found!</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include "../component/footer.php";
?>
