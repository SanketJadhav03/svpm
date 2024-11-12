<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

$queryRoll = "SELECT COUNT(*) AS student_count FROM tbl_students;";
$dataRoll = mysqli_fetch_array(mysqli_query($conn, $queryRoll));
if (isset($_POST["student_save"])) {
    $student_first_name = $_POST["student_first_name"];
    $student_last_name = $_POST["student_last_name"];
    $student_email = $_POST["student_email"];
    $student_contact = $_POST["student_contact"];
    $student_state = $_POST["student_state"];
    $student_city = $_POST["student_city"];
    $student_mother_name = $_POST["student_mother_name"];
    $student_father_name = $_POST["student_father_name"];
    $student_mother_occupation = $_POST["student_mother_occupation"];
    $student_father_occupation = $_POST["student_father_occupation"];
    $student_course = $_POST["student_course"];
    $student_roll = $_POST["student_roll"];
    $student_type = $_POST["student_type"];
    $student_dob = $_POST["student_dob"];
    $student_gender = $_POST["student_gender"];

    // Handle file upload
    if (isset($_FILES["student_image"]["name"])) {
        $target_dir = $base_url."assets/images/student";
        $imageFileName = basename($_FILES["student_image"]["name"]);
        $target_file = $target_dir . $imageFileName;
        move_uploaded_file($_FILES["student_image"]["tmp_name"], $target_file);
    } else {
        $imageFileName = 'default.png'; // In case no image is uploaded
    }

    $insertQuery = "INSERT INTO `tbl_students`(`student_first_name`, `student_last_name`, `student_email`, `student_contact`, `student_state`, `student_city`, `student_mother_name`, `student_father_name`, `student_mother_occupation`, `student_father_occupation`, `student_course`, `student_roll`, `student_type`, `student_dob`, `student_image`, `student_gender`)
    VALUES ('$student_first_name', '$student_last_name', '$student_email', '$student_contact', '$student_state', '$student_city', '$student_mother_name', '$student_father_name', '$student_mother_occupation', '$student_father_occupation', '$student_course', '$student_roll', '$student_type', '$student_dob', '$imageFileName', '$student_gender')";

    if (mysqli_query($conn, $insertQuery)) {
        $_SESSION["success"] = "Student Created Successfully!";
        echo "<script>window.location = 'index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<div class="content-wrapper p-2">
    <form action="" method="post" onsubmit="validation();" enctype="multipart/form-data">
        <div class="card ">
            <div class="card-header ">
                <div class="d-flex p-2 justify-content-between">
                    <div class="h5 font-weight-bold">Create Student</div>
                    <a href="index.php" class="btn btn-info shadow font-weight-bold"> <i class="fa fa-eye"></i>&nbsp; Students List</a>
                </div>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <label for="">Roll No <span class="text-danger font-weight-bold"> *</span></label>
                        <input value="<?= $dataRoll["student_count"] + 1000 ?>" type="text" style="cursor: not-allowed;" readonly class="form-control font-weight-bold" name="student_roll" id="student_roll" placeholder="Student Roll Numberr">
                    </div>
                    <div class="col-4">
                        <label for="">Course <span class="text-danger font-weight-bold"> *</span></label>
                        <select name="student_course" id="student_course" class="form-control font-weight-bold">
                            <option value="">Select Course</option>
                            <?php
                            // Query to get all departments
                            $alldepartment = "SELECT * FROM tbl_department";
                            $departmentQuery = mysqli_query($conn, $alldepartment);

                            // Loop through each department
                            while ($department = mysqli_fetch_array($departmentQuery)) {
                                // Query to get courses for the current department
                                $courseQuery = mysqli_query($conn, "SELECT * FROM tbl_course WHERE course_department_id = " . $department['department_id']);

                                // Display department as an optgroup
                                if (mysqli_num_rows($courseQuery) > 0) {
                                    echo '<optgroup label="' . $department["department_name"] . '">';

                                    // Loop through each course within this department
                                    while ($course = mysqli_fetch_array($courseQuery)) {
                                        echo '<option value="' . $course['course_id'] . '">' . $course["course_name"] . '</option>';
                                    }

                                    echo '</optgroup>';
                                }
                            }
                            ?>
                        </select>

                    </div>
                    <!-- <div class="col-2">
                        <label for="">Course Duration <span class="text-danger font-weight-bold"> *</span></label>
                        <input readonly style="cursor: not-allowed;" type="text" class="form-control font-weight-bold" name="student_duration" id="student_duration" placeholder="Course Duration">
                    </div>

                    <div class="col-2">
                        <label for="">Course Fees <span class="text-danger font-weight-bold"> *</span></label>
                        <input readonly style="cursor: not-allowed;" type="text" class="form-control font-weight-bold" name="student_fees" id="student_fees" placeholder="Course Fees">
                    </div> -->

                    <div class="col-4">
                        <label for="">Student Type <span class="text-danger font-weight-bold"> *</span></label>
                        <select name="student_type" id="student_type" class="form-control font-weight-bold">
                            <option value="">Select Type</option>
                            <option value="Part-time">Part-time</option>
                            <option value="Full-time">Full-time</option>
                        </select>
                    </div>
                    <div class="col-3 mt-4  ">
                        <label for="">First Name <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" class="form-control font-weight-bold" name="student_first_name" id="student_first_name" placeholder="First Name">
                    </div>
                    <div class="col-3 mt-4  ">
                        <label for="">Last Name <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" class="form-control font-weight-bold" name="student_last_name" id="student_last_name" placeholder="Last Name">
                    </div>
                    <div class="col-3 mt-4  ">
                        <label for="">Email <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="email" class="form-control font-weight-bold" name="student_email" id="student_email" placeholder="Email Address">
                    </div>
                    <div class="col-3 mt-4  ">
                        <label for="">Contact Number <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" class="form-control font-weight-bold" name="student_contact" id="student_contact" placeholder="Contact Number">
                    </div>


                    <div class="col-2 mt-4  ">
                        <label for="">Mother's Name <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" class="form-control font-weight-bold" name="student_mother_name" id="student_mother_name" placeholder="Mother Name">
                    </div>
                    <div class="col-3 mt-4  ">
                        <label for="">Mother's Occupation <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" class="form-control font-weight-bold" name="student_mother_occupation" id="student_mother_occupation" placeholder="Mother Occupation">
                    </div>
                    <div class="col-2 mt-4  ">
                        <label for="">Father's Name <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" class="form-control font-weight-bold" name="student_father_name" id="student_father_name" placeholder="Father Name">
                    </div>
                    <div class="col-3 mt-4  ">
                        <label for="">Father's Occupation <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" class="form-control font-weight-bold" name="student_father_occupation" id="student_father_occupation" placeholder="Father Occupation">
                    </div>
                    <div class="col-2 mt-4  ">
                        <label for="">Date Of Birth <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="date" class="form-control font-weight-bold" name="student_dob" id="student_dob">
                    </div>
                    <div class="col-2 mt-4  ">
                        <label for="">Gender <span class="text-danger font-weight-bold"> *</span></label>
                        <select name="student_gender" class="form-control font-weight-bold" id="student_gender">
                            <option value="">Select Gender</option>
                            <option value="Female">Female</option>
                            <option value="Male">Male</option>
                        </select>
                    </div>
                    <div class="col-2 mt-4  ">
                        <label for="">State <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" class="form-control font-weight-bold" name="student_state" id="student_state" value="Maharashtra" style="cursor: not-allowed;" readonly>
                    </div>
                    <div class="col-2 mt-4  ">
                        <label for="">City <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" class="form-control font-weight-bold" name="student_city" id="student_city" placeholder="City">
                    </div>


                    <div class="col-3 mt-4  ">
                        <label for="">Image </label>
                        <input type="file" onchange="showImage();" class="form-control" name="student_image" id="student_image">
                    </div>
                    <div class="col-3 mt-4 text-center">
                        <img src="<?= $base_url ?>assets/images/product/default.png" height="150" width="150" type="image/*" alt="Student Image" id="show_image">
                    </div>
                </div>
            </div>
            <div class="card-footer mt-2">
                <div class="d-flex p-2 justify-content-end">
                    <button name="student_save" type="submit" class="btn btn-primary shadow font-weight-bold"> <i class="fa fa-save "></i>&nbsp; Add Student</button>
                    &nbsp;
                    <button type="reset" class="btn btn-danger shadow font-weight-bold"> <i class="fas fa-times "></i>&nbsp; Clear</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function validation() {
        var student_code = document.getElementById("student_code");
        var student_name = document.getElementById("student_name");
        var student_type = document.getElementById("student_type");
        var student_total = document.getElementById("student_total");
        if (student_code.value == "") {
            student_code.focus();
            event.preventDefault();
        } else if (student_name.value == "") {
            student_name.focus();
            event.preventDefault();
        } else if (student_type.value == "") {
            student_type.focus();
            event.preventDefault();
        } else if (student_total.value == "") {
            student_total.focus();
            event.preventDefault();
        }
    }


    function showImage() {
        const image = document.getElementById('show_image');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                image.src = e.target.result;
            };

            reader.readAsDataURL(file);
        }
    }
</script>
<?php
include "../component/footer.php";
?>