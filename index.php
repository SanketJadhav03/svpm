<?php
$title = "Admin Dashboard";
include "config/connection.php";
include("component/header.php");
include "component/sidebar.php";
if (isset($_SESSION["user_role"])) {
  if ($_SESSION["user_role"] == 3) {
    $temp_student_url = $base_url . "dashboard/student.php";
    echo "<script>window.location = '$temp_student_url'</script>";
  } else if ($_SESSION["user_role"] == 4) {
    $temp_student_url = $base_url . "dashboard/department.php";
    echo "<script>window.location = '$temp_student_url'</script>";
  }
}

// Fetch active notices from the database
$notices = [];
$query = "SELECT * FROM `tbl_notices` WHERE `notice_status` = 1 ORDER BY `notice_id` DESC LIMIT 9"; // Only fetch active notices
$result = mysqli_query($conn, $query);

if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $notices[] = $row; // Store each notice in the array
  }
}
?>
<style>
  .notices-marquee {
    height: 330px;
    overflow: hidden;
    position: relative;
    background-color: #f1f1f1;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 10px;
  }

  .notice-item {
    margin-bottom: 20px;
    display: block;
    padding: 10px;
    border-bottom: 1px solid #ddd;
    background-color: #ffffff;
    border-radius: 4px;
    transition: transform 0.2s ease;
    font-size: 14px;
    /* Initial font size */
  }

  .notice-item:hover {
    transform: scale(1.1);
    /* Slightly enlarge on hover */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    /* Darker shadow on hover */
    font-size: 16px;
    /* Increase font size on hover */
  }

  /* New styles for the sliding animation */
  @keyframes scroll-vertical {
    0% {
      transform: translateY(100%);
    }

    100% {
      transform: translateY(-100%);
    }
  }

  .notices-content {
    display: flex;
    flex-direction: column;
    animation: scroll-vertical 15s linear infinite;
    animation-play-state: running;
    /* Default state is running */
  }

  .notices-content:hover {
    animation-play-state: paused;
    padding: 10px;
    /* Pause animation on hover */
  }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper pt-2">
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Info boxes -->
      <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <a href="<?= $base_url . "student/" ?>" class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></a>

            <div class="info-box-content">
              <?php
              $queryRoll = "SELECT COUNT(*) AS student_count FROM tbl_students;";
              $dataRoll = mysqli_fetch_array(mysqli_query($conn, $queryRoll));
              ?>
              <span class="info-box-text">Total Students</span>
              <span class="info-box-number"><?= $dataRoll["student_count"] ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box">
            <a href="<?= $base_url . "courses/" ?>" class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i>
            </a>

            <div class="info-box-content">
              <?php
              $dataCourse = "SELECT COUNT(*) AS course_count FROM tbl_course;";
              $dataCourse = mysqli_fetch_array(mysqli_query($conn, $dataCourse));
              ?>
              <span class="info-box-text">Courses</span>
              <span class="info-box-number">
                <?= $dataCourse["course_count"] ?>
              </span>
            </div>

            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <a href="<?= $base_url . "faculty/" ?>" class="info-box-icon bg-danger elevation-1"> <i class=" fas fa-chalkboard"></i></a>

            <div class="info-box-content">
              <?php
              $dataFaculty = "SELECT COUNT(*) AS faculty_count FROM tbl_faculty;";
              $dataFaculty = mysqli_fetch_array(mysqli_query($conn, $dataFaculty));
              ?>
              <span class="info-box-text">Faculty</span>
              <span class="info-box-number">
                <?= $dataFaculty["faculty_count"] ?>
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <a href="<?= $base_url . "notice/" ?>" class="info-box-icon bg-success elevation-1"><i class="fas fa-sticky-note"></i></a>

            <div class="info-box-content">
              <?php
              $queryNotice = "SELECT COUNT(*) AS notice_count FROM tbl_notices ;";
              $noticesCount = mysqli_fetch_array(mysqli_query($conn, $queryNotice));
              ?>
              <span class="info-box-text">Notice</span>
              <span class="info-box-number"><?= $noticesCount["notice_count"] ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- /.col -->
      </div>
      <!-- /.row -->



      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-12">
              <div class="card ">
                <div class="card-header">
                  <h3 class="card-title">Notices</h3>
                  <div class="card-tools">

                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>

                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="notices-marquee">
                    <div class="notices-content">
                      <div class="row">
                        <?php foreach ($notices as $notice): ?>
                          <div class="col-md-4">
                            <span class="notice-item">
                              <i class="fas fa-thumbtack"></i>&nbsp;
                              <b><?= htmlspecialchars($notice['notice_title']) ?></b>
                              <div class="notice-date"> - <?= date('F j, Y g:i A', strtotime($notice['notice_date'])) ?></div>
                              <div> - <?= htmlspecialchars($notice['notice_description']) ?></div>
                            </span>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- /.card-body -->

              </div>
            </div>
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">
                    Exam Shedule Information
                  </h3>
                  <div class="card-tools">

                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>

                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <table class="table bordered table- ">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Exam Title</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Department</th>
                        <th>Course</th>
                        <th>Time Table</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $examQuery = "SELECT e.exam_id, e.exam_title, e.exam_description, e.exam_start_date, e.exam_end_date, e.exam_status, d.department_name, c.course_name 
            FROM tbl_exam e 
            JOIN tbl_department d ON e.exam_department_id = d.department_id
            JOIN tbl_course c ON e.exam_course_id = c.course_id";
                      $examResult = mysqli_query($conn, $examQuery);
                      if (mysqli_num_rows($examResult) > 0): ?>
                        <?php $count = 1;
                        while ($exam = mysqli_fetch_assoc($examResult)): ?>
                          <tr>
                            <td><?= $count++; ?></td>
                            <td><?= $exam['exam_title']; ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($exam['exam_start_date'])); ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($exam['exam_end_date'])); ?></td>
                            <td><?= $exam['exam_status']; ?></td>
                            <td><?= $exam['department_name']; ?></td>
                            <td><?= $exam['course_name']; ?></td>
                            <td>
                              <a href="time_table_list.php?exam_id=<?= $exam['exam_id']; ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-calendar-alt"></i>&nbsp; Time Table
                              </a>
                            </td>
                          </tr>
                        <?php endwhile; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="9" class="text-center">No exams found</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!-- /.row -->

          <!-- TABLE: LATEST ORDERS -->
          <div class="card">
            <div class="card-header border-transparent">
              <h3 class="card-title">Latest Students</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table ">
                  <tr>
                    <th>#</th>
                    <th>Roll No</th>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Course</th>
                  </tr>
                  <?php
                  $count = 0;
                  $limit = 10;


                  $selectQuery = "SELECT * FROM `tbl_students` INNER JOIN `tbl_course` ON tbl_course.course_id = tbl_students.student_course ORDER BY `student_id` DESC  LIMIT $limit  ";

                  $result = mysqli_query($conn, $selectQuery);
                  while ($data = mysqli_fetch_array($result)) {
                  ?>
                    <tr>
                      <td><?= $count += 1 ?></td>

                      <td><?= $data["student_roll"] ?></td>
                      <td><?= $data["student_first_name"] . " " . $data["student_last_name"] ?></td>
                      <td><?= $data["student_email"] ?></td>
                      <td><?= $data["student_contact"] ?></td>
                      <td><?= $data["course_name"] ?></td>

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
              <!-- /.table-responsive -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
              <a href="<?= $base_url ?>student/create.php" class="btn btn-sm shadow btn-info float-left">Add New Student</a>
              <a href="<?= $base_url ?>student/index.php" class="btn btn-sm shadow btn-secondary float-right">View All Students</a>
            </div>
            <!-- /.card-footer -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->


        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div><!--/. container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
  window.onload = function() {
    const marquee = document.querySelector('.notices-content');
    const speed = 30000; // Speed of the animation (higher value = slower)
    marquee.style.animationDuration = `${speed / 1000}s`;
  };
</script>
<?php
include "component/footer.php";
?>