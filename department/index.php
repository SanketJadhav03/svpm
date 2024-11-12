<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";
?>
<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <div class="text-center p-3">
                <h3 class="font-weight-bold">Department Management</h3>
            </div>
            <form action="">
                <div class="row justify-content-end">
                    <div class="col-2 font-weight-bold">
                        Department Name
                        <input type="text" name="department_name" value="<?= isset($_GET["department_name"]) ? $_GET["department_name"] : "" ?>" class="form-control font-weight-bold" placeholder="Department Name">
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
                        <a href="create.php" class="font-weight-bold w-100 shadow btn btn-success"> <i class="fas fa-plus"></i>&nbsp; Add Department</a>
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
                        <th>Department Name</th>
                        <th>Department Code</th>
                        <th>Description</th>
                        <th>HOD Name</th>
                        <th>HOD Contact</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $count = 0;
                    $limit = 10;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;
                    $countQuery = "SELECT COUNT(*) as total FROM `tbl_department`";
                    $selectQuery = "SELECT * FROM `tbl_department` LIMIT $limit OFFSET $offset";
                    if (isset($_GET["department_name"])) {
                        $department_name = $_GET["department_name"];
                        $department_name = mysqli_real_escape_string($conn, $department_name);
                        $countQuery = "SELECT COUNT(*) as total FROM `tbl_department` WHERE `department_name` LIKE '%$department_name%'";
                        $selectQuery = "SELECT * FROM `tbl_department` WHERE `department_name` LIKE '%$department_name%' LIMIT $limit OFFSET $offset";
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
                            <td><?= $data["department_code"] ?></td>
                            <td><?= $data["department_description"] ?></td>
                            <td><?= $data["department_hod_name"] ?></td>
                            <td><?= $data["department_hod_contact"] ?></td>
                            <td><?= $data["department_email"] ?></td>
                            <td><?= $data["department_phone"] ?></td>
                            <td>
                                <a href="edit.php?department_id=<?= $data["department_id"] ?>" class="btn btn-sm shadow btn-info">
                                    <i class="fa fa-pen"></i>
                                </a>
                                <a href="delete.php?department_id=<?= $data["department_id"] ?>" onclick="if(confirm('Are you sure want to delete this department?')){return true}else{return false;}" class="btn btn-sm shadow btn-danger">
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
                            <td colspan="10" class="font-weight-bold text-center">
                                <span class="text-danger">Departments Not Found.</span>
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
                        <a class="btn btn-sm btn-outline-info ml-2" href="?page=<?php echo $page - 1; ?>&department_name=<?php echo isset($department_name) ? $department_name : ''; ?>">Previous</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a class="btn btn-sm <?= $page == $i ? "btn-info" : "btn-outline-info" ?>  ml-2 shadow" href="?page=<?php echo $i; ?>&department_name=<?php echo isset($department_name) ? $department_name : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a class="btn btn-sm btn-outline-info ml-2" href="?page=<?php echo $page + 1; ?>&department_name=<?php echo isset($department_name) ? $department_name : ''; ?>">Next</a>
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

        const addText = (text, x, y, maxLength = null) => {
            const truncatedText = maxLength && text.length > maxLength ? `${text.substring(0, maxLength)}...` : text;
            doc.text(truncatedText, x, y);
        };

        doc.setFontSize(16);
        addText('Department Report', 14, 16);

        const headers = ['#', 'Department ID', 'Department Name', 'Department Code', 'Description', 'HOD Name', 'HOD Contact', 'Email', 'Phone'];
        const positions = [10, 30, 50, 70, 90, 110, 130, 150, 170];

        doc.setFontSize(12);
        headers.forEach((header, index) => addText(header, positions[index], 30));

        fetch('download-pdf.php')
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    addText('No Departments Found', 14, 40);
                } else {
                    let y = 40;
                    data.forEach((item, index) => {
                        addText((index + 1).toString(), 10, y); // Index
                        addText(item.department_id, 30, y); // Department ID
                        addText(item.department_name, 50, y); // Department Name
                        addText(item.department_code, 70, y); // Department Code
                        addText(item.department_description, 90, y); // Description
                        addText(item.department_hod_name, 110, y); // HOD Name
                        addText(item.department_hod_contact, 130, y); // HOD Contact
                        addText(item.department_email, 150, y); // Email
                        addText(item.department_phone, 170, y); // Phone
                        y += 10;
                    });
                }
                doc.save('departments_report.pdf');
            });
    });

    document.getElementById('download-excel').addEventListener('click', function() {
        fetch('download-pdf.php')
            .then(response => response.json())
            .then(data => {
                const ws = XLSX.utils.json_to_sheet(data.map((item, index) => ({
                    '#': index + 1,
                    'Department ID': item.department_id,
                    'Department Name': item.department_name,
                    'Department Code': item.department_code,
                    'Description': item.department_description,
                    'HOD Name': item.department_hod_name,
                    'HOD Contact': item.department_hod_contact,
                    'Email': item.department_email,
                    'Phone': item.department_phone
                })));

                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Departments');

                XLSX.writeFile(wb, 'departments_report.xlsx');
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

    const printTableData = async () => {
        const data = await fetchData();

        let printContents = '<table class="table">';
        printContents += '<thead><tr><th>#</th><th>Department Name</th><th>Department Code</th><th>Description</th><th>HOD Name</th><th>HOD Contact</th><th>Email</th><th>Phone</th></tr></thead>';
        printContents += '<tbody>';
        
        data.forEach((item, index) => {
            printContents += `<tr>
                                <td>${index + 1}</td> 
                                <td>${item.department_name}</td>
                                <td>${item.department_code}</td>
                                <td>${item.department_description}</td>
                                <td>${item.department_hod_name}</td>
                                <td>${item.department_hod_contact}</td>
                                <td>${item.department_email}</td>
                                <td>${item.department_phone}</td>
                              </tr>`;
        });

        printContents += '</tbody></table>';

        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        window.location.reload();
        document.body.innerHTML = originalContents;
    };

    document.getElementById('print-page').addEventListener('click', function() {
        printTableData();
    });
</script>
<?php
include "../component/footer.php";
?>
