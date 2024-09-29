<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Get the student ID from the URL
$student_id = $_GET['student_id'];

// Fetch the student details from the database
$query = "SELECT * FROM tbl_students INNER JOIN tbl_courses ON tbl_students.student_course = tbl_courses.course_id  WHERE student_id = $student_id";
$student = mysqli_fetch_array(mysqli_query($conn, $query));

// Check if the form is submitted
if (isset($_POST["student_update"])) {
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
    $student_type = $_POST["student_type"];
    $student_dob = $_POST["student_dob"];
    $student_gender = $_POST["student_gender"];

    // Handle file upload
    if (isset($_FILES["student_image"]["name"]) && $_FILES["student_image"]["name"] != "") {
        $target_dir = "../assets/images/student/";
        $imageFileName = basename($_FILES["student_image"]["name"]);
        $target_file = $target_dir . $imageFileName;
        move_uploaded_file($_FILES["student_image"]["tmp_name"], $target_file);
    } else {
        $imageFileName = $student['student_image']; // Use existing image if no new image is uploaded
    }

    // Update the student record
    $updateQuery = "UPDATE tbl_students 
                    SET student_first_name = '$student_first_name', student_last_name = '$student_last_name',
                        student_email = '$student_email', student_contact = '$student_contact', 
                        student_state = '$student_state', student_city = '$student_city',
                        student_mother_name = '$student_mother_name', student_father_name = '$student_father_name',
                        student_mother_occupation = '$student_mother_occupation', student_father_occupation = '$student_father_occupation',
                        student_course = '$student_course', student_type = '$student_type',
                        student_dob = '$student_dob', student_image = '$imageFileName', student_gender = '$student_gender'
                    WHERE student_id = $student_id";

    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION["success"] = "Student Updated Successfully!";
        echo "<script>window.location = 'index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<div class="content-wrapper p-2">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="card ">
            <div class="card-header ">
                <div class="d-flex p-2 justify-content-between">
                    <div class="h5 font-weight-bold">Edit Student</div>
                    <a href="index.php" class="btn btn-info shadow font-weight-bold"> <i class="fa fa-eye"></i>&nbsp; Students List</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <label for="">Roll No <span class="text-danger font-weight-bold"> *</span></label>
                        <input value="<?= $student['student_roll'] ?>" type="text" readonly class="form-control font-weight-bold" name="student_roll" id="student_roll">
                    </div>
                    <div class="col-3">
                        <label for="">Course <span class="text-danger font-weight-bold"> *</span></label>
                        <select name="student_course" id="student_course" class="form-control font-weight-bold">
                            <option value="">Select Course</option>
                            <?php
                            $allcourse = "SELECT * FROM tbl_courses";
                            $courseQuery = mysqli_query($conn, $allcourse);
                            while ($course = mysqli_fetch_array($courseQuery)) {
                                $selected = $course['course_id'] == $student['student_course'] ? 'selected' : '';
                                ?>
                                <option value="<?= $course['course_id'] ?>" data-course-duration="<?= $course['course_total'] ?>" 
                                    data-course-type="<?= $course['course_type'] ?>" data-course-fees="<?= $course['course_fees'] ?>" <?= $selected ?>>
                                    <?= $course["course_name"] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-2">
                        <label for="">Course Duration <span class="text-danger font-weight-bold"> *</span></label>
                        <input value="<?= $student['course_total']." ".($student['course_type'] == 1 ? "Semester":"Year") ?>" readonly style="cursor: not-allowed;" type="text" class="form-control font-weight-bold" name="student_duration" id="student_duration" placeholder="Course Duration">
                    </div>

                    <div class="col-2">
                        <label for="">Course Fees <span class="text-danger font-weight-bold"> *</span></label>
                        <input value="<?= $student['course_fees'] ?>" readonly style="cursor: not-allowed;" type="text" class="form-control font-weight-bold" name="student_fees" id="student_fees" placeholder="Course Fees">
                    </div>

                    <div class="col-2">
                        <label for="">Student Type <span class="text-danger font-weight-bold"> *</span></label>
                        <select name="student_type" id="student_type" class="form-control font-weight-bold">
                            <option value="">Select Type</option>
                            <option value="Regular" <?= $student['student_type'] == 'Regular' ? 'selected' : '' ?>>Regular</option>
                            <option value="External" <?= $student['student_type'] == 'External' ? 'selected' : '' ?>>External</option>
                        </select>
                    </div>

                    <div class="col-3 mt-4  ">
                        <label for="">First Name <span class="text-danger font-weight-bold"> *</span></label>
                        <input value="<?= $student['student_first_name'] ?>" type="text" class="form-control font-weight-bold" name="student_first_name" id="student_first_name" placeholder="First Name">
                    </div>
                    <div class="col-3 mt-4  ">
                        <label for="">Last Name <span class="text-danger font-weight-bold"> *</span></label>
                        <input value="<?= $student['student_last_name'] ?>" type="text" class="form-control font-weight-bold" name="student_last_name" id="student_last_name" placeholder="Last Name">
                    </div>
                    <div class="col-3 mt-4  ">
                        <label for="">Email <span class="text-danger font-weight-bold"> *</span></label>
                        <input value="<?= $student['student_email'] ?>" type="email" class="form-control font-weight-bold" name="student_email" id="student_email" placeholder="Email Address">
                    </div>
                    <div class="col-3 mt-4  ">
                        <label for="">Contact Number <span class="text-danger font-weight-bold"> *</span></label>
                        <input value="<?= $student['student_contact'] ?>" type="text" class="form-control font-weight-bold" name="student_contact" id="student_contact" placeholder="Contact Number">
                    </div>

                    <div class="col-3 mt-4  ">
                        <label for="">Mother's Name <span class="text-danger font-weight-bold"> *</span></label>
                        <input value="<?= $student['student_mother_name'] ?>" type="text" class="form-control font-weight-bold" name="student_mother_name" id="student_mother_name" placeholder="Mother Name">
                    </div>
                    <div class="col-3 mt-4  ">
                        <label for="">Mother's Occupation <span class="text-danger font-weight-bold"> *</span></label>
                        <input value="<?= $student['student_mother_occupation'] ?>" type="text" class="form-control font-weight-bold" name="student_mother_occupation" id="student_mother_occupation" placeholder="Mother Occupation">
                    </div>
                    <div class="col-3 mt-4  ">
                        <label for="">Father's Name <span class="text-danger font-weight-bold"> *</span></label>
                        <input value="<?= $student['student_father_name'] ?>" type="text" class="form-control font-weight-bold" name="student_father_name" id="student_father_name" placeholder="Father Name">
                    </div>
                    <div class="col-3 mt-4  ">
                        <label for="">Father's Occupation <span class="text-danger font-weight-bold"> *</span></label>
                        <input value="<?= $student['student_father_occupation'] ?>" type="text" class="form-control font-weight-bold" name="student_father_occupation" id="student_father_occupation" placeholder="Father Occupation">
                    </div>

                    <div class="col-3 mt-4  ">
                        <label for="">State <span class="text-danger font-weight-bold"> *</span></label>
                        <input value="<?= $student['student_state'] ?>" type="text" class="form-control font-weight-bold" name="student_state" id="student_state" placeholder="State">
                    </div>
                    <div class="col-3 mt-4  ">
                        <label for="">City <span class="text-danger font-weight-bold"> *</span></label>
                        <input value="<?= $student['student_city'] ?>" type="text" class="form-control font-weight-bold" name="student_city" id="student_city" placeholder="City">
                    </div>

                    <div class="col-3 mt-4  ">
                        <label for="">Date of Birth <span class="text-danger font-weight-bold"> *</span></label>
                        <input value="<?= $student['student_dob'] ?>" type="date" class="form-control font-weight-bold" name="student_dob" id="student_dob">
                    </div>
                    <div class="col-3 mt-4  ">
                        <label for="">Gender <span class="text-danger font-weight-bold"> *</span></label>
                        <select name="student_gender" id="student_gender" class="form-control font-weight-bold">
                            <option value="Male" <?= $student['student_gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $student['student_gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                            <option value="Other" <?= $student['student_gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>

                    <!-- Image Upload -->
                    <div class="col-3 mt-4">
                        <label for="">Image</label>
                        <input type="file" class="form-control" name="student_image" id="student_image" onchange="showImage();">
                    </div>
                    <div class="col-3 mt-4 text-center">
                        <img src="<?= $base_url ?>assets/images/student/<?= $student['student_image'] ?>" height="150" width="150" id="show_image" alt="Student Image">
                    </div>
                </div>
            </div>
            <div class="card-footer mt-2">
                <div class="d-flex p-2 justify-content-end">
                    <button name="student_update" type="submit" class="btn btn-primary shadow font-weight-bold"> <i class="fa fa-save "></i>&nbsp; Update Student</button>
                    &nbsp;
                    <button type="reset" class="btn btn-danger shadow font-weight-bold"> <i class="fas fa-times "></i>&nbsp; Clear</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
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
    $(document).ready(function() {
        $('#student_course').change(function() {
            var selectedCourse = $(this).find('option:selected'); // Get the selected option
            var courseDuration = selectedCourse.data('course-duration'); // Get course duration from data attribute
            var courseFees = selectedCourse.data('course-fees'); // Get course fees from data attribute
            var courseType = selectedCourse.data('course-type'); // Get course fees from data attribute
            // Set the values of the inputs
            $('#student_duration').val(courseDuration ? courseDuration + (courseType == 1? " Semester":" Year") : 'N/A');
            $('#student_fees').val(courseFees ? courseFees : 'N/A');
        });
    });
</script>

<?php
include "../component/footer.php";
?>
