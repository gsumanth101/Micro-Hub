<?php
// Start session
session_start();

// Include the database connection
include('includes/connection.php');

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: studentlogin.php");
}

// Get the username from the session
$username = $_SESSION['username'];

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM student WHERE username = ?");
if (!$stmt) {
    die('Error in preparing statement: ' . $conn->error);
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if user details are found
if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
} else {
    // Redirect to the login page if user details not found
    echo "Invalid Credentials";
}

// Close the prepared statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Student | Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  	<!-- Site favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="assets\images\apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="assets\images\android-chrome-512x512.png">
	<link rel="icon" type="image/png" sizes="16x16" href="assets\images\android-chrome-192x192.png">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="student_dashboard.php" class="nav-link">Home</a>
      </li>
     <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>-->
    </ul>


  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="assets\images\logo1.png" alt="MicroHub" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">MicroHub</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo htmlspecialchars($userData['username']);?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            Dashboard
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="student_dashboard.php" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Student Details</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="status.php" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Submission Status</p>
            </a>
          </li>
 <!--          <li class="nav-item">
            <a href="../../index3.html" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Dashboard v3</p>
            </a>
          </li>-->
        </ul>
      </li>

<!--############################ End DashBoard #########################-->

<!--###################### Micro Project #################################-->
<li class="nav-header">Experimental Elective</li>

      <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa fa-laptop aria-hidden="true"></i>
          <p>
            Micro Project
            <i class="right fas fa-angle-left"></i>
            <span class="right badge badge-danger">New</span>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="micro_registration.php" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Registration</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="project_submission.php" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Project Submission</p>
            </a>
          </li>
 <!--        <li class="nav-item">
            <a href="../../index3.html" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Dashboard v3</p>
            </a>
          </li> --> 
        </ul>
      </li>

<!--###################### End Micro Project #################################-->

<!--######################  Community Service project #################################-->
     <!-- <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-copy"></i>
          <p>
            CSP
            <i class="fas fa-angle-left right"></i>
            <span class="badge badge-info right"></span>
          </p>
        </a>
      
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="not_opened.php" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Registration</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="not_opened.php" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Reviews</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="not_opened.php" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Final Submission</p>
            </a>
          </li>
 
        </ul>
      </li>-->


<!--###################### End Community Service project #################################-->

<!--###################### Hackerthon #################################-->
   <!--   <li class="nav-item">
        <a href="not_opened.php" class="nav-link">
          <i class="nav-icon fas fa-chart-pie"></i>
          <p>
            Hackathon
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="not_opened.php" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Registration</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="not_opened.php" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Submission</p>
            </a>
          </li>
        </ul>
      </li>-->
<!--###################### Hackerthon #################################-->

<!--###################### International Certification #################################-->
<!--<li class="nav-item">
  <a href="not_opened.php" class="nav-link">
    <i class="nav-icon fa fa-inbox aria-hidden="true"></i>
    <p>
      International Certificate
    </p>
  </a>

</li>-->
<!--###################### International Certification #################################-->

<!--######################   Non CGPA  ###########################3-->

<!--############################# Group 1 ###################################-->
 <!--   <li class="nav-header">Non CGPA</li>
      <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-tree"></i>
          <p>
            Group 1
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>General</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Icons</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Buttons</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Sliders</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Modals & Alerts</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Navbar & Tabs</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Timeline</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Ribbons</p>
            </a>
          </li>
        </ul>
      </li> -->
<!--############################# End Group 1 ###################################-->

<!--############################# Group 2 ###################################-->
 <!--     <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-edit"></i>
          <p>
            Group 2
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>General Elements</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Advanced Elements</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Editors</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Validation</p>
            </a>
          </li>
        </ul>
      </li> -->
<!--############################# End Group 2 ###################################-->


<!--############################# Group 3 ###################################-->
<!--    <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-table"></i>
          <p>
            Group 3
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Simple Tables</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>DataTables</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>jsGrid</p>
            </a>
          </li>
        </ul>
      </li> -->
<!--############################# End Group 3 ###################################-->

<!--###################### End Non CGPA #################################-->

<!--Logout-->
     
<!--<div class="d-flex flex-column" style="height: 180px;">
  <button type="button" class="btn btn-danger mt-auto">Log out</button>
</div> -->
<li class="nav-item">
  <a href="update_password.php" class="nav-link">
    <i class="nav-icon fa fa-inbox aria-hidden="true></i>
    <p>
      Change Password
    </p>
  </a>

</li>
<form action="logout.php" method="post">
<div class="d-flex flex-column">
    <button type="submit" class="btn btn-danger">Log out</button>
</div>
</form>

<!--Logout-->
  </nav>


  <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>
<!--   ##############################3 End of common ########################-->







<!--   ##############################3 End of common ########################-->







