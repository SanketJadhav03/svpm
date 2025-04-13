<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Fetch all faculties for dropdown
$facultyQuery = "SELECT * FROM tbl_faculty";
$facultyResult = mysqli_query($conn, $facultyQuery);

// Fetch all departments for dropdown
$departmentQuery = "SELECT * FROM tbl_department";
$departmentResult = mysqli_query($conn, $departmentQuery);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $faculty_id = mysqli_real_escape_string($conn, $_POST['faculty_id']);
    $department_id = mysqli_real_escape_string($conn, $_POST['department_id']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $status = "Pending";
    
    // Calculate holiday count
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $holiday_count = $start->diff($end)->days + 1; // +1 to include both start and end days
    
    $insertQuery = "INSERT INTO tbl_faculty_leave 
                   (faculty_id, department_id, faculty_leave_reason, faculty_leave_start_date, 
                   faculty_leave_end_date, faculty_leave_holiday_count, faculty_leave_status) 
                   VALUES ('$faculty_id', '$department_id', '$reason', '$start_date', 
                   '$end_date', '$holiday_count', '$status')";

    if (mysqli_query($conn, $insertQuery)) {
        $_SESSION["success"] = "Leave created successfully!";
       echo "<script>window.location.href='faculty.php';</script>";
        exit();
    } else {
        $_SESSION["error"] = "Error creating leave: " . mysqli_error($conn);
    }
}
?>

<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <h3 class="font-weight-bold">Create New Leave</h3>
        </div>
        
        <div class="card-body">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Faculty</label>
                            <input type="text" class="form-control"  value="<?= $_SESSION['username'] ?>" readonly>
                            <input type="hidden" class="form-control" name="faculty_id"  value="<?= $_SESSION['faculty_id'] ?>" >
                            <!-- <select name="faculty_id" class="form-control" required>
                                <option value="">Select Faculty</option>
                                <?php while ($faculty = mysqli_fetch_assoc($facultyResult)): ?>
                                    <option value="<?= $faculty['faculty_id'] ?>"><?= $faculty['faculty_name'] ?></option>
                                <?php endwhile; ?>
                            </select> -->
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Department</label>
                            <input type="hidden" value="<?= $_SESSION["department_id"] ?>" name="department_id" class="form-control" required readonly>
                            <select name="department_id" class="form-control" required readonly disabled>
                                <option value="">Select Department</option>
                                <?php while ($department = mysqli_fetch_assoc($departmentResult)): ?>
                                    <option value="<?= $department['department_id'] ?>" <?= $department['department_id'] == $_SESSION["department_id"] ?"selected":"" ?>><?= $department['department_name'] ?></option>
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
                            <select disabled name="status" class="form-control" required>
                                <option value="Pending">Pending</option>
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
                    <a href="faculty.php" class="btn btn-secondary shadow font-weight-bold ml-2">
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