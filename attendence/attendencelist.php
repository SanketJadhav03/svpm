<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php"; 
?>

<div class="content-wrapper">
    <div class="container-fluid p-2">
        <div class="card">
            <div class="card-header">
                <div class="card-title font-weight-bold">
                    Attendance List
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Form -->
                <form method="GET" action="">
                <div class="row">

                    <div class="form-group col-4">
                        <label for="start_date">Start Date:</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" 
                        value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>">
                    </div>
                    <div class="form-group col-4">
                        <label for="end_date">End Date:</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" 
                        value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>">
                    </div>
                    <div class="col-4">
                        
                        <button type="submit" class="mt-4 btn btn-primary">Filter</button>
                        <a href="attendance_list.php" class="btn mt-4 btn-secondary">Clear Filter</a> <!-- Clear filter link -->
                    </div>
                </div>
                </form>
                
                <?php
                // Get selected dates from the form
                $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
                $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

                // Query to select attendance records with an optional date range filter
                $query = "SELECT * FROM tbl_attendance AS a
                          INNER JOIN tbl_students AS s ON a.attendance_student_id = s.student_id";
                
                // Apply date range filter if both start and end dates are selected
                if (!empty($startDate) && !empty($endDate)) {
                    $query .= " WHERE a.attendance_date BETWEEN '$startDate' AND '$endDate'";
                }

                $result = mysqli_query($conn, $query);
                $totalRecords = mysqli_num_rows($result); // Get the total number of rows
                ?>
                
                <p class="font-weight-bold">Total Records: <?php echo $totalRecords; ?></p> <!-- Display total records count -->

                <div class="table-responsive">
                    <table class="table text-center">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Time</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($totalRecords > 0) {
                            $count = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                    <td>{$count}</td>
                                    <td>{$row['student_first_name']}</td>
                                    <td>{$row['attendance_time']}</td>
                                    <td>{$row['attendance_date']}</td>
                                </tr>";
                                $count++;
                            }
                        } else {
                            echo "<tr>
                                    <td colspan='4'>
                                        <div class='text-danger font-weight-bold'>No Attendance Found.</div>
                                    </td>
                                  </tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include "../component/footer.php";
?>
