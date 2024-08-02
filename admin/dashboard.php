<?php
include "sidebar.php";

$adminCount = 0;
$sqlAdmin = "SELECT COUNT(*) as count FROM admin";
$resultAdmin = $conn->query($sqlAdmin);
if ($resultAdmin && $resultAdmin->num_rows > 0) {
    $row = $resultAdmin->fetch_assoc();
    $adminCount = $row['count'];
}

// Get count from Coordinator table
$coordinatorCount = 0;
$sqlCoordinator = "SELECT COUNT(*) as count FROM coordinator";
$resultCoordinator = $conn->query($sqlCoordinator);
if ($resultCoordinator && $resultCoordinator->num_rows > 0) {
    $row = $resultCoordinator->fetch_assoc();
    $coordinatorCount = $row['count'];
}

// Get count from Faculty table
$facultyCount = 0;
$sqlFaculty = "SELECT COUNT(*) as count FROM faculty";
$resultFaculty = $conn->query($sqlFaculty);
if ($resultFaculty && $resultFaculty->num_rows > 0) {
    $row = $resultFaculty->fetch_assoc();
    $facultyCount = $row['count'];
}

// Get count from Student table
$studentCount = 0;
$sqlStudent = "SELECT COUNT(*) as count FROM student";
$resultStudent = $conn->query($sqlStudent);
if ($resultStudent && $resultStudent->num_rows > 0) {
    $row = $resultStudent->fetch_assoc();
    $studentCount = $row['count'];
}

$microCount = 0;
$sqlMicro = "SELECT COUNT(*) as count FROM micro_project";
$resultMicro = $conn->query($sqlMicro);
if ($resultMicro && $resultMicro->num_rows > 0) {
    $row = $resultMicro->fetch_assoc();
    $microCount = $row['count'];
}

$micro_regd_Count = 0;
$sqlMicro_regd = "SELECT COUNT(*) as count FROM micro_project_registration";
$resultMicro_regd = $conn->query($sqlMicro_regd);
if ($resultMicro_regd && $resultMicro_regd->num_rows > 0) {
    $row = $resultMicro_regd->fetch_assoc();
    $micro_regd_Count = $row['count'];
}

$Intern_pending = 0;
$sqlIntern_regd = "SELECT COUNT(*) as count FROM team_assignments;";
$resultIntern_regd = $conn->query($sqlIntern_regd);
if ($resultIntern_regd && $resultIntern_regd->num_rows > 0) {
    $row = $resultIntern_regd->fetch_assoc();
    $Intern_pending = $row['count'];
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        
        <div class="row">
        <div class="col-lg-3 col-6">
                <!-- Coordinator Details Box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <!-- Replace '150' with the actual count of Coordinator records -->
                        <h3><?php echo $adminCount; ?></h3>
                        <p>Admins</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person"></i>
                    </div>
                    <a href="admin.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <!-- Coordinator Details Box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <!-- Replace '150' with the actual count of Coordinator records -->
                        <h3><?php echo $coordinatorCount; ?></h3>
                        <p>Coordinators</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person"></i>
                    </div>
                    <a href="coordinator.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <!-- Faculty Details Box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <!-- Replace '53' with the actual count of Faculty records -->
                        <h3><?php echo $facultyCount; ?></h3>
                        <p>Faculty</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-university"></i>
                    </div>
                    <a href="faculty.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <!-- Student Details Box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <!-- Replace '44' with the actual count of Student records -->
                        <h3><?php echo $studentCount; ?></h3>
                        <p>Students</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-stalker"></i>
                    </div>
                    <a href="student.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- Micro Details Box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <!-- Replace '150' with the actual count of Coordinator records -->
                        <h3><?php echo $micro_regd_Count; ?></h3>
                        <p>Micro Project Registrations</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person"></i>
                    </div>
                    <a href="micro_regd.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- Micro Details Box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <!-- Replace '150' with the actual count of Coordinator records -->
                        <h3><?php echo $microCount; ?></h3>
                        <p>Micro Project Submissions</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- Add more small boxes for other statistics if needed -->
            <div class="col-lg-3 col-6">
                <!-- Faculty Details Box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <!-- Replace '53' with the actual count of Faculty records -->
                        <h3><?php echo $Intern_pending; ?></h3>
                        <p>Internship</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-university"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
</div>
<!-- /.content -->

<?php
include "footer.html"
?>
