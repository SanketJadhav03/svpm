<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";
?>
<div class="content-wrapper p-2">
    <div class="card ">
        <div class="card-header">
            <div class="text-center p-3">
                <h3 class="font-weight-bold">Student Report</h3>
            </div>
            <form action="">
                <div class="row justify-content-end">
                    <div class="col-2 font-weight-bold">
                        Student Name
                        <input type="text" name="student_name" value="<?= isset($_GET["student_name"]) ? $_GET["student_name"] : "" ?>" class="form-control font-weight-bold" placeholder="Student Name">
                    </div>
                    <div class="col-1 font-weight-bold">
                        <br>
                        <button type="submit" class="shadow btn w-100 btn-info font-weight-bold"> <i class="fas fa-search"></i> &nbsp;Find</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table ">
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Roll No</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Course</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $count = 0;
                    $department_id = isset($_SESSION["department_id"])? $_SESSION["department_id"] : 0;
                    if ($department_id > 0) {

                        $query = "SELECT * FROM `tbl_students` INNER JOIN `tbl_course` ON tbl_course.course_id = tbl_students.student_course WHERE tbl_course.course_department_id = '$department_id'";
                    } else {
                        $query = "SELECT * FROM `tbl_students` INNER JOIN `tbl_course` ON tbl_course.course_id = tbl_students.student_course  ";
                    }
                    if (isset($_GET["student_name"])) {
                        $student_name = mysqli_real_escape_string($conn, $_GET["student_name"]);
                        $query .= " WHERE `student_first_name` LIKE '%$student_name%' OR `student_last_name` LIKE '%$student_name%'";
                    }
                    $result = mysqli_query($conn, $query);
                    while ($data = mysqli_fetch_array($result)) {
                    ?>
                        <tr>
                            <td><?= ++$count ?></td>
                            <td>
                                <img src="<?= $base_url ?>assets/images/student/<?= $data["student_image"] ?: "default.png" ?>" height="100" width="100" alt="Student Image">
                            </td>
                            <td><?= $data["student_roll"] ?></td>
                            <td><?= $data["student_first_name"] . " " . $data["student_last_name"] ?></td>
                            <td><?= $data["student_email"] ?></td>
                            <td><?= $data["student_contact"] ?></td>
                            <td><?= $data["course_name"] ?></td>
                            <td>
                                <a href="view.php?student_id=<?= $data["student_id"] ?>" class="btn btn-sm shadow btn-info">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="upload_result.php?student_id=<?= $data["student_id"] ?>" class="btn btn-sm btn-success">
                                    <i class="fa fa-upload"></i> Result
                                </a>
                            </td>
                        </tr>
                    <?php }
                    if ($count == 0) {
                    ?>
                        <tr>
                            <td colspan="8" class="font-weight-bold text-center">
                                <span class="text-danger">No Students Found.</span>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
include "../component/footer.php";
?>