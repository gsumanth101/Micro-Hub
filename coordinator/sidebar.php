<?php


// Include the database connection
include('../includes/connection.php');

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: coordinatorlogin.php");
}

// Get the username from the session
$username = $_SESSION['username'];

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM coordinator WHERE username = ?");
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
  <title>Co-Ordinator | Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css">
  <link rel="apple-touch-icon" sizes="180x180" href="../assets\images\apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../assets\images\android-chrome-512x512.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../assets\images\android-chrome-192x192.png">
</head>
<!--   ##############################  common ########################-->
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
        <a href="#" class="nav-link">Home</a>
      </li>
     <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li> -->
    </ul>
  
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="../assets\images\logo1.png" alt="MicroHub" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">MicroHub</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../dist/img/user2.png" class="img-circle elevation-2" alt="User Image">
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

   <!--############################ DashBoard #########################-->
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
            <a href="dashboard.php" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="coordinator_dashboard.php" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Co-Ordinator Details</p>
            </a>
          </li>
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
            <a href="upload_tools.php" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Add tools</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="manage_tools.php" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>manage tools</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="micro_regd.php" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Registrations</p>
            </a>
          </li>
         
          <li class="nav-item">
          <a href="#" class="nav-link">
          <i class="nav-icon aria-hidden="true"></i>
          <p>
            Project Submissions
            <i class="right fas fa-angle-left"></i>
            <span class="right badge badge-danger"></span>
          </p>
        </a>
       
          <ul class="nav nav-treeview">
           <li class="nav-item">
            <a href="micro_projectSubmit.php" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>All Submissions</p>
            </a>
          </li> 
          <li class="nav-item">
            <a href="micro_accepted.php" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Accepted</p>
            </a>
          </li> 
          <li class="nav-item">
            <a href="micro_rejected.php" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Rejected</p>
            </a>
          </li> 
          </ul>
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
              <p>Registrations</p>
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
      <!--<li class="nav-item">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-chart-pie"></i>
          <p>
            Hackathon
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="upload_external_events.php" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Add External Events</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="manage_external_events.php" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>manage External Events</p>
            </a>
          </li>
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

<!--###################### Internship #################################-->
<li class="nav-item">
  <a href="intern_teams.php" class="nav-link">
    <i class="nav-icon fa fa-inbox"></i>
    <p>
      Phenosoft Internship
    </p>
  </a>

</li>
<!--###################### Internship #################################-->

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
            <a href="../UI/general.html" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>General</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../UI/icons.html" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Icons</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../UI/buttons.html" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Buttons</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../UI/sliders.html" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Sliders</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../UI/modals.html" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Modals & Alerts</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../UI/navbar.html" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Navbar & Tabs</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../UI/timeline.html" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Timeline</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../UI/ribbons.html" class="nav-link">
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
            <a href="../forms/general.html" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>General Elements</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../forms/advanced.html" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Advanced Elements</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../forms/editors.html" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Editors</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../forms/validation.html" class="nav-link">
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
            <a href="../tables/simple.html" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>Simple Tables</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../tables/data.html" class="nav-link">
              <i class="fa fa-light fa-caret-right nav-icon"></i>
              <p>DataTables</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../tables/jsgrid.html" class="nav-link">
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
    <i class="nav-icon fa fa-inbox aria-hidden="true"></i>
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



