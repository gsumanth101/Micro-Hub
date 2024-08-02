 <!-- NEW -->
 <?php
include('sidebar.php');
$username = $_SESSION['username'];
$sql = "SELECT * FROM internship_teams WHERE mem_reg1='$username' OR mem_reg1='$username' OR mem_reg2='$username' OR mem_reg3='$username' OR mem_reg4='$username' OR mem_reg5='$username' ";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$team_name = $row['team_name'];
if(mysqli_num_rows($result) == 0){
  echo '
    <div class="text-center"><br><br>
      <img src="dist\img\cartoon.jpeg" class="card-img-top" alt="Team Member" style="width: 300px; height: 400px;">
    <h6 class="text" style="color:red">You are not Registered</h6>
  </div><br><br><br><br>';
  include('footer.html');
  exit();
}
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Team Details</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Phenosoft Internship</a></li>
              <li class="breadcrumb-item active">Team Details</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card card-primary card-outline">
          <div class="card-header bg-orange">
            <h3 class="card-title">Team ID:- <?php echo $row['team_id'] ?></h3>
          </div> <!-- /.card-body -->
          <div class="card-body">
            <h4><?php echo $row['team_name'] ?></h4><br>

            <strong>Team Members:- </strong>

            <h6><?php echo $row['mem_name1'], "   -   ", $row['mem_reg1'] ?>(Team Lead)</h6>
            <h6><?php echo $row['mem_name2'], "  -   ", $row['mem_reg2'] ?></h6>
            <h6><?php echo $row['mem_name3'], "  -  ", $row['mem_reg3'] ?></h6>
            <h6><?php echo $row['mem_name4'], "  -  ", $row['mem_reg4'] ?></h6>
            <h6><?php echo $row['mem_name5'], "  -  ", $row['mem_reg5'] ?></h6><br>

            <strong>Selected Mentor & Problem</strong>
            <?php
            $sql = "SELECT * FROM team_assignments WHERE team_id = '{$row['team_id']}'";
            $result = mysqli_query($conn, $sql);
            $row1 = mysqli_fetch_assoc($result);
            echo "<h6>Problem Id:- {$row1['problem_id']}</h6>";
            echo "<h6>Mentor Name:- {$row1['mentor_name']}</h6>";

            ?>

            <strong>Action</strong>
            <div>
              <p><em>Only Team Leader can choose the problem statement</em></p>
              <a href="problem_statement.php">Problem Statements</a><br>
              <!-- <a href="https://useiconic.com/open/">Iconic Icons</a><br>
              <a href="https://ionicons.com/">Ion Icons</a><br> -->
            </div>
          </div><!-- /.card-body -->
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php 
  include('footer.html');
  ?>