<?php
include "../config/connection.php"; // Database connection
include "../component/header.php";
include "../component/sidebar.php";

$query = "SELECT a.*, c.course_id, c.course_name, s.subject_name, s.subject_for, 
                 ua.uploaded_status
          FROM tbl_assignments a
          JOIN tbl_course c ON a.course_id = c.course_id
          JOIN tbl_subjects s ON a.subject_id = s.subject_id 
          LEFT JOIN tbl_uploaded_assignments ua ON a.assignment_id = ua.assignment_id AND ua.student_id = ?
          WHERE c.course_id = ?
          ORDER BY c.course_name, s.subject_for, s.subject_name";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $_SESSION["student_id"], $_SESSION["student_course"]);
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
    <div class="container-fluid pt-4">
        <div class="card shadow-lg border-0 p-4 bg-light">
            <h2 class="text-center text-primary fw-bold mb-4">
                Assignments to Complete
            </h2>

            <?php if (!empty($assignments_by_course)): ?>
                <?php foreach ($assignments_by_course as $course_name => $subject_fors): ?>
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-header bg-gradient">
                            <h4 class="mb-0"><i class="fas fa-graduation-cap"></i> <?php echo $course_name; ?></h4>
                        </div>
                        <div class="card-body">
                            <?php $previous_done = true; // Allow first assignment 
                            ?>
                            <?php foreach ($subject_fors as $subject_for => $assignments): ?>
                                <h5 class="text-secondary fw-bold pb-2"><i class="fas fa-book"></i> <?php echo $subject_for; ?></h5>
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered align-middle">
                                        <thead>
                                            <tr>
                                                <th>Subject</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>File</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($assignments as $assignment): ?>
                                                <?php
                                                $status_text = "Incomplete";
                                                $btn_class = "btn-success";
                                                $btn_text = "Upload";
                                                $disabled = "";

                                                switch ($assignment['uploaded_status']) {
                                                    case 1:
                                                        $status_text = "Under Review";
                                                        $btn_class = "btn-warning";
                                                        $btn_text = "Under Review";
                                                        break;
                                                    case 2:
                                                        $status_text = "Done";
                                                        $btn_class = "btn-success";
                                                        $btn_text = "Completed";
                                                        break;
                                                    case 3:
                                                        $status_text = "Rejected";
                                                        $btn_class = "btn-danger";
                                                        $btn_text = "Re-Upload";
                                                        break;
                                                }

                                                if (!$previous_done) {
                                                    $disabled = "disabled";
                                                }
                                                ?>
                                                <tr class="bg-white">
                                                    <td class="fw-bold text-primary"><?php echo $assignment['subject_name']; ?></td>
                                                    <td><?php echo $assignment['assignment_title']; ?></td>
                                                    <td><?php echo $assignment['assignment_description']; ?></td>
                                                    <td>
                                                        <?php if (!empty($assignment['assignment_file'])): ?>
                                                            <a href="../uploads/assignments/<?php echo $assignment['assignment_file']; ?>"
                                                                class="btn btn-outline-primary btn-sm" target="_blank">
                                                                <i class="fas fa-file-alt"></i> View
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-muted">No File</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><span class="badge bg-info"> <?php echo $status_text; ?> </span></td>
                                                    <td>
                                                        <?php if ($assignment['uploaded_status'] != 1 && $assignment['uploaded_status'] != 2) { ?>
                                                            <a href="upload_assignment.php?id=<?php echo $assignment['assignment_id']; ?>"
                                                                class="btn <?php echo $btn_class; ?> btn-sm <?php echo $disabled; ?>">
                                                                <i class="fas fa-upload"></i> <?php echo $btn_text; ?>
                                                            </a>
                                                        <?php } else { ?>
                                                            <div
                                                                class="btn <?php echo $btn_class; ?> btn-sm <?php echo $disabled; ?>">
                                                                <?php
                                                                $iconClass = ($assignment['uploaded_status'] == 1) ? 'fas fa-hourglass-half' : 'fas fa-check-circle';
                                                                ?>
                                                                <i class="<?= $iconClass; ?>"> <?php echo $btn_text; ?> </i>

                                                            </div>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                $previous_done = ($assignment['uploaded_status'] == 2); // Allow next only if previous is Done
                                                ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-muted">No assignments found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include "../component/footer.php"; ?>