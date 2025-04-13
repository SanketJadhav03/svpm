<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

if (!isset($_GET['faculty_leave_id'])) {
    header("Location: facultyLeave.php");
    exit();
}

$leave_id = mysqli_real_escape_string($conn, $_GET['faculty_leave_id']);

// Fetch leave details
$leaveQuery = "SELECT * FROM tbl_faculty_leave INNER JOIN tbl_faculty ON tbl_faculty.faculty_id = tbl_faculty_leave.faculty_id  WHERE faculty_leave_id = '$leave_id'";
$leaveResult = mysqli_query($conn, $leaveQuery);
$leaveData = mysqli_fetch_assoc($leaveResult);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $remark = mysqli_real_escape_string($conn, $_POST['remark']);

    $updateQuery = "UPDATE tbl_faculty_leave 
                   SET faculty_leave_status = '$status', 
                       faculty_leave_remark = '$remark'
                   WHERE faculty_leave_id = '$leave_id'";

    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION["success"] = "Leave updated successfully!";
        echo "<script>window.location.href='faculty.php';</script>";
        exit();
    } else {
        $_SESSION["error"] = "Error updating leave: " . mysqli_error($conn);
    }
}
?>

<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <h3 class="font-weight-bold">Update Leave Status</h3>
        </div>

        <div class="card-body">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Faculty</label>
                            <input type="text" class="form-control" value="<?= $leaveData['faculty_name'] ?>" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="Pending" <?= $leaveData['faculty_leave_status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Approved" <?= $leaveData['faculty_leave_status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="Rejected" <?= $leaveData['faculty_leave_status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold">Department Remarks</label>
                            <textarea name="remark" class="form-control" rows="3"><?= $leaveData['faculty_leave_remark'] ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-success shadow font-weight-bold">
                        <i class="fas fa-save"></i> &nbsp;Update Leave
                    </button>
                    <a href="faculty.php" class="btn btn-secondary shadow font-weight-bold ml-2">
                        <i class="fas fa-times"></i> &nbsp;Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "../component/footer.php"; ?>