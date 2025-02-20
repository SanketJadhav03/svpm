<?php
include "../config/connection.php"; // Database connection
include "../component/header.php";
include "../component/sidebar.php";

$faculty_id = $_SESSION['faculty_id']; // Assuming faculty is logged in

$query = "SELECT a.*, c.course_name, s.subject_name, s.subject_for
          FROM tbl_assignments a
          JOIN tbl_course c ON a.course_id = c.course_id
          JOIN tbl_subjects s ON a.subject_id = s.subject_id
          WHERE a.faculty_id = ?
          ORDER BY c.course_name, s.subject_for, s.subject_name";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$result = $stmt->get_result();

$assignments_by_course = [];

while ($row = $result->fetch_assoc()) {
    $course_name = $row['course_name'];
    $subject_for = $row['subject_for'];
    $assignments_by_course[$course_name][$subject_for][] = $row;
}

$stmt->close();
$conn->close();
?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="card p-3">
            <h2>View Assignments</h2>
            <?php if (!empty($assignments_by_course)): ?>
                <?php foreach ($assignments_by_course as $course_name => $subject_fors): ?>
                    <h3><?php echo $course_name; ?></h3>
                    <?php foreach ($subject_fors as $subject_for => $assignments): ?>
                        <h4> <?php echo $subject_for; ?></h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assignments as $assignment): ?>
                                    <tr>
                                        <td><?php echo $assignment['subject_name']; ?></td>
                                        <td><?php echo $assignment['assignment_title']; ?></td>
                                        <td><?php echo $assignment['assignment_description']; ?></td>
                                        <td>
                                            <?php if (!empty($assignment['assignment_file'])): ?>
                                                <a href="../uploads/assignments/<?php echo $assignment['assignment_file']; ?>" target="_blank">View</a>
                                            <?php else: ?>
                                                No File
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="delete_assignment.php?id=<?php echo $assignment['assignment_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No assignments found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include "../component/footer.php"; ?>
