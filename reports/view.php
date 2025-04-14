<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

if (!isset($_GET["student_id"])) {
    echo "<script>alert('Invalid Request'); window.location.href='index.php';</script>";
    exit();
}

$data_id = mysqli_real_escape_string($conn, $_GET["student_id"]);
$query = "SELECT s.*, c.course_name, c.course_duration FROM `tbl_students` s 
          INNER JOIN `tbl_course` c ON c.course_id = s.student_course
          WHERE s.student_id = '$data_id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_array($result);

if (!$data) {
    echo "<script>alert('Student Not Found'); window.location.href='index.php';</script>";
    exit();
}

$courseName = $data["course_name"];
$courseSemesters = (int) $data["course_duration"]; // Total Semesters from DB
$completedSemesters = 0; // We'll get this from results table

// Get completed semesters count from results
$semesterQuery = "SELECT COUNT(DISTINCT semester) as completed FROM tbl_results WHERE student_id = '$data_id'";
$semesterResult = mysqli_query($conn, $semesterQuery);
if ($semesterRow = mysqli_fetch_assoc($semesterResult)) {
    $completedSemesters = $semesterRow['completed'];
}
$remainingSemesters = max(0, $courseSemesters - $completedSemesters); // Remaining
?>

<div class="content-wrapper p-3">
    <div class="card">
        <div class="card-header text-center py-4">
            <h3 class="font-weight-bold">Student Report - <?= $data["student_first_name"] . " " . $data["student_last_name"] ?></h3>
            <div>Admission Date: <?= date('d/m/Y h:i A', strtotime($data['created_at'])); ?> </div>
        </div>
        <div class="card-body row">
            <div class="mt-4 col-4 text-center">
                <div>
                    <img src="<?= $base_url ?>assets/images/student/<?= $data['student_image'] != "" ? $data['student_image'] : "default.png" ?>"
                        class="img-fluid rounded-circle border border-light"
                        alt="Student Image"
                        style="width: 200px; height: 170px;">
                </div>
                <div class="py-3">
                    <h4 class="text-center">Course Progress</h4>
                    <div class="py-2 pb-2">Total Duration: <?= $courseSemesters . " Semester(s)" ?></div>
                    <canvas id="coursePieChart"></canvas>
                    <div class="d-flex justify-content-between py-3">
                        <div>Completed: <b><?= $completedSemesters ?> Semester(s)</b></div>
                        <div>Remaining: <b><?= $remainingSemesters ?> Semester(s)</b></div>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>Roll No:</th>
                                    <td><?= $data['student_roll'] ?></td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td><?= $data['student_first_name'] . " " . $data["student_last_name"] ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?= $data['student_email'] ?></td>
                                </tr>
                                <tr>
                                    <th>Contact Number:</th>
                                    <td><?= $data['student_contact'] ?></td>
                                </tr>
                                <tr>
                                    <th>Course:</th>
                                    <td><?= $data['course_name'] ?></td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td><?= $data['student_type'] ?></td>
                                </tr>
                                <tr>
                                    <th>Date of Birth:</th>
                                    <td><?= date('d M Y', strtotime($data['student_dob'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Gender:</th>
                                    <td><?= $data['student_gender'] ?></td>
                                </tr>
                                <tr>
                                    <th>Mother's Name:</th>
                                    <td><?= $data['student_mother_name'] ?></td>
                                </tr>
                                <tr>
                                    <th>Father's Name:</th>
                                    <td><?= $data['student_father_name'] ?></td>
                                </tr>
                                <tr>
                                    <th>Mother's Occupation:</th>
                                    <td><?= $data['student_mother_occupation'] ?></td>
                                </tr>
                                <tr>
                                    <th>Father's Occupation:</th>
                                    <td><?= $data['student_father_occupation'] ?></td>
                                </tr>
                                <tr>
                                    <th>State:</th>
                                    <td><?= $data['student_state'] ?></td>
                                </tr>
                                <tr>
                                    <th>City:</th>
                                    <td><?= $data['student_city'] ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <h4 class="text-center mt-4">Student Results</h4>
        <div class="container-fluid">
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
                    <?php
                    $resultQuery = "SELECT * FROM tbl_results WHERE student_id = '$data_id' ORDER BY semester ASC";
                    $resultData = mysqli_query($conn, $resultQuery);
                    while ($row = mysqli_fetch_assoc($resultData)) {
                    ?>
                        <tr>
                            <td>Semester <?= $row['semester'] ?></td>
                            <td><?= $row['result_description'] ?></td>
                            <td><?= $row['percentage'] ?>%</td>
                            <td><a href="../uploads/results/<?= $row['result_file'] ?>" target="_blank">View File</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div>
                <a href="upload_result.php?student_id=<?= $data_id ?>" class="w-100 mb-2 btn btn-primary mt-2">Upload New Result</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('coursePieChart').getContext('2d');
    var coursePieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Completed Semesters', 'Remaining Semesters'],
            datasets: [{
                data: [<?= $completedSemesters ?>, <?= $remainingSemesters ?>],
                backgroundColor: ['#28a745', '#dc3545'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

<?php
include "../component/footer.php";
?>