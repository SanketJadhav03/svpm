<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";
?>
<div class="content-wrapper p-2">
    <div class="card ">
        <div class="card-header">
            <div class="text-center p-3">
                <h3 class="font-weight-bold">Subject Management</h3>
            </div>
            <form action="">
                <div class="row justify-content-end">

                    <div class="col-2 font-weight-bold">
                        Subject Name
                        <input type="text" name="subject_name" value="<?= isset($_GET["subject_name"]) ? $_GET["subject_name"] : "" ?>" class="form-control font-weight-bold" placeholder="Subject Name">
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
                        <a href="create.php" class="font-weight-bold  w-100 shadow btn  btn-success"> <i class="fas fa-plus"></i>&nbsp; Add Subject</a>
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
                        <th>Department</th>
                        <th>Course </th>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Subject For</th>
                        <th>Subject Type</th>
                        <th>Theory Marks</th>
                        <th>Practcal Marks</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $count = 0;
                    $limit = 10;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;
                    $countQuery = "SELECT COUNT(*) as total FROM `tbl_subjects` INNER JOIN `tbl_course` ON tbl_course.course_id = tbl_subjects.subject_course ";
                    $selectQuery = "SELECT * FROM `tbl_subjects` INNER JOIN `tbl_course` ON tbl_course.course_id = tbl_subjects.subject_course LEFT JOIN tbl_department ON tbl_course.course_department_id = tbl_department.department_id";
                    $whereClause = ""; // Initialize as empty
                   
                   // Check if the user role exists and is associated with a department
                   if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == 4 && isset($_SESSION["department_id"])) {
                       $department_id = $_SESSION["department_id"];
                       $whereClause = " WHERE tbl_course.course_department_id = $department_id";
                   }
                   $countQuery .= $whereClause;
                   $selectQuery = $selectQuery . $whereClause . "  ORDER BY course_name LIMIT $limit OFFSET $offset";
                    if (isset($_GET["subject_name"])) {
                        $subject_name = $_GET["subject_name"];
                        $subject_name = mysqli_real_escape_string($conn, $subject_name);
                        $countQuery = "SELECT COUNT(*) as total FROM `tbl_subjects` WHERE `subject_name` LIKE '%$subject_name%'";
                        $selectQuery = "SELECT * FROM `tbl_subjects` INNER JOIN `tbl_course` ON tbl_course.course_id = tbl_subjects.subject_course LEFT JOIN tbl_department ON tbl_course.course_department_id = tbl_department.department_id WHERE `subject_name` LIKE '%$subject_name%' ";
                        if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == 4 && isset($_SESSION["department_id"])) {
                            $department_id = $_SESSION["department_id"];
                            $whereClause = " AND tbl_course.course_department_id = $department_id";
                        } 
                        $selectQuery = $selectQuery . $whereClause . "  ORDER BY course_name LIMIT $limit OFFSET $offset";
                     }
                    $countResult = mysqli_query($conn, $countQuery);
                    $totalRecords = mysqli_fetch_assoc($countResult)['total'];
                    $totalPages = ceil($totalRecords / $limit);
                    $result = mysqli_query($conn, $selectQuery);
                    while ($data = mysqli_fetch_array($result)) {
                    ?>
                        <tr>
                            <td><?= $count += 1 ?></td>
                            <td><?= $data["department_name"] ?></td>
                            <td><?= $data["course_name"] ?></td>
                            <td><?= $data["subject_code"] ?></td>
                            <td><?= $data["subject_name"]  ?></td>
                            <td><?= $data["subject_for"]  ?></td>
                            <td><?= $data["subject_type"] == 1 ? " Core":" Optional"  ?></td>
                            <td><?= $data["subject_theory"]  ?></td>
                            <td><?= $data["subject_practical"]  ?></td>
                            <td>
                                <a href="edit.php?subject_id=<?= $data["subject_id"] ?>" class="btn btn-sm shadow btn-info">
                                    <i class="fa fa-pen"></i>
                                </a>
                                <a href="delete.php?subject_id=<?= $data["subject_id"] ?>" onclick="if(confirm('Are you sure want to delete this subject?')){return true}else{return false;}" class="btn btn-sm shadow btn-danger">
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
                        <a class="btn btn-sm btn-outline-info ml-2" href="?page=<?php echo $page - 1; ?>&subject_name=<?php echo isset($subject_name) ? $subject_name : ''; ?>">Previous</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a class="btn btn-sm <?= $page == $i?"btn-info":"btn-outline-info" ?>  ml-2 shadow" href="?page=<?php echo $i; ?>&subject_name=<?php echo isset($subject_name) ? $subject_name : ''; ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a class="btn btn-sm btn-outline-info ml-2" href="?page=<?php echo $page + 1; ?>&subject_name=<?php echo isset($subject_name) ? $subject_name : ''; ?>">Next</a>
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

    // Title
    doc.setFontSize(16);
    doc.text('Subject Management Report', 14, 16);

    // Table headers
    doc.setFontSize(12);
    const startX = 14;
    const startY = 30;
    const lineSpacing = 10;

    // Set column headers
    doc.text('#', startX, startY);
    doc.text('Subject Code', startX + 10, startY);
    doc.text('Subject Name', startX + 50, startY);
    doc.text('Subject For', startX + 100, startY);
    doc.text('Subject Type', startX + 130, startY);
    doc.text('Theory Marks', startX + 160, startY);
    doc.text('Practical Marks', startX + 190, startY);
    doc.text('Total Marks', startX + 220, startY);

    // Fetch data and populate PDF
    fetch('download-pdf.php')
        .then(response => response.json())
        .then(data => {
            let y = startY + lineSpacing; // Move below headers

            data.forEach((item, index) => {
                doc.text((index + 1).toString(), startX, y); // Index column
                doc.text(item.subject_code, startX + 10, y); // Subject Code
                doc.text(item.subject_name, startX + 50, y); // Subject Name
                doc.text(item.subject_for, startX + 100, y); // Subject For (Semester/Year)
                doc.text(item.subject_type == 1 ? "Core" : "Optional", startX + 130, y); // Subject Type
                doc.text(item.subject_theory.toString(), startX + 160, y); // Theory Marks
                doc.text(item.subject_practical.toString(), startX + 190, y); // Practical Marks
                doc.text((parseInt(item.subject_theory) + parseInt(item.subject_practical)).toString(), startX + 220, y); // Total Marks
                y += lineSpacing;
            });

            if (data.length === 0) {
                doc.text('No Subjects Found', 14, y);
            }

            // Save the PDF
            doc.save('subjects_report.pdf');
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
                const ws = XLSX.utils.json_to_sheet(data.map((item, index) => ({
                    '#': index + 1,
                    'Subject Code': item.subject_code,
                    'Subject Name': item.subject_name,
                    'Subject For': item.subject_for,
                    'Subject Type': item.subject_type == 1? "Core":"Practical", // Type
                    'Theory Marks': item.subject_theory, // Total Marks
                    'Practical Marks': item.subject_practical, // Replace with actual field name
                    'Total Marks': (parseInt(item.subject_practical)+parseInt(item.subject_theory)) // Replace with actual field name
                })));

                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Subjects');

                // Save the workbook as an Excel file
                XLSX.writeFile(wb, 'subjects_report.xlsx');
            })
            .catch(error => console.error('Error fetching data:', error));
    });

    const fetchData = async () => {
        try {
            const response = await fetch('download-pdf.php');
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error fetching data:', error);
            return [];
        }
    };

    // Print the fetched data
    const printTableData = async () => {
        const data = await fetchData();

        // Create a dynamic table with the fetched data
        let printContents = '<table class="table">';
        printContents += '<thead><tr><th>#</th><th>Subject Code</th><th>Subject Name</th><th>Subject For</th><th>Total Marks</th><th>Theory Marks</th><th>Practical Marks</th><th>Total Marks</th></tr></thead>';
        printContents += '<tbody>';
        
        data.forEach((item, index) => {
            printContents += `<tr>
                                <td>${index + 1}</td>
                                <td>${item.subject_code}</td>
                                <td>${item.subject_name}</td>
                                <td>${item.subject_for}</td>
                                <td>${item.subject_type == 1? "Core":"Practical"}</td>
                                <td>${item.subject_theory}</td>
                                <td>${item.subject_practical}</td> <!-- Replace with actual field name -->
                                <td>${(parseInt(item.subject_practical)+parseInt(item.subject_theory))}</td> <!-- Replace with actual field name -->
                              </tr>`;
        });

        printContents += '</tbody></table>';

        // Open print dialog with only the fetched data
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        window.location.reload();
        document.body.innerHTML = originalContents;
    };

    // Event listener for the print button
    document.getElementById('print-page').addEventListener('click', function() {
        printTableData();
    });
</script>


<?php
include "../component/footer.php";
?>