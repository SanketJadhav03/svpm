<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";
?>
<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <div class="text-center p-3">
                <h3 class="font-weight-bold">Subject Management</h3>
            </div>
            <form action="" method="GET">
                <div class="row justify-content-end">
                    <div class="col-2 font-weight-bold">
                        Subject Name
                        <input type="text" name="subject_name" value="<?= isset($_GET["subject_name"]) ? $_GET["subject_name"] : "" ?>" class="form-control font-weight-bold" placeholder="Subject Name">
                    </div>
                    <div class="col-2 font-weight-bold">
                        Course
                        <select name="course_id" class="form-control font-weight-bold">
                            <option value="">Select Course</option>
                            <?php
                            // Fetch courses from database to populate the dropdown
                            $courseQuery = "SELECT * FROM tbl_course";
                            $courseResult = mysqli_query($conn, $courseQuery);
                            while ($course = mysqli_fetch_array($courseResult)) {
                                $selected = (isset($_GET["course_id"]) && $_GET["course_id"] == $course["course_id"]) ? "selected" : "";
                                echo "<option value='" . $course["course_id"] . "' $selected>" . $course["course_name"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-1 font-weight-bold">
                        <br>
                        <button type="submit" class="shadow btn w-100 btn-info font-weight-bold"> <i class="fas fa-search"></i> &nbsp;Find</button>
                    </div>
                    <div class="col-1 font-weight-bold">
                        <br>
                        <button type="button" class="shadow btn w-100 font-weight-bold btn-primary" id="download-excel"><i class="fas fa-file-excel"></i> &nbsp; Excel</button>
                    </div>
                    <div class="col-1 font-weight-bold">
                        <br>
                        <button type="button" class="shadow btn w-100 font-weight-bold btn-danger" id="download-pdf"><i class="fas fa-file-pdf"></i> &nbsp; PDF</button>
                    </div>
                    <div class="col-1 font-weight-bold">
                        <br>
                        <button type="button" class="shadow btn w-100 font-weight-bold btn-secondary" id="print-page"><i class="fa fa-print"></i> &nbsp;Print</button>
                    </div>
                    <div class="col-2 text-right font-weight-bold">
                        <br>
                        <a href="create.php" class="font-weight-bold w-100 shadow btn btn-success"> <i class="fas fa-plus"></i>&nbsp; Add Subject</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <?php
            if (isset($_SESSION["success"])) {
            ?>
                <div class="font-weight-bold alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5 class="font-weight-bold "><i class="icon fas fa-check"></i> Success!</h5>
                    <?= $_SESSION["success"] ?>
                </div>
            <?php
                unset($_SESSION["success"]);
            }
            ?>

            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>Department</th>
                        <th>Course</th>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Subject For</th>
                        <th>Subject Type</th>
                        <th>Theory Marks</th>
                        <th>Practical Marks</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $count = 0;
                    $limit = 10;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;

                    // Initialize $whereClause
                    $whereClause = " WHERE 1=1";

                    // Department filter for specific roles
                    if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == 4 && isset($_SESSION["department_id"])) {
                        $department_id = $_SESSION["department_id"];
                        $whereClause .= " AND tbl_course.course_department_id = $department_id";
                    }

                    // Course filter
                    if (isset($_GET["course_id"]) && $_GET["course_id"] != "") {
                        $course_id = $_GET["course_id"];
                        $whereClause .= " AND tbl_subjects.subject_course = $course_id";
                    }

                    // Subject name filter
                    if (isset($_GET["subject_name"]) && $_GET["subject_name"] != "") {
                        $subject_name = mysqli_real_escape_string($conn, $_GET["subject_name"]);
                        $whereClause .= " AND tbl_subjects.subject_name LIKE '%$subject_name%'";
                    }

                    // Count query
                    $countQuery = "SELECT COUNT(*) as total FROM `tbl_subjects`
                                   INNER JOIN `tbl_course` ON tbl_course.course_id = tbl_subjects.subject_course
                                   LEFT JOIN tbl_department ON tbl_course.course_department_id = tbl_department.department_id $whereClause";
                    $countResult = mysqli_query($conn, $countQuery);
                    $totalRecords = mysqli_fetch_assoc($countResult)['total'];
                    $totalPages = ceil($totalRecords / $limit);

                    // Select query
                    $selectQuery = "SELECT * FROM `tbl_subjects`
                                    INNER JOIN `tbl_course` ON tbl_course.course_id = tbl_subjects.subject_course
                                    LEFT JOIN tbl_department ON tbl_course.course_department_id = tbl_department.department_id
                                    $whereClause ORDER BY course_name LIMIT $limit OFFSET $offset";
                    $result = mysqli_query($conn, $selectQuery);

                    // Display data
                    while ($data = mysqli_fetch_array($result)) {
                    ?>
                        <tr>
                            <td><?= ++$count ?></td>
                            <td><?= $data["department_name"] ?></td>
                            <td><?= $data["course_name"] ?></td>
                            <td><?= $data["subject_code"] ?></td>
                            <td><?= $data["subject_name"] ?></td>
                            <td><?= $data["subject_for"] ?></td>
                            <td><?= $data["subject_type"] == 1 ? "Core" : "Optional" ?></td>
                            <td><?= $data["subject_theory"] ?></td>
                            <td><?= $data["subject_practical"] ?></td>
                            <td>
                                <a href="edit.php?subject_id=<?= $data["subject_id"] ?>" class="btn mb-1 btn-sm shadow btn-info">
                                    <i class="fa fa-pen"></i>
                                </a>
                                <a href="delete.php?subject_id=<?= $data["subject_id"] ?>" onclick="return confirm('Are you sure want to delete this subject?');" class="btn btn-sm shadow btn-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
                    if ($count == 0) {
                    ?>
                        <tr>
                            <td colspan="10" class="font-weight-bold text-center">
                                <span class="text-danger">Subjects Not Found.</span>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-center">
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a class="btn btn-sm btn-outline-info ml-2" href="?page=<?= $page - 1 ?>&subject_name=<?= isset($_GET["subject_name"]) ? $_GET["subject_name"] : "" ?>&course_id=<?= isset($_GET["course_id"]) ? $_GET["course_id"] : "" ?>">Previous</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a class="btn btn-sm <?= $page == $i ? "btn-info" : "btn-outline-info" ?> ml-2 shadow" href="?page=<?= $i ?>&subject_name=<?= isset($_GET["subject_name"]) ? $_GET["subject_name"] : "" ?>&course_id=<?= isset($_GET["course_id"]) ? $_GET["course_id"] : "" ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a class="btn btn-sm btn-outline-info ml-2" href="?page=<?= $page + 1 ?>&subject_name=<?= isset($_GET["subject_name"]) ? $_GET["subject_name"] : "" ?>&course_id=<?= isset($_GET["course_id"]) ? $_GET["course_id"] : "" ?>">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('download-pdf').addEventListener('click', function() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const startX = 14;
    const startY = 30;
    const lineSpacing = 10;

    doc.setFontSize(16);
    doc.text('Subject Management Report', 14, 16);

    doc.setFontSize(12);
    doc.text('#', startX, startY);
    doc.text('Subject Code', startX + 10, startY);
    doc.text('Subject Name', startX + 50, startY);
    doc.text('Course', startX + 100, startY);
    doc.text('Semester', startX + 160, startY);

    fetch('download-subjects.php')
        .then(response => response.json())
        .then(data => {
            let y = startY + lineSpacing;
            data.forEach((subject, index) => {
                doc.text((index + 1).toString(), startX, y);
                doc.text(subject.subject_code, startX + 10, y);
                doc.text(doc.splitTextToSize(subject.subject_name, 40), startX + 50, y);
                doc.text(subject.course_name, startX + 100, y);
                doc.text(subject.subject_for, startX + 160, y);
                y += lineSpacing;
            });

            if (data.length === 0) {
                doc.text('No Subjects Found', 14, y);
            }

            doc.save('subjects_report.pdf');
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            alert('Failed to generate PDF. Please try again.');
        });
});

document.getElementById('download-excel').addEventListener('click', function() {
    fetch('download-subjects.php')
        .then(response => response.json())
        .then(data => {
            const processedData = data.map((subject, index) => ({
                '#': index + 1,
                'Subject Code': subject.subject_code,
                'Subject Name': subject.subject_name,
                'Course': subject.course_name,
                'Semester': subject.subject_for,
            }));

            const ws = XLSX.utils.json_to_sheet(processedData);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Subjects');
            XLSX.writeFile(wb, 'subjects_report.xlsx');
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            alert('Failed to generate Excel. Please try again.');
        });
});


    // Fetch data for print
    const fetchSubjectData = async () => {
        try {
            const response = await fetch('download-subjects.php');
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error fetching data:', error);
            return [];
        }
    };

    const printSubjectData = async () => {
        const data = await fetchSubjectData();
        let printContents = '<table class="table">';
        printContents += '<thead><tr><th>#</th><th>Subject Code</th><th>Subject Name</th><th>Course</th><th>Semester</th></tr></thead>';
        printContents += '<tbody>';

        data.forEach((subject, index) => {
            printContents += `<tr>
                                <td>${index + 1}</td>
                                <td>${subject.subject_code}</td>
                                <td>${subject.subject_name}</td>
                                <td>${subject.course_name}</td>
                                <td>${subject.subject_for}</td>
                              </tr>`;
        });

        printContents += '</tbody></table>';
        const originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        window.location.reload();
        document.body.innerHTML = originalContents;
    };

    document.getElementById('print-page').addEventListener('click', function() {
        printSubjectData();
    });
</script>

<?php
include "../component/footer.php";
?>
