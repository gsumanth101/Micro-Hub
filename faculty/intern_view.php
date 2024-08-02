 <?php 
 include('sidebar.php');
 if (isset($_SESSION["username"])) {
    $team = $_GET['team'];
    $username = $_SESSION["username"];
    $mentorName = $userData['name'];

    $sql = "SELECT * FROM team_assignments where team_id = '$team'";
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);
    $team_name = $row['team_name'];
    $problem_id = $row['problem_id'];
    $mentor_name = $row['mentor_name'];
    $team_id = $row['team_id'];

    if(mysqli_num_rows($result) == 0){
      echo '
        <div class="text-center"><br><br>
          <img src="dist\img\cartoon.jpeg" class="card-img-top" alt="Team Member" style="width: 300px; height: 400px;">
        <h6 class="text" style="color:red">You are not Registered</h6>
      </div><br><br><br><br>';
      include('footer.html');
      exit();
    }
    $team = "SELECT * FROM internship_teams WHERE team_id = '$team_id'";
    $team_result = $conn->query($team);
    $team_row = mysqli_fetch_assoc($team_result);
    $team_name = $team_row['team_name'];
}

 ?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Project Detail</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Phenosoft Internship</a></li>
              <li class="breadcrumb-item active">Project Detail</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header bg-orange">
          <h3 class="card-title">Projects Detail</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
              <div class="row">
                <div class="col-12 col-sm-4">
                  <div class="info-box bg-light">
                    <div class="info-box-content">
                      <span class="info-box-text text-center text-muted">Team ID</span>
                      <span class="info-box-number text-center text-muted mb-0"><?php echo $team_id ?></span>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-4">
                  <div class="info-box bg-light">
                    <div class="info-box-content">
                      <span class="info-box-text text-center text-muted">Problem ID</span>
                      <span class="info-box-number text-center text-muted mb-0"><?php echo $problem_id ?></span>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-4">
                  <div class="info-box bg-light">
                    <div class="info-box-content">
                      <span class="info-box-text text-center text-muted">Team Name</span>
                      <span class="info-box-number text-center text-muted mb-0"><?php echo $team_name ?></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <h4>Details</h4>
                    <div class="post">
                      <div class="user-block">
                        <span class="username">
                          <a href="#"></a>
                        </span>
                        <!-- <span class="description">Shared publicly - 7:45 PM today</span> -->
                      </div>

                      <h5><strong>Problem Statement:</strong></h5>
                      <p>
                        <?php
                          $sql = "SELECT * FROM problem_statement WHERE problem_id = '$problem_id'";
                          $result = $conn->query($sql);
                          $row = mysqli_fetch_assoc($result);
                          echo $row['problem_statement'];
                        ?>
                      </p>

                      <!-- <p>
                        <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 1 v2</a>
                      </p> -->
                    </div>


                </div>
              </div>
            </div>
            <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
              <h3 class="text-primary"><i class="fas fa-users"></i> Team Details</h3>
              <p class="text-muted"></p>
            <h6><?php echo $team_row['mem_name1'] ?>(Team Lead)</h6>
            <h6><?php echo $team_row['mem_name2'] ?></h6>
            <h6><?php echo $team_row['mem_name3'] ?></h6>
            <h6><?php echo $team_row['mem_name4'] ?></h6>
            <h6><?php echo $team_row['mem_name5'] ?></h6>
              <br>
              <!-- <div class="text-muted">
                <p class="text-sm">Client Company
                  <b class="d-block">Deveint Inc</b>
                </p>
                <p class="text-sm">Project Leader
                  <b class="d-block">Tony Chicken</b>
                </p>
              </div> -->

              <!-- <h5 class="mt-5 text-muted">Project files</h5>
              <ul class="list-unstyled">
                <li>
                  <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-word"></i> Functional-requirements.docx</a>
                </li>
                <li>
                  <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-pdf"></i> UAT.pdf</a>
                </li>
                <li>
                  <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-envelope"></i> Email-from-flatbal.mln</a>
                </li>
                <li>
                  <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-image "></i> Logo.png</a>
                </li>
                <li>
                  <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-word"></i> Contract-10_12_2014.docx</a>
                </li>
              </ul> -->
              <!-- <div class="text-center mt-5 mb-3">
                <a href="#" class="btn btn-sm btn-primary">Add files</a>
                <a href="#" class="btn btn-sm btn-warning">Report contact</a>
              </div> -->
            </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php 
include('footer.html');
?>