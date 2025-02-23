<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_SESSION['student_id'];
    $course_id = $_POST['course_id'];
    $assignment_id = $_POST['assignment_id'];
    $uploaded_description = $_POST['uploaded_description'];

    // File upload logic
    $target_dir = "../uploads/student_assignments/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = basename($_FILES["uploaded_file"]["name"]);
    $target_file = $target_dir . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Allowed file types
    $allowed_types = ["pdf", "doc", "docx", "png", "jpg"];
    if (!in_array($file_type, $allowed_types)) {
        echo "<script>alert('Invalid file type. Only PDF, DOC, DOCX, PNG, JPG allowed.');</script>";
        exit();
    }

    if (move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $target_file)) {
        // Insert into database
        $query = "INSERT INTO tbl_uploaded_assignments (student_id, course_id, assignment_id, uploaded_file, uploaded_description, uploaded_status) 
                  VALUES (?, ?, ?, ?, ?, 1)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiiss", $student_id, $course_id, $assignment_id, $file_name, $uploaded_description);

        if ($stmt->execute()) {
            echo "<script>alert('Assignment uploaded successfully!'); window.location.href='view_assignment.php';</script>";
        } else {
            echo "<script>alert('Error uploading assignment.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('File upload failed.');</script>";
    }
}

$conn->close();
?>
<div class="content-wrapper">
    <div class="container-fluid pt-2"> 
        <div class="card p-4 ">
            <form action="upload_assignment.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="course_id" value="<?php echo $_SESSION['student_course']; ?>">
                <input type="hidden" name="assignment_id" value="<?php echo $_GET['id']; ?>">

                <div class="form-group">
                    <label for="uploaded_description">Assignment Description:</label>
                    <textarea name="uploaded_description" class="form-control" required></textarea>
                </div>

                <div class="form-group">
                    <label for="uploaded_file">Upload File:</label>
                    <input type="file" name="uploaded_file" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success">Upload Assignment</button>
            </form>
        </div>
    </div>
</div>
<?php
include "../component/footer.php";
?>