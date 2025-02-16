<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Fetch the exam details by ID
if (isset($_GET['exam_id'])) {
    $exam_id = $_GET['exam_id'];
    $examQuery = "SELECT * FROM tbl_exam WHERE exam_id = '$exam_id'";
    $examResult = mysqli_query($conn, $examQuery);

    if (mysqli_num_rows($examResult) > 0) {
        $exam = mysqli_fetch_assoc($examResult);
    } else {
        $_SESSION['error'] = "Exam not found!";
        header('Location: index.php');
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid exam ID!";
    header('Location: index.php');
    exit;
}

// Handle the form submission
if (isset($_POST['exam_update'])) {
    $exam_title = mysqli_real_escape_string($conn, $_POST['exam_title']);
    $exam_description = mysqli_real_escape_string($conn, $_POST['exam_description']);
    $exam_start_date = mysqli_real_escape_string($conn, $_POST['exam_start_date']);
    $exam_end_date = mysqli_real_escape_string($conn, $_POST['exam_end_date']);
    $exam_status = mysqli_real_escape_string($conn, $_POST['exam_status']);
    $exam_department_id = mysqli_real_escape_string($conn, $_POST['exam_department_id']);
    $exam_course_id = mysqli_real_escape_string($conn, $_POST['exam_course_id']);

    // Validate the required fields
    if (empty($exam_title) || empty($exam_start_date) || empty($exam_end_date) || empty($exam_department_id) || empty($exam_course_id)) {
        $_SESSION['error'] = "All required fields must be filled!";
    } else {
        // Update the exam record
        $updateQuery = "UPDATE tbl_exam 
                        SET exam_title = '$exam_title', exam_description = '$exam_description', 
                            exam_start_date = '$exam_start_date', exam_end_date = '$exam_end_date', 
                            exam_status = '$exam_status', exam_department_id = '$exam_department_id', 
                            exam_course_id = '$exam_course_id' 
                        WHERE exam_id = '$exam_id'";

        if (mysqli_query($conn, $updateQuery)) {
            $_SESSION['success'] = "Exam updated successfully!";
            echo "<script>window.location = 'index.php';</script>";
        } else {
            $_SESSION['error'] = "Error in updating exam: " . mysqli_error($conn);
        }
    }
}

// Fetch departments
$departmentQuery = "SELECT * FROM tbl_department";
$departmentResult = mysqli_query($conn, $departmentQuery);

// Fetch courses based on the current department selection
$courseQuery = "SELECT * FROM tbl_course WHERE course_department_id = '" . $exam['exam_department_id'] . "'";
$courseResult = mysqli_query($conn, $courseQuery);
?>

<div class="content-wrapper p-2">
    <form action="" method="post">
        <div class="card">
            <div class="card-header">
                <div class="d-flex p-2 justify-content-between">
                    <div class="h5 font-weight-bold">Edit Exam</div>
                    <a href="index.php" class="btn btn-info shadow font-weight-bold">
                        <i class="fa fa-eye"></i>&nbsp; Exams List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION["success"])): ?>
                    <div class="alert alert-success">
                        <?= $_SESSION["success"]; unset($_SESSION["success"]); ?>
                    </div>
                <?php elseif (isset($_SESSION["error"])): ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION["error"]; unset($_SESSION["error"]); ?>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Exam Title -->
                    <div class="col-4">
                        <label for="exam_title">Exam Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control font-weight-bold" name="exam_title" id="exam_title" value="<?= $exam['exam_title']; ?>" required>
                    </div>
                    <div class="col-4 ">
                        <label for="exam_department_id">Department <span class="text-danger">*</span></label>
                        <?php
                        if(isset($_SESSION['department_id'])){
                       ?>
                            <select class="form-control font-weight-bold" name="exam_department_id" id="exam_department_id" disabled>
                       <?php
                        }else{
                       ?>
                        <select class="form-control font-weight-bold" name="exam_department_id" id="exam_department_id" required>
                       <?php
                        }
                       ?>
                            <option value="">Select Department</option>
                            <?php while ($department = mysqli_fetch_assoc($departmentResult)): ?>
                                <option value="<?= $department['department_id']; ?>" <?= ($exam['exam_department_id'] == $department['department_id']) ? 'selected' : ''; ?>>
                                    <?= $department['department_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Course Selection -->
                    <div class="col-4  ">
                        <label for="exam_course_id">Course <span class="text-danger">*</span></label>
                        <select class="form-control font-weight-bold" name="exam_course_id" id="exam_course_id" required>
                            <option value="">Select Course</option>
                            <?php while ($course = mysqli_fetch_assoc($courseResult)): ?>
                                <option value="<?= $course['course_id']; ?>" <?= ($exam['exam_course_id'] == $course['course_id']) ? 'selected' : ''; ?>>
                                    <?= $course['course_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div> 
                    <!-- Start Date -->
                    <div class="col-4 mt-3">
                        <label for="exam_start_date">Start Date <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control font-weight-bold" name="exam_start_date" id="exam_start_date" value="<?= date('Y-m-d\TH:i', strtotime($exam['exam_start_date'])); ?>" required>
                    </div>

                    <!-- End Date -->
                    <div class="col-4 mt-3">
                        <label for="exam_end_date">End Date <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control font-weight-bold" name="exam_end_date" id="exam_end_date" value="<?= date('Y-m-d\TH:i', strtotime($exam['exam_end_date'])); ?>" required>
                    </div>

                    <!-- Exam Status -->
                    <div class="col-4 mt-3">
                        <label for="exam_status">Status <span class="text-danger">*</span></label>
                        <select class="form-control font-weight-bold" name="exam_status" id="exam_status" required>
                            <option value="Scheduled" <?= ($exam['exam_status'] == 'Scheduled') ? 'selected' : ''; ?>>Scheduled</option>
                            <option value="Completed" <?= ($exam['exam_status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                        </select>
                    </div>

                    <!-- Department Selection -->
                   
                    <div class="col-12">
                        <label for="exam_description">Exam Description</label>
                        <textarea class="form-control font-weight-bold" name="exam_description" id="exam_description"><?= $exam['exam_description']; ?></textarea>
                    </div>

                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex p-2 justify-content-end">
                    <button name="exam_update" type="submit" class="btn btn-primary shadow font-weight-bold">
                        <i class="fa fa-save"></i>&nbsp; Update Exam
                    </button>
                    &nbsp;
                    <a href="index.php" class="btn btn-danger shadow font-weight-bold">
                        <i class="fas fa-times"></i>&nbsp; Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- AJAX Script to Fetch Courses Based on Department Selection -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Trigger change event when the department is selected
        $('#exam_department_id').change(function() {
            var departmentId = $(this).val();

            // Make an AJAX request to fetch courses
            $.ajax({
                url: 'fetch_courses.php',
                type: 'GET',
                data: { department_id: departmentId },
                success: function(response) {
                    $('#exam_course_id').html(response);
                }
            });
        });
    });
</script>

<?php
include "../component/footer.php";
?>
