<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

if (!isset($_GET["student_id"])) {
    echo "<script>alert('Invalid Request'); window.location.href='index.php';</script>";
    exit();
}

$student_id = mysqli_real_escape_string($conn, $_GET["student_id"]);
$query = "SELECT * FROM `tbl_students` s 
          INNER JOIN `tbl_course` c ON c.course_id = s.student_course
          WHERE s.student_id = '$student_id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_array($result);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = mysqli_real_escape_string($conn, $_POST["course_id"]);
    $semester = mysqli_real_escape_string($conn, $_POST["semester"]);
    $result_description = mysqli_real_escape_string($conn, $_POST["result_description"]);
    $percentage = mysqli_real_escape_string($conn, $_POST["percentage"]);

    // File Upload Handling
    if (!empty($_FILES["result_file"]["name"])) {
        $fileName = time() . "_" . basename($_FILES["result_file"]["name"]);
        $targetDir = "../uploads/results/";
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["result_file"]["tmp_name"], $targetFile)) {
            $query = "INSERT INTO tbl_results (student_id, course_id, semester, result_description, percentage, result_file) 
                      VALUES ('$student_id', '$course_id', '$semester', '$result_description', '$percentage', '$fileName')";
            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Result Uploaded Successfully!'); window.location.href='view.php?student_id=$student_id';</script>";
            } else {
                echo "<script>alert('Database Error!');</script>";
            }
        } else {
            echo "<script>alert('File Upload Failed!');</script>";
        }
    } else {
        echo "<script>alert('Please Select a File!');</script>";
    }
}
?>

<div class="content-wrapper p-3">
    <div class="card">
        <div class="card-header text-center">
            <h3>Upload Semester-Wise Result</h3>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>Course</label>
                    <input type="text" value="<?= $data["course_name"] ?>" class="form-control font-weight-bold" readonly>
                    <input type="hidden" name="course_id" value="<?= $data["student_course"] ?>">
                </div>
                <div class="mb-3">
                    <label>Semester</label>
                    <select name="semester" class="form-control font-weight-bold" required>
                        <option value="">Select Semester</option>
                        <?php for ($i = 1; $i <= $data["course_duration"]; $i++) { // Assuming max 8 semesters 
                        ?>
                            <option value="<?= $i ?>">Semester <?= $i ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Percentage (%)</label>
                    <input placeholder="Percentage" type="number" step="0.01" name="percentage" class="form-control font-weight-bold" required>
                </div>
                <div class="mb-3">
                    <label>Result Description</label>
                    <textarea placeholder="Result Description" name="result_description" class="form-control font-weight-bold" required></textarea>
                </div>
                <div class="mb-3">
                    <label>Upload Result File (PDF, DOCX, or Image)</label>
                    <input type="file" name="result_file" class="form-control font-weight-bold" accept=".pdf,.docx,.jpg,.png,.jpeg" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success">Upload Result</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "../component/footer.php"; ?>