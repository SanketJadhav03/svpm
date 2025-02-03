<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Fetch exams data
$examQuery = "SELECT e.exam_id, e.exam_title, e.exam_description, e.exam_start_date, e.exam_end_date, e.exam_status, d.department_name, c.course_name 
              FROM tbl_exam e 
              JOIN tbl_department d ON e.exam_department_id = d.department_id
              JOIN tbl_course c ON e.exam_course_id = c.course_id";
$examResult = mysqli_query($conn, $examQuery);

?>

<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <div class="d-flex p-2 justify-content-between">
                <div class="h5 font-weight-bold">Exams List</div>
                <a href="create.php" class="btn btn-success shadow font-weight-bold">
                    <i class="fa fa-plus"></i>&nbsp; Add New Exam
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

            <table class="table bordered table- ">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Exam Title</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Department</th>
                        <th>Course</th>
                        <th>Time Table</th>
                        <!-- <th>Que. Papers</th> -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($examResult) > 0): ?>
                        <?php $count = 1;  while ($exam = mysqli_fetch_assoc($examResult)): ?>
                            <tr>
                                <td><?= $count++; ?></td>
                                <td><?= $exam['exam_title']; ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($exam['exam_start_date'])); ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($exam['exam_end_date'])); ?></td>
                                <td><?= $exam['exam_status']; ?></td>
                                <td><?= $exam['department_name']; ?></td>
                                <td><?= $exam['course_name']; ?></td>
                                <td>
                                    <a href="time_table_list.php?exam_id=<?= $exam['exam_id']; ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-calendar-alt"></i>&nbsp; Time Table
                                    </a>
                                </td>
                                <!-- <td>
                                    <a href="question_papers.php?exam_id=<?= $exam['exam_id']; ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-file-alt"></i>&nbsp; Que. Papers
                                    </a>
                                </td> -->

                                <td>
                                    <a href="edit.php?exam_id=<?= $exam['exam_id']; ?>" class="btn btn-info btn-sm">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                    <a href="delete.php?exam_id=<?= $exam['exam_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this exam?');">
                                        <i class="fa fa-trash"></i>
                                    </a>

                                </td>

                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">No exams found</td>
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