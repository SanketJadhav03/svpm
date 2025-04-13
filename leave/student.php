<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";
?>

<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-center p-3">
                    <h3 class="font-weight-bold">Student Leave Management</h3>
                </div>
                <div>
                    <form action="">
                        <div class="row justify-content-end">
                            <div class="font-weight-bold mx-2">
                                Student Name
                                <input type="search" name="student_name" value="<?= isset($_GET["student_name"]) ? htmlspecialchars($_GET["student_name"]) : "" ?>" class="form-control font-weight-bold" placeholder="Student Name">
                            </div>
                            <div class="font-weight-bold mx-2">
                                <br>
                                <button type="submit" class="shadow btn w-100 btn-info font-weight-bold"> <i class="fas fa-search"></i> &nbsp;Find</button>
                            </div>
                            <?php if (isset($_SESSION["student_id"])) { ?>
                                <div class="mt-4">
                                    <a href="studentcreate.php" class="btn btn-success shadow font-weight-bold">
                                        <i class="fas fa-plus"></i> &nbsp;Create Leave
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            <?php if (isset($_SESSION["success"])): ?>
                <div class="font-weight-bold alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5 class="font-weight-bold"><i class="icon fas fa-check"></i> Success!</h5>
                    <?= htmlspecialchars($_SESSION["success"]);
                    unset($_SESSION["success"]); ?>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Course</th>
                        <th>Reason</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Days</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $count = 0;
                    $limit = 10;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;
                    $department_id = $_SESSION["department_id"];
                    
                    // Initialize where clause with table aliases
                    $where = "";
                    if($_SESSION["user_role"] == 3){
                        $student_id = $_SESSION["student_id"];
                        $where = " WHERE sl.student_id = '$student_id'";
                    } else {
                        $where = " WHERE c.course_department_id = '$department_id'";
                    }
                    
                    if (isset($_GET["student_name"])) {
                        $student_name = mysqli_real_escape_string($conn, $_GET["student_name"]);
                        if($_SESSION["user_role"] == 3) {
                            $where = " WHERE sl.student_id = '$student_id' AND (s.student_first_name LIKE '%$student_name%' OR s.student_last_name LIKE '%$student_name%')";
                        } else {
                            $where = " WHERE c.course_department_id = '$department_id' AND (s.student_first_name LIKE '%$student_name%' OR s.student_last_name LIKE '%$student_name%')";
                        }
                    }

                    // Using table aliases to avoid ambiguity
                    $countQuery = "SELECT COUNT(*) as total FROM tbl_student_leave sl
                        LEFT JOIN tbl_students s ON s.student_id = sl.student_id 
                        INNER JOIN tbl_course c ON c.course_id = sl.course_id
                        $where";
                        
                    $selectQuery = "SELECT sl.*, s.student_first_name, s.student_last_name, c.course_name 
                        FROM tbl_student_leave sl
                        LEFT JOIN tbl_students s ON s.student_id = sl.student_id 
                        LEFT JOIN tbl_course c ON c.course_id = sl.course_id 
                        $where 
                        ORDER BY sl.student_leave_start_date DESC
                        LIMIT $limit OFFSET $offset";

                    $countResult = mysqli_query($conn, $countQuery);
                    $totalRecords = mysqli_fetch_assoc($countResult)['total'];
                    $totalPages = ceil($totalRecords / $limit);
                    $result = mysqli_query($conn, $selectQuery);

                    while ($data = mysqli_fetch_array($result)) {
                    ?>
                        <tr>
                            <td><?= ++$count ?></td>
                            <td><?= htmlspecialchars($data["student_first_name"]." ".$data["student_last_name"]) ?></td>
                            <td><?= htmlspecialchars($data["course_name"]) ?></td>
                            <td><?= htmlspecialchars($data["student_leave_reason"]) ?></td>
                            <td><?= date('d M Y', strtotime($data["student_leave_start_date"])) ?></td>
                            <td><?= date('d M Y', strtotime($data["student_leave_end_date"])) ?></td>
                            <td><?= $data["student_leave_holiday_count"] ?></td>
                            <td>
                                <span class="badge badge-<?=
                                    $data["student_leave_status"] == 'Approved' ? 'success' : 
                                    ($data["student_leave_status"] == 'Pending' ? 'warning' : 'danger')
                                ?>">
                                    <?= htmlspecialchars($data["student_leave_status"]) ?>
                                </span>
                            </td>
                            <td><?= $data["student_leave_remark"] ? htmlspecialchars($data["student_leave_remark"]) : '--' ?></td>
                            <td>
                                <?php if (!isset($_SESSION["student_id"])) { ?>
                                    <a href="studentedit.php?student_leave_id=<?= $data["student_leave_id"] ?>" class="btn btn-sm shadow btn-info">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                <?php } ?>
                                <a href="studentdelete.php?student_leave_id=<?= $data["student_leave_id"] ?>" 
                                   onclick="return confirm('Are you sure you want to delete this leave record?');" 
                                   class="btn btn-sm shadow btn-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if ($count == 0): ?>
                        <tr>
                            <td colspan="10" class="font-weight-bold text-center text-danger">No Student Leave Records Found.</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <?php if ($totalPages > 1): ?>
        <div class="card-footer">
            <div class="d-flex justify-content-center">
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a class="btn btn-sm btn-outline-info ml-2" href="?page=<?= $page - 1 ?>&student_name=<?= isset($student_name) ? urlencode($student_name) : '' ?>">Previous</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a class="btn btn-sm <?= $page == $i ? "btn-info" : "btn-outline-info" ?> ml-2 shadow" href="?page=<?= $i ?>&student_name=<?= isset($student_name) ? urlencode($student_name) : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                    <?php if ($page < $totalPages): ?>
                        <a class="btn btn-sm btn-outline-info ml-2" href="?page=<?= $page + 1 ?>&student_name=<?= isset($student_name) ? urlencode($student_name) : '' ?>">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include "../component/footer.php"; ?>