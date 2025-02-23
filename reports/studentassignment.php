<?php
include "../config/connection.php"; // Database connection
include "../component/header.php";
include "../component/sidebar.php"; 

// Fetch students for dropdown
$studentQuery = "SELECT * FROM tbl_students INNER JOIN tbl_course ON tbl_stuednts.student_course_id = tbl_course.course_id";
$studentResult = $conn->query($studentQuery);

// Handle selected student
$selected_student = isset($_GET['student_id']) ? $_GET['student_id'] : '';
?>

<div class="content-wrapper">
    <div class="container-fluid pt-4">
        <div class="card shadow-lg border-0 p-4 bg-light">
            <h2 class="text-center text-primary fw-bold mb-4">
                View Assignments by Student
            </h2>

            <!-- Student Selection Form -->
            <form method="GET">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Select Student:</label>
                    <div class="col-sm-6">
                        <select class="form-control" name="student_id" onchange="this.form.submit()">
                            <option value="">-- Select Student --</option>
                            <?php while ($student = $studentResult->fetch_assoc()): ?>
                                <option value="<?php echo $student['student_id']; ?>" 
                                    <?php echo ($selected_student == $student['student_id']) ? 'selected' : ''; ?>>
                                    <?php echo $student['student_first_name']." ".$student['student_last_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
            </form>

            <?php
            if (!empty($selected_student)) {
                // Fetch assignments based on selected student
                $query = "SELECT a.*, c.course_name, s.subject_name, s.subject_for, 
                                ua.uploaded_status
                        FROM tbl_assignments a
                        JOIN tbl_course c ON a.course_id = c.course_id
                        JOIN tbl_subjects s ON a.subject_id = s.subject_id 
                        LEFT JOIN tbl_uploaded_assignments ua 
                            ON a.assignment_id = ua.assignment_id AND ua.student_id = ?
                        WHERE c.course_id = (SELECT student_course FROM tbl_students WHERE student_id = ?)
                        ORDER BY c.course_name, s.subject_for, s.subject_name";

                $stmt = $conn->prepare($query);
                $stmt->bind_param("ii", $selected_student, $selected_student);
                $stmt->execute();
                $result = $stmt->get_result();

                $assignments_by_course = [];
                while ($row = $result->fetch_assoc()) {
                    $course_name = $row['course_name'];
                    $subject_for = $row['subject_for'];
                    $assignments_by_course[$course_name][$subject_for][] = $row;
                }

                $stmt->close();
            }
            ?>

            <?php if (!empty($assignments_by_course)): ?>
                <?php foreach ($assignments_by_course as $course_name => $subject_fors): ?>
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-header bg-gradient">
                            <h4 class="mb-0"><i class="fas fa-graduation-cap"></i> <?php echo $course_name; ?></h4>
                        </div>
                        <div class="card-body">
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($assignments as $assignment): ?>
                                                <?php
                                                $status_text = "Incomplete";
                                                switch ($assignment['uploaded_status']) {
                                                    case 1: $status_text = "Under Review"; break;
                                                    case 2: $status_text = "Done"; break;
                                                    case 3: $status_text = "Rejected"; break;
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
                                                    <td><span class="badge bg-info"><?php echo $status_text; ?></span></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php elseif (!empty($selected_student)): ?>
                <p class="text-center text-muted">No assignments found for this student.</p>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include "../component/footer.php"; ?>
