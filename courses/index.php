<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";
?>
<div class="content-wrapper p-2">
    <div class="card ">
        <div class="card-header">
            <div class="text-center p-3">
                <h3 class="font-weight-bold">Course Management</h3>
            </div>
            <form action="">
                <div class="row justify-content-end">
                    <div class="col-2 font-weight-bold">
                        Course Name
                        <input type="text" name="course_name" value="<?= isset($_GET["course_name"]) ? $_GET["course_name"] : "" ?>" class="form-control font-weight-bold" placeholder="Course Name">
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
                        <a href="create.php" class="font-weight-bold w-100 shadow btn btn-success"> <i class="fas fa-plus"></i>&nbsp; Add Course</a>
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
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Credits</th>
                        <th>Course Duration</th>
                        <th>Department</th>
                        <th>Action</th>
                    </tr>
                    <?php
                   $count = 0;
                   $limit = 10;
                   $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                   $offset = ($page - 1) * $limit;
                   $course_name = isset($_GET["course_name"]) ? $_GET["course_name"] : '';
                   
                   $baseQuery = "SELECT tbl_course.*, tbl_department.department_name FROM `tbl_course`
                                 LEFT JOIN tbl_department ON tbl_course.course_department_id = tbl_department.department_id";
                   
                   $countQuery = "SELECT COUNT(*) as total FROM `tbl_course`";
                   $whereClause = ""; // Initialize as empty
                   
                   // Check if the user role exists and is associated with a department
                   if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == 4 && isset($_SESSION["department_id"])) {
                       $department_id = $_SESSION["department_id"];
                       $whereClause = " WHERE tbl_course.course_department_id = $department_id";
                   }
                   
                   // Apply course name filter if provided
                   if (!empty($course_name)) {
                       $course_name = mysqli_real_escape_string($conn, $course_name);
                       $whereClause .= (!empty($whereClause) ? " AND" : " WHERE") . " tbl_course.course_name LIKE '%$course_name%'";
                   }
                   
                   // Construct the final queries with the condition
                   $countQuery .= $whereClause;
                   $selectQuery = $baseQuery . $whereClause . " LIMIT $limit OFFSET $offset";
                   
                   $countResult = mysqli_query($conn, $countQuery);
                   $totalRecords = mysqli_fetch_assoc($countResult)['total'];
                   $totalPages = ceil($totalRecords / $limit);
                   $result = mysqli_query($conn, $selectQuery);
                   
                    while ($data = mysqli_fetch_array($result)) {
                    ?>
                        <tr>
                            <td><?= $count += 1 ?></td>
                            <td><?= $data["course_code"] ?></td>
                            <td><?= $data["course_name"] ?></td>
                            <td><?= $data["course_credits"] ?></td>
                            <td><?= $data["course_duration"]." Semester" ?></td>
                            <td><?= $data["department_name"] ?></td>
                            <td>
                                <a href="edit.php?course_id=<?= $data["course_id"] ?>" class="btn btn-sm shadow btn-info">
                                    <i class="fa fa-pen"></i>
                                </a>
                                <a href="delete.php?course_id=<?= $data["course_id"] ?>" onclick="if(confirm('Are you sure want to delete this course?')){return true}else{return false;}" class="btn btn-sm shadow btn-danger">
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
                            <td colspan="6" class="font-weight-bold text-center">
                                <span class="text-danger">Courses Not Found.</span>
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
                        <a class="btn btn-sm btn-outline-info ml-2" href="?page=<?php echo $page - 1; ?>&course_name=<?php echo isset($course_name) ? $course_name : ''; ?>">Previous</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a class="btn btn-sm <?= $page == $i?"btn-info":"btn-outline-info" ?>  ml-2 shadow" href="?page=<?php echo $i; ?>&course_name=<?php echo isset($course_name) ? $course_name : ''; ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a class="btn btn-sm btn-outline-info ml-2" href="?page=<?php echo $page + 1; ?>&course_name=<?php echo isset($course_name) ? $course_name : ''; ?>">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    document.getElementById('download-pdf').addEventListener('click', () => {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Utility to add text to the PDF
    const addText = (text, x, y, maxLength = null) => {
        const truncatedText = maxLength && text.length > maxLength ? `${text.substring(0, maxLength)}...` : text;
        doc.text(truncatedText, x, y);
    };

    // PDF Title
    doc.setFontSize(16);
    addText('Course Report', 14, 16);

    // Table headers
    const headers = ['#', 'Course Code', 'Course Name', 'Credits', 'Department'];
    const positions = [10, 30, 70, 120, 160];
    
    doc.setFontSize(12);
    headers.forEach((header, index) => addText(header, positions[index], 30));

    // Fetch data and populate PDF
    fetch('download-pdf.php')
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                addText('No Courses Found', 14, 40);
            } else {
                let y = 40;
                data.forEach((item, index) => {
                    addText((index + 1).toString(), 10, y); // Index
                    addText(item.course_code, 30, y, 10); // Course Code (max 10 chars)
                    addText(item.course_name, 70, y, 20); // Course Name (max 20 chars)
                    addText(item.course_credits, 120, y); // Credits
                    addText(item.department_name, 160, y); // Department
                    y += 10;
                });
            }

            // Save the PDF
            doc.save('courses_report.pdf');
        });
});


    document.getElementById('download-excel').addEventListener('click', function() {
        fetch('download-pdf.php')
            .then(response => response.json())
            .then(data => {
                // Create a new workbook and worksheet
                const ws = XLSX.utils.json_to_sheet(data.map((item, index) => ({
                    '#': index + 1,
                    'Course Code': item.course_code,
                    'Course Name': item.course_name,
                    'Credits': item.course_credits,
                    'Department': item.department_name
                })));

                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Courses');

                // Save the workbook as an Excel file
                XLSX.writeFile(wb, 'courses_report.xlsx');
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
        printContents += '<thead><tr><th>#</th><th>Course Code</th><th>Course Name</th><th>Credits</th><th>Department</th></tr></thead>';
        printContents += '<tbody>';
        
        data.forEach((item, index) => {
            printContents += `<tr>
                                <td>${index + 1}</td>
                                <td>${item.course_code}</td>
                                <td>${item.course_name}</td>
                                <td>${item.course_credits}</td>
                                <td>${item.department_name}</td>
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
