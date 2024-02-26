<?php
include "micro_sidebar.php";
$name = $userData['name'];
// Get count from Coordinator table
$micro_regdCount = 0;
$sqlregd = "SELECT COUNT(*) as count FROM micro_project where mentor_name='$name'";
$resultregd = $conn->query($sqlregd);
if ($resultregd && $resultregd->num_rows > 0) {
    $row = $resultregd->fetch_assoc();
    $micro_regdCount = $row['count'];
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
                <!-- Coordinator Details Box -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo $micro_regdCount; ?></h3>
                            <p>Micro Project Registrations</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person"></i>
                        </div>
                        <a href="project_submissions.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

             <div class="col-lg-3 col-6">
                    <!-- Faculty Details Box -->
           <!--            <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo $micro_projectCount; ?></h3>
                            <p>Project Submissions</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-university"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?php echo $studentCount; ?></h3>
                        <p>Stu</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-stalker"></i>
                    </div>
                    <a href="student.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>-->

                <!-- Add more small boxes for other statistics if needed -->

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
