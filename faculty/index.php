<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";
?>

<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <div class="text-center p-3">
                <h3 class="font-weight-bold">Faculty Management</h3>
            </div>
            <form action="">
                <div class="row justify-content-end">
                    <div class="col-2 font-weight-bold">
                        Faculty Name
                        <input type="search" name="faculty_name" value="<?= isset($_GET["faculty_name"]) ? $_GET["faculty_name"] : "" ?>" class="form-control font-weight-bold" placeholder="Faculty Name">
                    </div>
                    <div class="col-1 font-weight-bold">
                        <br>
                        <button type="submit" class="shadow btn w-100 btn-info font-weight-bold"> <i class="fas fa-search"></i> &nbsp;Find</button>
                    </div>
                    <div class="col-2 font-weight-bold">
                        <br>
                        <a href="create.php" class="font-weight-bold w-100 shadow btn btn-success"> <i class="fas fa-plus"></i>&nbsp; Add Faculty</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            <?php
            if (isset($_SESSION["success"])) {
            ?>
                <div class="font-weight-bold alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5 class="font-weight-bold "><i class="icon fas fa-check"></i> Success!</h5>
                    <?= $_SESSION["success"] ?>
                </div>
            <?php
                unset($_SESSION["success"]);
            }
            ?>

            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>Faculty Name</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Designation</th>
                        <th>Specialization</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $count = 0;
                    $limit = 10;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;
                    $departmentLogin = isset($_SESSION['department_id']) ? $_SESSION['department_id'] : 0;
                    if ($departmentLogin > 0) {
                        $countQuery = "SELECT COUNT(*) as total FROM `tbl_faculty` INNER JOIN tbl_department  ON tbl_department.department_id = tbl_faculty.faculty_department_id WHERE `faculty_department_id` = $departmentLogin";
                        $selectQuery = "SELECT * FROM `tbl_faculty` INNER JOIN tbl_department  ON tbl_department.department_id = tbl_faculty.faculty_department_id WHERE `faculty_department_id` = $departmentLogin LIMIT $limit OFFSET $offset";
                    } else {
                        $countQuery = "SELECT COUNT(*) as total FROM `tbl_faculty` INNER JOIN tbl_department  ON tbl_department.department_id = tbl_faculty.faculty_department_id";
                        $selectQuery = "SELECT * FROM `tbl_faculty` INNER JOIN tbl_department  ON tbl_department.department_id = tbl_faculty.faculty_department_id LIMIT $limit OFFSET $offset";
                    }
                    if (isset($_GET["faculty_name"])) {
                        $faculty_name = $_GET["faculty_name"];
                        $faculty_name = mysqli_real_escape_string($conn, $faculty_name);
                        $countQuery = "SELECT COUNT(*) as total FROM `tbl_faculty` INNER JOIN tbl_department  ON tbl_department.department_id = tbl_faculty.faculty_department_id WHERE `faculty_name` LIKE '%$faculty_name%'";
                        $selectQuery = "SELECT * FROM `tbl_faculty` INNER JOIN tbl_department  ON tbl_department.department_id = tbl_faculty.faculty_department_id WHERE `faculty_name` LIKE '%$faculty_name%' LIMIT $limit OFFSET $offset";
                    }
                    $countResult = mysqli_query($conn, $countQuery);
                    $totalRecords = mysqli_fetch_assoc($countResult)['total'];
                    $totalPages = ceil($totalRecords / $limit);
                    $result = mysqli_query($conn, $selectQuery);
                    while ($data = mysqli_fetch_array($result)) {
                    ?>
                        <tr>
                            <td><?= $count += 1 ?></td>
                            <td><?= $data["faculty_name"] ?></td>
                            <td><?= $data["department_name"] ?></td>
                            <td><?= $data["faculty_email"] ?></td>
                            <td><?= $data["faculty_phone"] ?></td>
                            <td><?= $data["faculty_designation"] ?></td>
                            <td><?= $data["faculty_specialization"] ?></td>
                            <td>
                                <a href="edit.php?faculty_id=<?= $data["faculty_id"] ?>" class="btn btn-sm shadow btn-info">
                                    <i class="fa fa-pen"></i>
                                </a>
                                <a href="delete.php?faculty_id=<?= $data["faculty_id"] ?>" onclick="if(confirm('Are you sure want to delete this faculty?')){return true}else{return false;}" class="btn btn-sm shadow btn-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                    <?php
                    if ($count == 0) {
                    ?>
                        <tr>
                            <td colspan="9" class="font-weight-bold text-center">
                                <span class="text-danger">No Faculty Found.</span>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>

        <div class="card-footer">
            <div class="d-flex justify-content-center">
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a class="btn btn-sm btn-outline-info ml-2" href="?page=<?php echo $page - 1; ?>&faculty_name=<?php echo isset($faculty_name) ? $faculty_name : ''; ?>">Previous</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a class="btn btn-sm <?= $page == $i ? "btn-info" : "btn-outline-info" ?>  ml-2 shadow" href="?page=<?php echo $i; ?>&faculty_name=<?php echo isset($faculty_name) ? $faculty_name : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a class="btn btn-sm btn-outline-info ml-2" href="?page=<?php echo $page + 1; ?>&faculty_name=<?php echo isset($faculty_name) ? $faculty_name : ''; ?>">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include "../component/footer.php";
?>