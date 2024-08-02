<?php
include('sidebar.php');
$username = $_SESSION['username'];
$sql = "SELECT * FROM caps_teams WHERE mem_regd1='$username' OR mem_regd1='$username' OR mem_regd2='$username' OR mem_regd3='$username' OR mem_regd4='$username' ";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$team_name = $row['team_name'];
$team_id = $row['team_id'];
$guide_username = $row['guide_username'];

$sql1 = "SELECT * FROM caps_problems WHERE team_id='$team_id'";
$result1 = mysqli_query($conn, $sql1);
$row1 = mysqli_fetch_assoc($result1);

$problem_statement = isset($row1['problem_statement']) ? $row1['problem_statement'] : "";
$area_research = isset($row1['area_research']) ? $row1['area_research'] : "";
if(mysqli_num_rows($result) == 0){
  
  // Get the section of the user
  $sql2 = "SELECT section FROM student WHERE username='$username'";
  $result2 = mysqli_query($conn, $sql2);
  $row2 = mysqli_fetch_assoc($result2);
  $section = $row2['section'];

  echo '<div class="text-center"><br><br>
      <img src="dist\img\cartoon.jpeg" class="card-img-top" alt="Team Member" style="width: 300px; height: 400px;">
    <h6 class="text" style="color:red">You are not Registered</h6>';

  $valid_sections = ['S1', 'S2', 'S3', 'S4', 'S5', 'S6', 'S7', 'S8', 'S9', 'S10', 'S11', 'S12', 'S13', 'S14', 'S15'];
  if (in_array($section, $valid_sections)) {
    echo '<p>Meet the Co-Ordinator to register Capstone Project</p>';
  } else {
    echo '<p>Do the Capstone Project in next Semester</p>';
  }

  echo '</div><br><br><br><br>';
  include('footer.html');
  exit();
}
$guide_name = "";
$sql1 = "SELECT name FROM faculty WHERE username='$guide_username'";
$result1 = mysqli_query($conn, $sql1);
$row1 = mysqli_fetch_assoc($result1);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Capstone Project</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Capstone Project</a></li>
              <li class="breadcrumb-item active">Team Details</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">

            <?php
            if(empty($guide_username)){
                echo '
                          <div class="col-12">
            <div class="callout callout-info bg-warning" >
                    <h5><i class="fas fa-info"></i> Note:</h5>
                    You have not choosen a guide yet. Please choose your guide.
                ';
            }
            ?>
            </div>


            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fas fa-users"></i> Team Details
                    <small class="float-right"><?php echo $row["team_id"] ?></small>
                  </h4>
                </div>
                <!-- /.col -->
              </div><br>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    <h6><strong>Team Id</strong>:- <?php echo $row["team_id"] ?></h6>
                    <h6><strong>Team Name</strong>:- <?php echo $row["team_name"] ?></h6>
                    <?php
                    if(empty($row['guide_name'])){
                        echo '<h6 style="color:red;"><strong>Guide Name</strong>:- Not chosen yet</h6>';
                    } else {
                        echo '<h6><strong>Guide Name</strong>:- '.$row1['name'].'</h6>';
                    }

                    if(empty($area_research)){
                        echo '<h6 style="color:red;"><strong>Area of Research</strong>:- Not chosen yet</h6>';
                  } else {
                      echo '<h6><strong>Area of Research</strong>:- '.$area_research.'</h6>';
                      echo '<h6><strong>Project Title</strong>:- '.$problem_statement .'</h6>';
                  }
                    ?>

                    <br>
                </div><hr>
                <!-- /.col -->
                <!-- <div class="col-md-4 invoice-col">
                  To
                  <address>
                  <div class="col-sm-4 invoice-col">
                    <strong>Team Name</strong><br>
                    <br>
                </div>
                </div>
                <div class="col-sm-4 invoice-col">
                  <b>Invoice #007612</b><br>
                  <br>
                  <b>Order ID:</b> 4F3S8J<br>
                  <b>Payment Due:</b> 2/22/2014<br>
                  <b>Account:</b> 968-34567
                </div> -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead style="font-family:Arial, Helvetica, sans-serif">
                    <tr>
                      <th>S.no</th>
                      <th>Name</th>
                      <th>Regd N0.</th>
                      <th>Role</th>
                    </tr>
                    </thead>
                    <tbody style="font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif">
                    <tr>
                      <?php if(!empty($row["mem_name1"])): ?>
                      <tr>
                        <td>1</td>
                        <td><?php echo $row["mem_name1"] ?></td>
                        <td><?php echo $row["mem_regd1"] ?></td>
                        <td>Team Lead</td>
                      </tr>
                      <?php endif; ?>

                      <?php if(!empty($row["mem_name2"])): ?>
                      <tr>
                        <td>2</td>
                        <td><?php echo $row["mem_name2"] ?></td>
                        <td><?php echo $row["mem_regd2"] ?></td>
                        <td>Member</td>
                      </tr>
                      <?php endif; ?>

                      <?php if(!empty($row["mem_name3"])): ?>
                      <tr>
                        <td>3</td>
                        <td><?php echo $row["mem_name3"] ?></td>
                        <td><?php echo $row["mem_regd3"] ?></td>
                        <td>Member</td>
                      </tr>
                      <?php endif; ?>

                      <?php if(!empty($row["mem_name4"])): ?>
                      <tr>
                        <td>4</td>
                        <td><?php echo $row["mem_name4"] ?></td>
                        <td><?php echo $row["mem_regd4"] ?></td>
                        <td>Member</td>
                      </tr>
                      <?php endif; ?>
                  </table>
                </div>
                <!-- /.col -->
              </div><br>
              <!-- /.row -->
              <?php if((empty($problem_statement) || empty($area_research)) && $row['mem_regd1'] == $username): ?>
                <button type="button" class="btn btn-primary" onclick="window.location.href='caps_problems.php'">Choose Problem</button>
              <?php endif; ?>
               


              <!-- this row will not appear when printing -->
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<?php
include "footer.html";
?>