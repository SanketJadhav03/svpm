<?php
include "../config/connection.php"; // Database connection
include "../component/header.php";
include "../component/sidebar.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $faculty_id = $_SESSION['faculty_id']; // Assuming faculty is logged in
    $course_id = $_POST['course_id'];
    $subject_id = $_POST['subject_id'];
    $assignment_title = $_POST['assignment_title'];
    $assignment_description = $_POST['assignment_description'];
    $upload_dir = "../uploads/assignments/"; // Directory to store files
    $assignment_file = null;

    // File upload handling
    if (!empty($_FILES["assignment_file"]["name"])) {
        $file_name = time() . "_" . basename($_FILES["assignment_file"]["name"]);
        $target_file = $upload_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_types = ["pdf", "doc", "docx", "ppt", "pptx"];

        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES["assignment_file"]["tmp_name"], $target_file)) {
                $assignment_file = $file_name;
            } else {
                echo "<script>alert('Error uploading file.');</script>";
            }
        } else {
            echo "<script>alert('Invalid file type. Allowed: PDF, DOC, DOCX, PPT, PPTX');</script>";
        }
    }

    // Insert assignment data into the database
    $sql = "INSERT INTO tbl_assignments (faculty_id, course_id, subject_id, assignment_title, assignment_description, assignment_file)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiisss", $faculty_id, $course_id, $subject_id, $assignment_title, $assignment_description, $assignment_file);

    if ($stmt->execute()) {
        echo "<script>alert('Assignment uploaded successfully!'); window.location='view_assignments.php';</script>";
    } else {
        echo "<script>alert('Error uploading assignment.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="card p-3">
            <h2>Upload Assignment</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Course</label>
                    <select name="course_id" id="course_id" class="form-control" required>
                        <option value="">Select Course</option>
                        <?php
                        $department_id = $_SESSION['department_id'];
                        $query = "SELECT * FROM tbl_course WHERE course_department_id = '$department_id'";
                        $result = $conn->query($query);
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['course_id']}'>{$row['course_name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Subject</label>
                    <select name="subject_id" id="subject_id" class="form-control" required>
                        <option value="">Select Subject</option>
                        <!-- Subjects will be loaded here based on selected course -->
                    </select>
                </div>

                <div class="form-group">
                    <label>Assignment Title</label>
                    <input type="text" name="assignment_title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Assignment Description</label>
                    <textarea name="assignment_description" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label>Upload Assignment File</label>
                    <input type="file" name="assignment_file" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Upload Assignment</button>
            </form>
        </div>
    </div>
</div>

<?php include "../component/footer.php"; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $("#course_id").change(function() {
        
        var course_id = $(this).val(); 
        if (course_id !== "") {
            $.ajax({
                url: "fetch_subjects.php",
                type: "POST",
                data: { course_id: course_id },
                success: function(data) { 
                    $("#subject_id").html(data);
                }
            });
        } else {
            $("#subject_id").html("<option value=''>Select Subject</option>");
        }
    });
});
</script>
