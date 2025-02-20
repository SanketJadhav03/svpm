  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3  mb-3 pl-3 ">

        <a href="#" class="d-block">
          <!-- <i class="fas fa-user-cog"></i> -->
          Welcome, <?= $_SESSION["username"] ?>
        </a>
      </div>

      <!-- SidebarSearch Form -->
      <!-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <?php
          if ($_SESSION["user_role"] == 1) {
          ?>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>index.php" class="nav-link">
                <i class="nav-icon fas fa-chart-pie"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>principle/index.php" class="nav-link">
                <i class="nav-icon fas fa-university"></i>
                <p>Principle</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>faculty/index.php" class="nav-link">
                <i class="nav-icon fas fa-chalkboard"></i>

                <p>Faculty</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>student/index.php" class="nav-link">
                <i class="nav-icon fas fa-graduation-cap"></i>

                <p>Student</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>attendence/attendencelist.php" class="nav-link">
                <i class="nav-icon fas fa-calendar-check"></i>

                <p>Student Attendance</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>attendence/facultyattendence.php" class="nav-link">
                <i class="nav-icon fas fa-calendar-check"></i>

                <p>Faculty Attendance</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>department/index.php" class="nav-link">
                <i class="nav-icon fas fa-building"></i>

                <p>Department</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>courses/index.php" class="nav-link">
                <i class="nav-icon fas fa-book"></i>

                <p>Courses</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>subject/index.php" class="nav-link">
                <i class="nav-icon fas fa-file-alt"></i>

                <p>Subject</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>notice/index.php" class="nav-link">
                <i class="nav-icon fas fa-sticky-note"></i>

                <p>Notices</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>exam/index.php" class="nav-link">
                <i class="fas fa-clipboard-list nav-icon"></i>

                <p>Manage Exams</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?= $base_url ?>#" class="nav-link">
                <i class="fas fa-chart-bar nav-icon"></i>
                <p>
                  Reports
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= $base_url ?>reports/student.php" class="nav-link">
                    <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                    <p>Student Report</p>
                  </a>
                </li>

              </ul>
            </li>


          <?php
          }
          ?>
          <?php
          if ($_SESSION["user_role"] == 2) {
          ?>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>index.php" class="nav-link">
                <i class="nav-icon fas fa-chart-pie"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>faculty/index.php" class="nav-link">
                <i class="nav-icon fas fa-chalkboard"></i>

                <p>Faculty</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>student/index.php" class="nav-link">
                <i class="nav-icon fas fa-graduation-cap"></i>

                <p>Student</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>attendence/attendencelist.php" class="nav-link">
                <i class="nav-icon fas fa-calendar-check"></i>

                <p>Student Attendance</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>attendence/facultyattendence.php" class="nav-link">
                <i class="nav-icon fas fa-calendar-check"></i>

                <p>Faculty Attendance</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>department/index.php" class="nav-link">
                <i class="nav-icon fas fa-building"></i>

                <p>Department</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>courses/index.php" class="nav-link">
                <i class="nav-icon fas fa-book"></i>

                <p>Courses</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>subject/index.php" class="nav-link">
                <i class="nav-icon fas fa-file-alt"></i>

                <p>Subject</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?php echo $base_url; ?>notice/index.php" class="nav-link">
                <i class="nav-icon fas fa-sticky-note"></i>

                <p>Notices</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>exam/index.php" class="nav-link">
                <i class="fas fa-clipboard-list nav-icon"></i>

                <p>Manage Exams</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= $base_url ?>#" class="nav-link">
                <i class="fas fa-chart-bar nav-icon"></i>
                <p>
                  Reports
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= $base_url ?>reports/student.php" class="nav-link">
                    <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                    <p>Student Report</p>
                  </a>
                </li>

              </ul>
            </li>
          <?php
          }
          ?>
          <?php
          if ($_SESSION["user_role"] == 3) {
          ?>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>dashboard/student.php" class="nav-link">
                <i class="nav-icon fas fa-chart-pie"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>student/attendence.php" class="nav-link">
                <i class="nav-icon fas fa-calendar-check"></i>
                <p>Mark Attendance</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>student/coursedetails.php" class="nav-link">
                <i class="nav-icon fas fa-book"></i>
                <p>Course Details</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>student/idcard.php?student_id=<?php echo isset($_SESSION["student_id"]) ? $_SESSION["student_id"] : ""; ?>" class="nav-link">
                <i class="nav-icon fas fa-id-card"></i>
                <p>Generate Id</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>student/results.php" class="nav-link">
                <i class="nav-icon fas fa-poll"></i>
                <p>Result Section</p>
              </a>
            </li>
          <?php
          }
          ?>

          <?php
          if ($_SESSION["user_role"] == 4) {
          ?>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>dashboard/department.php" class="nav-link">
                <i class="nav-icon fas fa-chart-pie"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>faculty/index.php" class="nav-link">
                <i class="nav-icon fas fa-chalkboard"></i>

                <p>Faculty</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>student/index.php" class="nav-link">
                <i class="nav-icon fas fa-graduation-cap"></i>

                <p>Student</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>attendence/attendencelist.php" class="nav-link">
                <i class="nav-icon fas fa-calendar-check"></i>

                <p>Student Attendance</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>attendence/facultyattendence.php" class="nav-link">
                <i class="nav-icon fas fa-calendar-check"></i>

                <p>Faculty Attendance</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>courses/index.php" class="nav-link">
                <i class="nav-icon fas fa-book"></i>

                <p>Courses</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>subject/index.php" class="nav-link">
                <i class="nav-icon fas fa-file-alt"></i>

                <p>Subject</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>notice/index.php" class="nav-link">
                <i class="nav-icon fas fa-sticky-note"></i>

                <p>Notices</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>exam/index.php" class="nav-link">
                <i class="fas fa-clipboard-list nav-icon"></i>

                <p>Manage Exams</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?= $base_url ?>#" class="nav-link">
                <i class="fas fa-chart-bar nav-icon"></i>
                <p>
                  Reports
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= $base_url ?>reports/student.php" class="nav-link">
                    <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                    <p>Student Report</p>
                  </a>
                </li>

              </ul>
            </li>
          <?php
          }
          ?>
          <?php
          if ($_SESSION["user_role"] == 5) {
          ?>
            <!-- 5 for faculty login -->
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>dashboard/faculty.php" class="nav-link">
                <i class="nav-icon fas fa-chart-pie"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>faculty/attendence.php" class="nav-link">
                <i class="nav-icon fas fa-calendar-check"></i>

                <p>Mark Attendance</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>attendence/attendencelist.php" class="nav-link">
                <i class="nav-icon fas fa-calendar-check"></i>

                <p>Student Attendance</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>courses/index.php" class="nav-link">
                <i class="nav-icon fas fa-book"></i>

                <p>Courses</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>notice/index.php" class="nav-link">
                <i class="nav-icon fas fa-sticky-note"></i>

                <p>Notices</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $base_url; ?>exam/index.php" class="nav-link">
                <i class="fas fa-clipboard-list nav-icon"></i>
                <p>Manage Exams</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= $base_url ?>#" class="nav-link">
                <i class="fas fa-tasks nav-icon"></i>
                <p>
                  Manage Assignment
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= $base_url ?>faculty/upload_assignment.php" class="nav-link">
                    <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                    <p>Upload Assignment</p>
                  </a>
                </li>

              </ul>
            </li>
            <li class="nav-item">
              <a href="<?= $base_url ?>#" class="nav-link">
                <i class="fas fa-chart-bar nav-icon"></i>
                <p>
                  Reports
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= $base_url ?>reports/student.php" class="nav-link">
                    <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                    <p>Student Report</p>
                  </a>
                </li>

              </ul>
            </li>
          <?php
          }
          ?>



        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>