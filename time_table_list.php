<?php
include "config/connection.php";
include "component/header.php";
include "component/sidebar.php";
$exam_id = isset($_GET["exam_id"]) ? $_GET["exam_id"] : null;

// Fetch the exam title and course name based on exam_id
$exam_title = '';
$course_name = '';
$course_id = '';
if ($exam_id) {
    // Join query to fetch both exam title and course name
    $examQuery = "SELECT e.exam_title, c.course_name,c.course_id 
                  FROM tbl_exam e 
                  JOIN tbl_course c ON e.exam_course_id = c.course_id 
                  WHERE e.exam_id = '$exam_id'";

    $result = mysqli_query($conn, $examQuery);

    if ($result && mysqli_num_rows($result) > 0) {
        $exam = mysqli_fetch_assoc($result);
        $exam_title = $exam["exam_title"];
        $course_name = $exam["course_name"];  // Get the course name
        $course_id = $exam["course_id"];  // Get the course name
    }
}
?>

<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <div class="text-center p-3">
                <h3 class="font-weight-bold"><?= $exam_title ?> </h3>
                <div>Time Table</div>
            </div>
            <form action="">
                <div class="row justify-content-end">
                    <div class="col-2 font-weight-bold">
                        Subject Name
                        <input type="hidden" name="exam_id" value="<?= isset($_GET["exam_id"]) ? $_GET["exam_id"] : "" ?>" class="form-control font-weight-bold" placeholder="Subject Name">
                        <input type="text" name="schedule_subject" value="<?= isset($_GET["schedule_subject"]) ? $_GET["schedule_subject"] : "" ?>" class="form-control font-weight-bold" placeholder="Subject Name">
                    </div>
                    <div class="col-2 font-weight-bold">
                        Exam Date
                        <input type="date" name="schedule_date" value="<?= isset($_GET["schedule_date"]) ? $_GET["schedule_date"] : "" ?>" class="form-control font-weight-bold" placeholder="Exam Date">
                    </div>
                    <div class="col-1 font-weight-bold">
                        <br>
                        <button type="submit" class="shadow btn w-100 btn-info font-weight-bold"> <i class="fas fa-search"></i> &nbsp;Find</button>
                    </div>
                    <div class="col-1 font-weight-bold">
                        <br>
                        <button type="button" class="shadow btn w-100 font-weight-bold btn-primary" id="download-excel" data-exam-id="<?= $_GET["exam_id"] ?>"><i class="fas fa-file-excel"></i> &nbsp; Excel</button>
                    </div>
                    <div class="col-1 font-weight-bold">
                        <br>
                        <button type="button" class="shadow btn w-100 font-weight-bold btn-danger" id="download-pdf"><i class="fas fa-file-pdf"></i> &nbsp; PDF</button>
                    </div>  
                    <div class="col-1 text-right font-weight-bold ">
                        <br>
                        <a href="index.php" class="font-weight-bold   shadow btn btn-info"> <i class="fas fa-eye"></i>&nbsp;Home</a>
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
                        <th>Course</th>
                        <th>Subject</th>
                        <th>Exam Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Status</th>
                        <!-- <th>Action</th> -->
                    </tr>
                    <?php
                    $count = 0;
                    $limit = 10;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;
                    $schedule_subject = isset($_GET["schedule_subject"]) ? $_GET["schedule_subject"] : '';
                    $schedule_date = isset($_GET["schedule_date"]) ? $_GET["schedule_date"] : '';
                    // Get exam_id from URL if it exists
                    $exam_id = isset($_GET["exam_id"]) ? $_GET["exam_id"] : "";

                    // Modify your query to filter by exam_id
                    $baseQuery = "SELECT * 
              FROM `tbl_exam_schedule`
              INNER JOIN tbl_subjects ON tbl_exam_schedule.schedule_subject = tbl_subjects.subject_id
              LEFT JOIN tbl_course ON tbl_exam_schedule.schedule_course = tbl_course.course_id
              ";

                    $countQuery = "SELECT COUNT(*) as total  FROM `tbl_exam_schedule` INNER JOIN tbl_subjects ON tbl_exam_schedule.schedule_subject = tbl_subjects.subject_id";
                    $whereClause = ""; // Initialize as empty

                    // Apply filters if provided
                    if (!empty($schedule_subject)) {
                        $schedule_subject = mysqli_real_escape_string($conn, $schedule_subject);
                        $whereClause .= (!empty($whereClause) ? " AND" : " WHERE") . " tbl_exam_schedule.subject_name LIKE '%$schedule_subject%'";
                    }
                    if (!empty($schedule_date)) {
                        $schedule_date = mysqli_real_escape_string($conn, $schedule_date);
                        $whereClause .= (!empty($whereClause) ? " AND" : " WHERE") . " tbl_exam_schedule.schedule_date = '$schedule_date'";
                    }

                    // Add the filter for exam_id if it is provided
                    if (!empty($exam_id)) {
                        $exam_id = mysqli_real_escape_string($conn, $exam_id);
                        $whereClause .= (!empty($whereClause) ? " AND" : " WHERE") . " tbl_exam_schedule.schedule_exam = '$exam_id'";
                    }

                    // Construct the final queries with the condition
                    $countQuery .= $whereClause;
                    $selectQuery = $baseQuery . $whereClause . " LIMIT $limit OFFSET $offset";

                    // Execute the queries
                    $countResult = mysqli_query($conn, $countQuery);
                    $totalRecords = mysqli_fetch_assoc($countResult)['total'];
                    $totalPages = ceil($totalRecords / $limit);
                    $result = mysqli_query($conn, $selectQuery);

                    // Display the results
                    while ($data = mysqli_fetch_array($result)) {
                    ?>
                        <tr>
                            <td><?= $count += 1 ?></td>
                            <td><?= $data["course_name"] ?></td>
                            <td><?= $data["subject_name"] ?></td>
                            <td><?= $data["schedule_date"] ?></td>
                            <td><?= $data["schedule_start_time"] ?></td>
                            <td><?= $data["schedule_end_time"] ?></td>
                            <td>
                                <?= isset($data["schedule_status"])
                                    ? ($data["schedule_status"] == 0 ? "Scheduled"
                                        : ($data["schedule_status"] == 1 ? "Completed"
                                            : "Cancelled"))
                                    : "Unknown" ?>
                            </td>
                            <!-- <td>
                                <a href="time_table_edit.php?schedule_id=<?= $data["schedule_id"] ?>" class="btn btn-sm shadow btn-info">
                                    <i class="fa fa-pen"></i>
                                </a>
                                <a href="time_table_delete.php?schedule_id=<?= $data["schedule_id"] ?>" onclick="if(confirm('Are you sure want to delete this exam schedule entry?')){return true}else{return false;}" class="btn btn-sm shadow btn-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td> -->
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
                        <a class="btn btn-sm btn-outline-info ml-2" href="?page=<?php echo $page - 1; ?>&schedule_subject=<?php echo isset($schedule_subject) ? $schedule_subject : ''; ?>&schedule_date=<?php echo isset($schedule_date) ? $schedule_date : ''; ?>">Previous</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a class="btn btn-sm <?= $page == $i ? "btn-info" : "btn-outline-info" ?>  ml-2 shadow" href="?page=<?php echo $i; ?>&schedule_subject=<?php echo isset($schedule_subject) ? $schedule_subject : ''; ?>&schedule_date=<?php echo isset($schedule_date) ? $schedule_date : ''; ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a class="btn btn-sm btn-outline-info ml-2" href="?page=<?php echo $page + 1; ?>&schedule_subject=<?php echo isset($schedule_subject) ? $schedule_subject : ''; ?>&schedule_date=<?php echo isset($schedule_date) ? $schedule_date : ''; ?>">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to handle export to Excel, PDF, and Print
    document.getElementById('download-pdf').addEventListener('click', () => {
        const {
            jsPDF
        } = window.jspdf;
        const doc = new jsPDF();

        // Utility to add text to the PDF
        const addText = (text, x, y, maxLength = null) => {
            const truncatedText = maxLength && text.length > maxLength ? `${text.substring(0, maxLength)}...` : text;
            doc.text(truncatedText, x, y);
        };

        // PDF Title
        doc.setFontSize(16);
        addText('Exam Schedule Report', 14, 16);

        // Table headers
        const headers = ['#', 'Exam', 'Course', 'Subject', 'Date', 'Start Time', 'End Time', 'Status'];
        const positions = [10, 30, 70, 100, 130, 160, 190, 220];

        doc.setFontSize(12);
        headers.forEach((header, index) => addText(header, positions[index], 30));

        // Fetch data and populate PDF
        fetch('download-pdf.php')
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    addText('No Exam Schedule Entries Found', 14, 40);
                } else {
                    let y = 40;
                    data.forEach((item, index) => {
                        addText((index + 1).toString(), 10, y); // Index
                        addText(item.schedule_exam, 30, y); // Exam Name
                        addText(item.course_name, 70, y); // Course Name
                        addText(item.schedule_subject, 100, y); // Subject Name
                        addText(item.schedule_date, 130, y); // Exam Date
                        addText(item.schedule_start_time, 160, y); // Start Time
                        addText(item.schedule_end_time, 190, y); // End Time
                        addText(item.schedule_status, 220, y); // Status
                        y += 10; // Increment Y position for next row
                    });
                }
                doc.save('Exam_Schedule_Report.pdf');
            });
    });

     
</script>

<?php
include "component/footer.php";
?>