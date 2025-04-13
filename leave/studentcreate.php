<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Fetch all students for dropdown
$studentQuery = "SELECT * FROM tbl_students";
$studentResult = mysqli_query($conn, $studentQuery);

// Fetch all courses for dropdown
$courseQuery = "SELECT * FROM tbl_course";
$courseResult = mysqli_query($conn, $courseQuery);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $status = "Pending";
    $remark = mysqli_real_escape_string($conn, $_POST['remark']);
    
    // Calculate holiday count
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $holiday_count = $start->diff($end)->days + 1; // +1 to include both start and end days
    
    $insertQuery = "INSERT INTO tbl_student_leave 
                   (student_id, course_id, student_leave_reason, student_leave_start_date, 
                   student_leave_end_date, student_leave_holiday_count, student_leave_status, student_leave_remark) 
                   VALUES ('$student_id', '$course_id', '$reason', '$start_date', 
                   '$end_date', '$holiday_count', '$status', '$remark')";

    if (mysqli_query($conn, $insertQuery)) {
        $_SESSION["success"] = "Student leave created successfully!";
        echo "<script>window.location.href='student.php';</script>";
        exit();
    } else {
        $_SESSION["error"] = "Error creating student leave: " . mysqli_error($conn);
    }
}
?>

<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <h3 class="font-weight-bold">Create New Student Leave</h3>
        </div>
        
        <div class="card-body">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Student</label>
                            <input type="hidden"  name="student_id" value="<?= $_SESSION["student_id"] ?>" >
                            <select class="form-control" required disabled>
                                <option value="">Select Student</option>
                                <?php while ($student = mysqli_fetch_assoc($studentResult)): ?>
                                    <option value="<?= $student['student_id'] ?>" <?= $student['student_id']==$_SESSION["student_id"]?"selected":"" ?> ><?= $student['student_first_name']." ".$student['student_last_name'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Course</label>
                            <input type="hidden"  name="course_id" value="<?= $_SESSION["student_course"] ?>" >
                            <select  class="form-control" disabled required>
                                <option value="">Select Course</option>
                                <?php while ($course = mysqli_fetch_assoc($courseResult)): ?>
                                    <option value="<?= $course['course_id'] ?>" <?=  $_SESSION["student_course"] == $course["course_id"]?"selected":"" ?>><?= $course['course_name'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold">Reason</label>
                            <textarea name="reason" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Start Date</label>
                            <input type="date" name="start_date" class="form-control" required onchange="calculateDays()" id="start_date">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">End Date</label>
                            <input type="date" name="end_date" class="form-control" required onchange="calculateDays()" id="end_date">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Total Days</label>
                            <input type="text" class="form-control" id="total_days" readonly>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Status</label>
                            <select name="status" disabled class="form-control" required>
                                <option value="Pending" selected>Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-success shadow font-weight-bold">
                        <i class="fas fa-save"></i> &nbsp;Save Leave
                    </button>
                    <a href="student_leave.php" class="btn btn-secondary shadow font-weight-bold ml-2">
                        <i class="fas fa-times"></i> &nbsp;Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function calculateDays() {
    const startDate = new Date(document.getElementById('start_date').value);
    const endDate = new Date(document.getElementById('end_date').value);
    
    if (startDate && endDate && startDate <= endDate) {
        const diffTime = Math.abs(endDate - startDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        document.getElementById('total_days').value = diffDays + " day(s)";
    }
}
</script>

<?php include "../component/footer.php"; ?>