<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";
?>
<div class="content-wrapper p-2">
    <div class="card ">
        <div class="card-header">
            <div class="text-center p-3">
                <h3 class="font-weight-bold">Student Management</h3>
            </div>
            <form action="">
                <div class="row justify-content-end">

                    <div class="col-2 font-weight-bold">
                        Student Name
                        <input type="text" name="student_name" value="<?= isset($_GET["student_name"]) ? $_GET["student_name"] : "" ?>" class="form-control font-weight-bold" placeholder="Student Name">
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
                        <a href="create.php" class="font-weight-bold  w-100 shadow btn  btn-success"> <i class="fas fa-plus"></i>&nbsp; Add Student</a>
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
                <table class="table ">
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Roll No</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Course</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $count = 0;
                    $limit = 10;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;
                    $departmentLogin = isset($_SESSION['department_id']) ?$_SESSION['department_id']:0;
                    if ($departmentLogin == 0) {
                        $countQuery = "SELECT COUNT(*) as total FROM `tbl_students` INNER JOIN `tbl_course` ON tbl_course.course_id = tbl_students.student_course";
                        $selectQuery = "SELECT * FROM `tbl_students` INNER JOIN `tbl_course` ON tbl_course.course_id = tbl_students.student_course LIMIT $limit OFFSET $offset";
                    } else {
                        $countQuery = "SELECT COUNT(*) as total FROM `tbl_students` INNER JOIN `tbl_course` ON tbl_course.course_id = tbl_students.student_course  WHERE course_department_id = $departmentLogin";
                        $selectQuery = "SELECT * FROM `tbl_students` INNER JOIN `tbl_course` ON tbl_course.course_id = tbl_students.student_course WHERE course_department_id = $departmentLogin LIMIT $limit OFFSET $offset";
                    }
                    if (isset($_GET["student_name"])) {
                        $student_name = $_GET["student_name"];
                        $student_name = mysqli_real_escape_string($conn, $student_name);
                        $countQuery = "SELECT COUNT(*) as total FROM `tbl_students` WHERE `student_first_name` LIKE '%$student_name%' OR `student_last_name` LIKE '%$student_name%'";
                        $selectQuery = "SELECT * FROM `tbl_students` INNER JOIN `tbl_course` ON tbl_course.course_id = tbl_students.student_course WHERE `student_first_name` LIKE '%$student_name%' OR `student_last_name` LIKE '%$student_name%' LIMIT $limit OFFSET $offset";
                    }
                    $countResult = mysqli_query($conn, $countQuery);
                    $totalRecords = mysqli_fetch_assoc($countResult)['total'];
                    $totalPages = ceil($totalRecords / $limit);
                    $result = mysqli_query($conn, $selectQuery);
                    while ($data = mysqli_fetch_array($result)) {
                    ?>
                        <tr>
                            <td><?= $count += 1 ?></td>
                            <td>
                                <img src="<?= $base_url ?>assets/images/student/<?= $data["student_image"] != "" ? $data["student_image"] : "default.png" ?>" height="100" width="100" alt="<?= $data["student_image"] ?>">
                            </td>
                            <td><?= $data["student_roll"] ?></td>
                            <td><?= $data["student_first_name"] . " " . $data["student_last_name"] ?></td>
                            <td><?= $data["student_email"] ?></td>
                            <td><?= $data["student_contact"] ?></td>
                            <td><?= $data["course_name"] ?></td>

                            <td>
                                <a href="view.php?student_id=<?= $data["student_id"] ?>" class="btn btn-sm shadow btn-info">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="edit.php?student_id=<?= $data["student_id"] ?>" class="btn btn-sm shadow btn-primary">
                                    <i class="fa fa-pen"></i>
                                </a>
                                <a href="delete.php?student_id=<?= $data["student_id"] ?>" onclick="if(confirm('Are you sure want to delete this student?')){return true}else{return false;}" class="btn btn-sm shadow btn-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                    <?php
                    if ($count == 0) {
                    ?>
                        <tr>
                            <td colspan="9" class="font-weight-bold text-center">
                                <span class="text-danger">Students Not Found.</span>
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
                        <a class="btn btn-sm btn-outline-info ml-2" href="?page=<?php echo $page - 1; ?>&student_name=<?php echo isset($student_name) ? $student_name : ''; ?>">Previous</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a class="btn btn-sm <?= $page == $i ? "btn-info" : "btn-outline-info" ?>  ml-2 shadow" href="?page=<?php echo $i; ?>&student_name=<?php echo isset($student_name) ? $student_name : ''; ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a class="btn btn-sm btn-outline-info ml-2" href="?page=<?php echo $page + 1; ?>&student_name=<?php echo isset($student_name) ? $student_name : ''; ?>">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    document.getElementById('download-pdf').addEventListener('click', function() {
        const {
            jsPDF
        } = window.jspdf;
        const doc = new jsPDF();

        // Title
        doc.setFontSize(16);
        doc.text('Student Management Report', 14, 16);

        // Table headers
        doc.setFontSize(12);
        const startX = 14;
        const startY = 30;
        const lineSpacing = 10;

        // Set column headers
        doc.text('#', startX, startY);
        doc.text('Roll No', startX + 10, startY);
        doc.text('Full Name', startX + 50, startY);
        doc.text('Email', startX + 100, startY);
        doc.text('Contact Number', startX + 130, startY);
        doc.text('Course', startX + 160, startY);
        doc.text('Type', startX + 190, startY);
        doc.text('DOB', startX + 220, startY);

        // Fetch data and populate PDF
        fetch('download-pdf.php')
            .then(response => response.json())
            .then(data => {
                let y = startY + lineSpacing; // Move below headers

                data.forEach((student, index) => {
                    doc.text((index + 1).toString(), startX, y); // Index column
                    doc.text(student.student_roll, startX + 10, y); // Roll No
                    doc.text(student.student_first_name + ' ' + student.student_last_name, startX + 50, y); // Full Name
                    doc.text(student.student_email, startX + 100, y); // Email
                    doc.text(student.student_contact, startX + 130, y); // Contact Number
                    doc.text(student.course_name, startX + 160, y); // Course
                    doc.text(student.student_type, startX + 190, y); // Type
                    doc.text(new Date(student.student_dob).toLocaleDateString(), startX + 220, y); // Date of Birth
                    y += lineSpacing;
                });

                if (data.length === 0) {
                    doc.text('No Students Found', 14, y);
                }

                // Save the PDF
                doc.save('students_report.pdf');
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    });

    document.getElementById('download-excel').addEventListener('click', function() {
        fetch('download-pdf.php')
            .then(response => response.json())
            .then(data => {
                // Create a new workbook and worksheet
                const ws = XLSX.utils.json_to_sheet(data.map((student, index) => ({
                    '#': index + 1,
                    'Roll No': student.student_roll,
                    'Full Name': student.student_first_name + ' ' + student.student_last_name,
                    'Email': student.student_email,
                    'Contact Number': student.student_contact,
                    'Course': student.course_name,
                    'Type': student.student_type,
                    'DOB': new Date(student.student_dob).toLocaleDateString(),
                })));

                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Students');

                // Save the workbook as an Excel file
                XLSX.writeFile(wb, 'students_report.xlsx');
            })
            .catch(error => console.error('Error fetching data:', error));
    });

    // Fetch data for print
    const fetchStudentData = async () => {
        try {
            const response = await fetch('download-pdf.php');
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error fetching data:', error);
            return [];
        }
    };

    const printStudentData = async () => {
        const data = await fetchStudentData();
        let printContents = '<table class="table">';
        printContents += '<thead><tr><th>#</th><th>Roll No</th><th>Full Name</th><th>Email</th><th>Contact Number</th><th>Course</th><th>Type</th><th>DOB</th></tr></thead>';
        printContents += '<tbody>';

        data.forEach((student, index) => {
            printContents += `<tr>
                            <td>${index + 1}</td>
                            <td>${student.student_roll}</td>
                            <td>${student.student_first_name} ${student.student_last_name}</td>
                            <td>${student.student_email}</td>
                            <td>${student.student_contact}</td>
                            <td>${student.course_name}</td>
                            <td>${student.student_type}</td>
                            <td>${new Date(student.student_dob).toLocaleDateString()}</td>
                          </tr>`;
        });

        printContents += '</tbody></table>';
        const originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    };
    document.getElementById('print-page').addEventListener('click', function() {
        printStudentData();
    });
</script>
<?php
include "../component/footer.php";
?>