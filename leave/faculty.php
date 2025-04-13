<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Initialize variables
$faculty_name = isset($_GET["faculty_name"]) ? trim($_GET["faculty_name"]) : "";
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;
$department_id = $_SESSION["department_id"];
$faculty_id = $_SESSION["faculty_id"] ?? 0;
$user_role = $_SESSION["user_role"];
?>

<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-center p-3">
                    <h3 class="font-weight-bold">Faculty Leave Management</h3>
                </div>
                <div>
                    <form action="" method="get">
                        <div class="row justify-content-end">
                            <div class="font-weight-bold mx-2">
                                Faculty Name
                                <input type="search" name="faculty_name" value="<?= htmlspecialchars($faculty_name) ?>" 
                                       class="form-control font-weight-bold" placeholder="Faculty Name">
                            </div>
                            <div class="font-weight-bold mx-2">
                                <br>
                                <button type="submit" class="shadow btn w-100 btn-info font-weight-bold">
                                    <i class="fas fa-search"></i> &nbsp;Find
                                </button>
                            </div>
                            <?php if ($user_role == 5) { ?>
                                <div class="mt-4">
                                    <a href="create.php" class="btn btn-success shadow font-weight-bold">
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
                        <th>Faculty Name</th>
                        <th>Department</th>
                        <th>Reason</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Days</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    // Build WHERE conditions
                    $whereConditions = [];
                    $params = [];
                    $types = '';
                    
                    if ($user_role == 5) {
                        $whereConditions[] = "fl.faculty_id = ?";
                        $params[] = $faculty_id;
                        $types .= 'i';
                    } else {
                        $whereConditions[] = "fl.department_id = ?";
                        $params[] = $department_id;
                        $types .= 'i';
                    }
                    
                    if (!empty($faculty_name)) {
                        $whereConditions[] = "f.faculty_name LIKE ?";
                        $params[] = "%$faculty_name%";
                        $types .= 's';
                    }
                    
                    $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
                    
                    // Count query
                    $countQuery = "SELECT COUNT(*) as total 
                                 FROM tbl_faculty_leave fl
                                 LEFT JOIN tbl_faculty f ON f.faculty_id = fl.faculty_id
                                 $whereClause";
                    
                    // Select query
                    $selectQuery = "SELECT fl.*, f.faculty_name, d.department_name 
                                   FROM tbl_faculty_leave fl
                                   LEFT JOIN tbl_faculty f ON f.faculty_id = fl.faculty_id
                                   INNER JOIN tbl_department d ON d.department_id = fl.department_id
                                   $whereClause 
                                   ORDER BY fl.faculty_leave_start_date DESC
                                   LIMIT ? OFFSET ?";
                    
                    // Prepare and execute count query
                    $stmt = $conn->prepare($countQuery);
                    if (!empty($params)) {
                        $stmt->bind_param($types, ...$params);
                    }
                    $stmt->execute();
                    $countResult = $stmt->get_result();
                    $totalRecords = $countResult->fetch_assoc()['total'];
                    $totalPages = ceil($totalRecords / $limit);
                    
                    // Prepare and execute select query
                    $params[] = $limit;
                    $params[] = $offset;
                    $types .= 'ii';
                    
                    $stmt = $conn->prepare($selectQuery);
                    $stmt->bind_param($types, ...$params);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    $count = 0;
                    while ($data = $result->fetch_assoc()) {
                        $count++;
                    ?>
                        <tr>
                            <td><?= $count ?></td>
                            <td><?= htmlspecialchars($data["faculty_name"]) ?></td>
                            <td><?= htmlspecialchars($data["department_name"]) ?></td>
                            <td><?= htmlspecialchars($data["faculty_leave_reason"]) ?></td>
                            <td><?= date('d M Y', strtotime($data["faculty_leave_start_date"])) ?></td>
                            <td><?= date('d M Y', strtotime($data["faculty_leave_end_date"])) ?></td>
                            <td><?= $data["faculty_leave_holiday_count"] ?></td>
                            <td>
                                <span class="badge badge-<?=
                                    $data["faculty_leave_status"] == 'Approved' ? 'success' : 
                                    ($data["faculty_leave_status"] == 'Pending' ? 'warning' : 'danger')
                                ?>">
                                    <?= htmlspecialchars($data["faculty_leave_status"]) ?>
                                </span>
                            </td>
                            <td><?= $data["faculty_leave_remark"] ? htmlspecialchars($data["faculty_leave_remark"]) : '--' ?></td>
                            <td>
                                <?php if ($user_role == 4) { ?>
                                    <a href="edit.php?faculty_leave_id=<?= $data["faculty_leave_id"] ?>" 
                                       class="btn btn-sm shadow btn-info">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                <?php } ?>
                                <a href="delete.php?faculty_leave_id=<?= $data["faculty_leave_id"] ?>" 
                                   onclick="return confirm('Are you sure you want to delete this leave record?');" 
                                   class="btn btn-sm shadow btn-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                    
                    <?php if ($count == 0): ?>
                        <tr>
                            <td colspan="10" class="font-weight-bold text-center text-danger">
                                No Faculty Leave Records Found.
                            </td>
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
                        <a class="btn btn-sm btn-outline-info ml-2" 
                           href="?page=<?= $page - 1 ?>&faculty_name=<?= urlencode($faculty_name) ?>">
                            Previous
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a class="btn btn-sm <?= $page == $i ? "btn-info" : "btn-outline-info" ?> ml-2 shadow" 
                           href="?page=<?= $i ?>&faculty_name=<?= urlencode($faculty_name) ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <a class="btn btn-sm btn-outline-info ml-2" 
                           href="?page=<?= $page + 1 ?>&faculty_name=<?= urlencode($faculty_name) ?>">
                            Next
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include "../component/footer.php"; ?>