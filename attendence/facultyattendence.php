<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Get filters

$departmentId = isset($_SESSION["department_id"])
    ? $_SESSION["department_id"]
    : (isset($_GET['department_id'])
        ? mysqli_real_escape_string($conn, $_GET['department_id'])
        : '');
$startDate = isset($_GET['start_date']) ? mysqli_real_escape_string($conn, $_GET['start_date']) : date('Y-m-d');
$endDate = isset($_GET['end_date']) ? mysqli_real_escape_string($conn, $_GET['end_date']) : date('Y-m-d');

// Fetch total present faculty in the selected department
$presentQuery = "SELECT COUNT(DISTINCT attendance_faculty_id) AS total_present 
                 FROM tbl_faculty_attendance 
                 WHERE attendance_faculty_id IN 
                 (SELECT faculty_id FROM tbl_faculty WHERE faculty_department_id = '$departmentId')";

if (!empty($startDate) && !empty($endDate)) {
    $presentQuery .= " AND attendance_date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
}

$presentResult = mysqli_query($conn, $presentQuery);
$presentData = mysqli_fetch_assoc($presentResult);
$totalPresent = $presentData['total_present'] ?? 0;

// Fetch total faculty in the department
$totalFacultyQuery = "SELECT COUNT(*) AS total_faculty FROM tbl_faculty WHERE faculty_department_id = '$departmentId'";
$totalFacultyResult = mysqli_query($conn, $totalFacultyQuery);
$totalFacultyData = mysqli_fetch_assoc($totalFacultyResult);
$totalFaculty = $totalFacultyData['total_faculty'] ?? 0;
?>

<div class="content-wrapper">
    <div class="container-fluid p-2">
        <div class="card">
            <div class="card-header">
                <div class="card-title font-weight-bold">Faculty Attendance Summary</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <canvas id="facultyAttendancePieChart"></canvas>
                        <div class="container d-flex font-weight-bold justify-content-between py-3">
                            <div class="text-success">
                                Present: <?php echo $totalPresent; ?>/<?php echo $totalFaculty; ?>
                            </div>
                            <div class="text-danger">
                                Absent: <?php echo $totalFaculty - $totalPresent; ?>/<?php echo $totalFaculty; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-9">
                        <form method="GET" action="">
                            <div class="row">
                                <div class="form-group col-4">
                                    <label for="department">Select Department:</label>
                                    <select <?= isset($_SESSION["department_id"]) ? " disabled" : "" ?> name="department_id" id="department" class="form-control">
                                        <option value="">All Departments</option>
                                        <?php
                                        $deptQuery = "SELECT * FROM tbl_department ORDER BY department_name";
                                        $deptResult = mysqli_query($conn, $deptQuery);
                                        while ($dept = mysqli_fetch_assoc($deptResult)) {
                                            $selected = ($departmentId == $dept['department_id']) ? "selected" : "";
                                            echo "<option value='{$dept['department_id']}' $selected>{$dept['department_name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-3">
                                    <label for="start_date">Start Date:</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control"
                                        value="<?php echo htmlspecialchars($startDate); ?>">
                                </div>
                                <div class="form-group col-3">
                                    <label for="end_date">End Date:</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control"
                                        value="<?php echo htmlspecialchars($endDate); ?>">
                                </div>
                                <div class="col-2">
                                    <button type="submit" class="mt-4 btn btn-primary">Filter</button>
                                    <a href="faculty_attendance.php" class="btn mt-4 btn-secondary">Clear</a>
                                </div>
                            </div>
                        </form>

                        <?php
                        // Query to fetch faculty attendance
                        $query = "SELECT f.faculty_id, f.faculty_name, d.department_name, 
                                  a.* 
                                  FROM tbl_faculty AS f
                                  LEFT JOIN tbl_faculty_attendance AS a 
                                  ON f.faculty_id = a.attendance_faculty_id 
                                  AND a.attendance_date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'
                                  INNER JOIN tbl_department AS d ON f.faculty_department_id = d.department_id";

                        $conditions = [];
                        if (!empty($departmentId)) $conditions[] = "f.faculty_department_id = '$departmentId'";
                        if (!empty($conditions)) $query .= " WHERE " . implode(" AND ", $conditions);
                        $query .= " ORDER BY d.department_name, a.attendance_date DESC";

                        $result = mysqli_query($conn, $query);
                        $totalRecords = mysqli_num_rows($result);
                        ?>

                        <div class="table-responsive">
                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Date & Time</th>

                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($totalRecords > 0) {
                                        $count = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $image = $row['attendance_photo'] ?? "default.png";
                                            $status = $row['attendance_photo'] ? "<span class='badge bg-success'>Present</span>" : "<span class='badge bg-danger'>Absent</span>";
                                            $attendanceDate = $row['attendance_date'] ? date('d/m/Y h:i:s A', strtotime($row['attendance_date'])) : "N/A";
                                            $latitude = $row['attendance_latitude'] ?? "N/A";
                                            $longitude = $row['attendance_longitude'] ?? "N/A";
                                            echo "<tr>
                                                    <td>{$count}</td>
                                                    <td>{$row['faculty_name']}</td> 
                                                    <td>{$row['department_name']}</td>  
                                                    <td>{$attendanceDate}</td>
                                                      <td>{$latitude}</td>
                    <td>{$longitude}</td>
                                                    <td>{$status}</td>  
                                                </tr>";
                                            $count++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'><div class='text-danger font-weight-bold'>No Attendance Found.</div></td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('facultyAttendancePieChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Present', 'Absent'],
            datasets: [{
                data: [<?php echo $totalPresent; ?>, <?php echo $totalFaculty - $totalPresent; ?>],
                backgroundColor: ['#28a745', '#dc3545']
            }]
        }
    });
</script>

<?php include "../component/footer.php"; ?>