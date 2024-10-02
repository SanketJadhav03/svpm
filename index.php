<?php
$title = "Admin Dashboard";
include "config/connection.php";
include("component/header.php");
include "component/sidebar.php";


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
    /* Set a fixed height for the marquee */
    overflow: hidden;
    /* Hide overflow */
    position: relative;
    /* Position relative for absolute positioning of content */
    background-color: #f1f1f1;
    /* Light gray background */
    /* Blue border */
    border-radius: 8px;
    /* Rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    /* Soft shadow */
    padding: 10px;
    /* Padding inside the marquee */
  }


  .notice-item {
    margin-bottom: 20px;
    /* Space between notices */
    display: block;
    /* Align notices in a block for vertical layout */
    padding: 10px;
    /* Padding around notice */
    border-bottom: 1px solid #ddd;
    /* Bottom border for separation */
    background-color: #ffffff;
    /* White background for each notice */
    border-radius: 4px;
    /* Slightly rounded corners for notices */
    transition: transform 0.2s ease;
    /* Transition effect for hover */
  }

  /* Change background on hover */
  .notice-item:hover {
    transform: scale(1.02);
    /* Slightly enlarge on hover */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    /* Darker shadow on hover */
  }

  /* Vertical scrolling keyframes */
  @keyframes scroll-vertical {
    0% {
      transform: translateY(100%);
      /* Start from the bottom */
    }

    100% {
      transform: translateY(-100%);
      /* End at the top */
    }
  }

  /* Optional: Text Styling */
  .notice-item span {
    font-weight: bold;
    /* Bold notice text */
    color: #333;
    /* Dark gray color for text */
  }

  /* Optional: Date and Time Styling */
  .notice-date {
    font-size: 0.85em;
    /* Smaller font for date */
    color: #666;
    /* Lighter gray for date */
  }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-12 text-center">
          <h1 class="h1">Admin Dashboard </h1>
        </div><!-- /.col -->

      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

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
              <span class="info-box-text">Courses</span>
              <span class="info-box-number">
                10
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

              <span class="info-box-text">Faculty</span>
              <span class="info-box-number">0</span>
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
          <!-- MAP & BOX PANE -->

          <!-- /.card -->
          <div class="row">
            <div class="col-md-12">
              <!-- DIRECT CHAT -->
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
                    <div class="row">
                      <?php foreach ($notices as $notice): ?>
                        <div class="col-md-4">
                          <span class="notice-item">
                          <i class="fas fa-thumbtack" ></i>&nbsp; 
                          <b><?= htmlspecialchars($notice['notice_title']) ?> </b>
                            <div class="notice-date"> - <?= date('F j, Y g:i A', strtotime($notice['notice_date'])) ?></div>
                            <div>
                              - <?= htmlspecialchars($notice['notice_description']) ?>
                            </div>
                          </span>
                        </div>
                       
                      <?php endforeach; ?>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

              </div>


              <!--/.direct-chat -->
            </div>
            <!-- /.col -->


            <!-- /.col -->
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


                  $selectQuery = "SELECT * FROM `tbl_students` INNER JOIN `tbl_courses` ON tbl_courses.course_id = tbl_students.student_course ORDER BY `student_id` DESC  LIMIT $limit  ";

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

<?php
include "component/footer.php";
?>