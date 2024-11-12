  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
     
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3  mb-3 pl-3 ">
        
          <a  href="#" class="d-block">
          <!-- <i class="fas fa-user-cog"></i> -->
           Welcome,  <?= $_SESSION["username"]?>
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
               if($_SESSION["user_role"]==1){
               ?>
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>index.php" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Dashboard</p>
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
          <!-- <li class="nav-item">
            <a href="<?php echo $base_url; ?>product/index.php" class="nav-link">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>Products</p>
            </a>
          </li> -->
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>student/index.php" class="nav-link">
            <i class="nav-icon fas fa-graduation-cap"></i>

              <p>Student</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>attendence/attendencelist.php" class="nav-link">
            <i class="nav-icon fas fa-calendar-check"></i>

              <p>Attendence</p>
            </a>
          </li>
          <li class="nav-item">
                <a href="<?php echo $base_url; ?>faculty/index.php" class="nav-link">
                <i class="nav-icon fas fa-chalkboard"></i>

                  <p>Faculty</p>
                </a>
              </li>
          <li class="nav-item">
                <a href="<?php echo $base_url; ?>notice/index.php" class="nav-link">
                <i class="nav-icon fas fa-sticky-note"></i>

                  <p>Notices</p>
                </a>
              </li>
               
               
          <!-- <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                User's
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo $base_url; ?>student/index.php" class="nav-link">
                  <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                  <p>Student</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo $base_url; ?>student/index.php" class="nav-link">
                  <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                  <p>Faculty</p>
                </a>
              </li>
               
              
            </ul>

          </li> -->
           <?php
               }
           ?>
                  <?php 
               if($_SESSION["user_role"]==3){
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
              <p>Attendance</p>
            </a>
          </li> 
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>student/coursedetails.php" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>Course Details</p>
            </a>
          </li> 
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>student/idcard.php?student_id=<?php echo isset($_SESSION["student_id"])?$_SESSION["student_id"]:"";?>" class="nav-link">
              <i class="nav-icon fas fa-id-card"></i>
              <p>Generate Id</p>
            </a>
          </li> 
          <?php
               }
          ?>

<?php 
               if($_SESSION["user_role"]==4){
               ?>
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>dashboard/department.php" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Dashboard</p>
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
          <?php
               }
          ?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-clock"></i>
              <p>
                Manage Exam's 
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
                
              <li class="nav-item">
                <a href="pages/charts/chartjs.html" class="nav-link">
                  <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                  <p>Question Paper's</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/chartjs.html" class="nav-link">
                  <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                  <p>Shedule Test</p>
                </a>
              </li>
            </ul>

          </li>
           
         
             
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
