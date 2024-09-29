  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
     
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo $base_url; ?>assets/images/admin/default.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Admin</a>
        </div>
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
               <li class="nav-item">
            <a href="<?php echo $base_url; ?>index.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>courses/index.php" class="nav-link">
              <i class="nav-icon fas fa-laptop"></i>
              <p>Courses</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $base_url; ?>subject/index.php" class="nav-link">
              <i class="nav-icon fas fa-envelope"></i>
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
              <i class="nav-icon fas fa-user-plus"></i>
              <p>Student</p>
            </a>
          </li>
          <li class="nav-item">
                <a href="<?php echo $base_url; ?>faculty/index.php" class="nav-link">
                  <i class="fas fa-user nav-icon"></i>
                  <p>Faculty</p>
                </a>
              </li>
               
               
          <li class="nav-item">
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

          </li>
           
               
          <!-- <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Setting's
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/charts/chartjs.html" class="nav-link">
                  <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                  <p>Department</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/chartjs.html" class="nav-link">
                  <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                  <p>Course</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/chartjs.html" class="nav-link">
                  <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                  <p>Interested</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/chartjs.html" class="nav-link">
                  <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                  <p>Education</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/chartjs.html" class="nav-link">
                  <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                  <p>Inquiry For</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/chartjs.html" class="nav-link">
                  <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                  <p>Inquiry Status</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/chartjs.html" class="nav-link">
                  <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                  <p>source</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/chartjs.html" class="nav-link">
                  <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                  <p>Shift</p>
                </a>

              </li>
              <li class="nav-item">
                <a href="pages/charts/chartjs.html" class="nav-link">
                  <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                  <p>Payment Modes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/chartjs.html" class="nav-link">
                  <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                  <p>City</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/chartjs.html" class="nav-link">
                  <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                  <p>Batch For</p>
                </a>
              </li>
            </ul>

          </li>
            -->
         
             
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
