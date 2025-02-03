<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Fetch timetable records with joins
$query = "SELECT rt.*, d.department_name, c.course_name, s.subject_name, f.faculty_name 
          FROM tbl_regular_time_table rt
          INNER JOIN tbl_department d ON rt.department_id = d.department_id
          INNER JOIN tbl_course c ON rt.course_id = c.course_id
          INNER JOIN tbl_subjects s ON rt.subject_id = s.subject_id
          INNER JOIN tbl_faculty f ON rt.faculty_id = f.faculty_id
          ORDER BY FIELD(rt.period_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), rt.period_start_time,rt.period_end_time";

$result = mysqli_query($conn, $query);
?>

<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <div class="d-flex p-2 justify-content-between">
                <div class="h5 font-weight-bold">Regular Lecture Time Table</div>
                <a href="create.php" class="btn btn-success shadow font-weight-bold">
                    <i class="fa fa-plus"></i>&nbsp; Add New Timetable Entry
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION["success"])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION["success"];
                    unset($_SESSION["success"]); ?>
                </div>
            <?php elseif (isset($_SESSION["error"])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION["error"];
                    unset($_SESSION["error"]); ?>
                </div>
            <?php endif; ?>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Department</th>
                        <th>Course</th>
                        <th>Subject</th>
                        <th>Faculty</th>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $count = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $count++; ?></td>
                                <td><?= $row['department_name']; ?></td>
                                <td><?= $row['course_name']; ?></td>
                                <td><?= $row['subject_name']; ?></td>
                                <td><?= $row['faculty_name']; ?></td>
                                <td><?= $row['period_day']; ?></td>
                                <td><?= date("h:i A", strtotime($row['period_start_time'])); ?></td>
                                <td><?= date("h:i A", strtotime($row['period_end_time'])); ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $row['regular_time_table_id']; ?>" class="btn btn-info btn-sm">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                    <a href="delete.php?id=<?= $row['regular_time_table_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                   
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No records found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include "../component/footer.php";
?>
