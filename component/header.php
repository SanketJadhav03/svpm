<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>
    <?php
    echo isset($title) ? $title : "College Management";
    ?>
  </title>
  <?php
  $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/svpm/';
  if (!isset($_SESSION["user_role"])) {
    $temp = $base_url . "authentication/";
    header("Location: $temp");
  }
  ?>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?php echo $base_url; ?>assets/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?php echo $base_url; ?>assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $base_url; ?>assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini ">
  <div class="wrapper">

    <!-- Preloader
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble" src="<?php echo $base_url; ?>assets/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div> -->

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <?php
          if (isset($_SESSION["user_role"])) {
            $home_url = '';

            // Check the user role
            if ($_SESSION["user_role"] == 1) {
              $home_url = 'index.php';
            } elseif ($_SESSION["user_role"] == 3) {
              $home_url = 'dashboard/student.php';
            } else if ($_SESSION["user_role"] == 4) {
              $home_url = 'dashboard/department.php';
            }

            // Only display the link if a valid URL is set
            if ($home_url != '') {
              echo '<a href="' . $base_url . $home_url . '" class="nav-link">Home</a>';
            }
          }
          ?>
        </li>


        <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li> -->
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->


        <!-- <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li> -->
        <li class="nav-item">
          <a class="btn bg-danger " href="<?= $base_url . "logout.php" ?>">
            <i class="fas  fa-sign-out-alt"></i>
          </a>
        </li>

        <!-- <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li> -->
      </ul>
    </nav>
    <!-- /.navbar -->