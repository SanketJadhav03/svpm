<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";
?>
<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <div class="text-center p-3">
                <h3 class="font-weight-bold">Subject Management</h3>
            </div>
            <form action="" method="GET">
                <div class="row justify-content-end">
                    <div class="col-2 font-weight-bold">
                        Subject Name
                        <input type="text" name="subject_name" value="<?= isset($_GET["subject_name"]) ? $_GET["subject_name"] : "" ?>" class="form-control font-weight-bold" placeholder="Subject Name">
                    </div>
                    <div class="col-2 font-weight-bold">
                        Course
                        <select name="course_id" class="form-control font-weight-bold">
                            <option value="">Select Course</option>
                            <?php
                            // Fetch courses from database to populate the dropdown
                            $courseQuery = "SELECT * FROM tbl_course";
                            $courseResult = mysqli_query($conn, $courseQuery);
                            while ($course = mysqli_fetch_array($courseResult)) {
                                $selected = (isset($_GET["course_id"]) && $_GET["course_id"] == $course["course_id"]) ? "selected" : "";
                                echo "<option value='" . $course["course_id"] . "' $selected>" . $course["course_name"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-1 font-weight-bold">
                        <br>
                        <button type="submit" class="shadow btn w-100 btn-info font-weight-bold"> <i class="fas fa-search"></i> &nbsp;Find</button>
                    </div>
                    <div class="col-1 font-weight-bold">
                        <br>
                        <button type="button" class="shadow btn w-100 font-weight-bold btn-primary" id="download-excel"><i class="fas fa-file-excel"></i> &nbsp; Excel</button>
                    </div>
                    <div class="col-1 font-weight-bold">
                        <br>
                        <button type="button" class="shadow btn w-100 font-weight-bold btn-danger" id="download-pdf"><i class="fas fa-file-pdf"></i> &nbsp; PDF</button>
                    </div>
                    <div class="col-1 font-weight-bold">
                        <br>
                        <button type="button" class="shadow btn w-100 font-weight-bold btn-secondary" id="print-page"><i class="fa fa-print"></i> &nbsp;Print</button>
                    </div>
                    <div class="col-2 text-right font-weight-bold">
                        <br>
                        <a href="create.php" class="font-weight-bold w-100 shadow btn btn-success"> <i class="fas fa-plus"></i>&nbsp; Add Subject</a>
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
                        <th>Department</th>
                        <th>Course</th>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Subject For</th>
                        <th>Subject Type</th>
                        <th>Theory Marks</th>
                        <th>Practical Marks</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $count = 0;
                    $limit = 10;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;
                    $countQuery = "SELECT COUNT(*) as total FROM `tbl_subjects` INNER JOIN `tbl_course` ON tbl_course.course_id = tbl_subjects.subject_course ";
                    $selectQuery = "SELECT * FROM `tbl_subjects` INNER JOIN `tbl_course` ON tbl_course.course_id = tbl_subjects.subject_course LEFT JOIN tbl_department ON tbl_course.course_department_id = tbl_department.department_id";
                    $whereClause = ""; // Initialize as empty

                    // Check if the user role exists and is associated with a department
                    if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == 4 && isset($_SESSION["department_id"])) {
                        $department_id = $_SESSION["department_id"];
                        $whereClause = " WHERE tbl_course.course_department_id = $department_id";
                    }

                    // Add condition for course filter
                    if (isset($_GET["course_id"]) && $_GET["course_id"] != "") {
                        $course_id = $_GET["course_id"];
                        $whereClause .= " AND tbl_subjects.subject_course = $course_id";
                    }

                    $countQuery .= $whereClause;
                    $selectQuery = $selectQuery . $whereClause . " ORDER BY course_name LIMIT $limit OFFSET $offset";

                    // Subject name filter
                    if (isset($_GET["subject_name"])) {
                        $subject_name = $_GET["subject_name"];
                        $subject_name = mysqli_real_escape_string($conn, $subject_name);
                        $countQuery = "SELECT COUNT(*) as total FROM `tbl_subjects` WHERE `subject_name` LIKE '%$subject_name%'";
                        $selectQuery = "SELECT * FROM `tbl_subjects` INNER JOIN `tbl_course` ON tbl_course.course_id = tbl_subjects.subject_course LEFT JOIN tbl_department ON tbl_course.course_department_id = tbl_department.department_id WHERE `subject_name` LIKE '%$subject_name%' ";

                        // Apply department condition
                        if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == 4 && isset($_SESSION["department_id"])) {
                            $department_id = $_SESSION["department_id"];
                            $whereClause = " AND tbl_course.course_department_id = $department_id";
                        }
                        $selectQuery = $selectQuery . $whereClause . " ORDER BY course_name LIMIT $limit OFFSET $offset";
                    }

                    $countResult = mysqli_query($conn, $countQuery);
                    $totalRecords = mysqli_fetch_assoc($countResult)['total'];
                    $totalPages = ceil($totalRecords / $limit);
                    $result = mysqli_query($conn, $selectQuery);
                    while ($data = mysqli_fetch_array($result)) {
                    ?>
                        <tr>
                            <td><?= $count += 1 ?></td>
                            <td><?= $data["department_name"] ?></td>
                            <td><?= $data["course_name"] ?></td>
                            <td><?= $data["subject_code"] ?></td>
                            <td><?= $data["subject_name"] ?></td>
                            <td><?= $data["subject_for"] ?></td>
                            <td><?= $data["subject_type"] == 1 ? "Core" : "Optional" ?></td>
                            <td><?= $data["subject_theory"] ?></td>
                            <td><?= $data["subject_practical"] ?></td>
                            <td>
                                <a href="edit.php?subject_id=<?= $data["subject_id"] ?>" class="btn mb-1 btn-sm shadow btn-info">
                                    <i class="fa fa-pen"></i>
                                </a>
                                <a href="delete.php?subject_id=<?= $data["subject_id"] ?>" onclick="if(confirm('Are you sure want to delete this subject?')){return true}else{return false;}" class="btn btn-sm shadow btn-danger">
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
                                <span class="text-danger">Subjects Not Found.</span>
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
                        <a class="btn btn-sm btn-outline-info ml-2" href="?page=<?php echo $page - 1; ?>&subject_name=<?php echo isset($subject_name) ? $subject_name : ''; ?>&course_id=<?php echo isset($course_id) ? $course_id : ''; ?>">Previous</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a class="btn btn-sm <?= $page == $i?"btn-info":"btn-outline-info" ?> ml-2 shadow" href="?page=<?php echo $i; ?>&subject_name=<?php echo isset($subject_name) ? $subject_name : ''; ?>&course_id=<?php echo isset($course_id) ? $course_id : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a class="btn btn-sm btn-outline-info ml-2" href="?page=<?php echo $page + 1; ?>&subject_name=<?php echo isset($subject_name) ? $subject_name : ''; ?>&course_id=<?php echo isset($course_id) ? $course_id : ''; ?>">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "../component/footer.php";
?>
