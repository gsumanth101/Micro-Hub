<?php
include('sidebar.php');
include('db_connection.php'); // Assuming you have a file to connect to the database

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
  echo '<script>alert("You must be logged in to view this page.")</script>';
  exit();
}

$username = $_SESSION["username"];

// Check if the team ID is provided
if (!isset($_GET['team']) && !isset($_POST['team_id'])) {
  echo '<script>alert("Invalid team")</script>';
  exit();
}

$team = isset($_GET['team']) ? $_GET['team'] : $_POST['team_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Handle form submission to save attendance
  $team_id = $_POST['team_id'];
  $attendance = [];
  
  for ($i = 1; $i <= 5; $i++) {
    if (isset($_POST["mem_att$i"])) {
      $attendance[] = $_POST["mem_att$i"];
    }
  }
  
  // Delete previous attendance records for the team
  $delete_sql = "DELETE FROM attendance WHERE team_id = '$team_id'";
  $conn->query($delete_sql);
  
  // Insert new attendance records
  foreach ($attendance as $member_name) {
    $insert_sql = "INSERT INTO attendance (team_id, member_name) VALUES ('$team_id', '$member_name')";
    if (!$conn->query($insert_sql)) {
      echo '<script>alert("Failed to save attendance: ' . $conn->error . '")</script>';
      exit();
    }
  }

  echo '<script>alert("Attendance saved successfully.")</script>';
}

// Fetch team assignment details
$sql = "SELECT * FROM team_assignments WHERE team_id = '$team'";
$result = $conn->query($sql);

if (!$result || mysqli_num_rows($result) == 0) {
  echo '
    <div class="text-center"><br><br>
      <img src="dist\img\cartoon.jpeg" class="card-img-top" alt="Team Member" style="width: 300px; height: 400px;">
      <h6 class="text" style="color:red">You are not Registered</h6>
    </div><br><br><br><br>';
  include('footer.html');
  exit();
}

$assignment = mysqli_fetch_assoc($result);
$team_id = $assignment['team_id'];
$team_name = $assignment['team_name'];
$problem_id = $assignment['problem_id'];
$mentor_name = $assignment['mentor_name'];
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
    </div>
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
                  </div>

                  <h5><strong>Problem Statement:</strong></h5>
                  <p>
                    <?php
                      $sql = "SELECT * FROM problem_statement WHERE problem_id = '$problem_id'";
                      $result = $conn->query($sql);
                      if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        echo $row['problem_statement'];
                      } else {
                        echo '<script>alert("Failed to retrieve problem statement: ' . $conn->error . '")</script>';
                      }
                    ?>
                  </p>
                  <div class="row">
                    <div class="col-12">
                      <h5><strong>Team Members:</strong></h5>
                      <ul>
                        <form action="" method="post">
                          <input type="hidden" name="team_id" value="<?php echo $team_id; ?>">
                          <div class="table-responsive">
                            <table class="table table-bordered">
                              <thead>
                                <tr>
                                  <th>Regd. No</th>
                                  <th>Member Name</th>
                                  <th>Attendance</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                  $sql = "SELECT * FROM internship_teams WHERE team_id = '$team_id'";
                                  $result = $conn->query($sql);
                                  if ($result) {
                                    $team = mysqli_fetch_assoc($result);
                                    for ($i = 1; $i <= 5; $i++) {
                                      $mem_reg = $team["mem_reg$i"];
                                      $mem_name = $team["mem_name$i"];
                                      if (!empty($mem_reg) && !empty($mem_name)) {
                                        echo '<tr>';
                                        echo '<td>' . $mem_reg . '</td>';
                                        echo '<td>' . $mem_name . '</td>';
                                        echo '<td><input type="checkbox" name="mem_att' . $i . '" value="' . $mem_name . '"></td>';
                                        echo '</tr>';
                                      }
                                    }
                                  } else {
                                    echo '<script>alert("Failed to retrieve team members: ' . $conn->error . '")</script>';
                                  }
                                ?>
                              </tbody>
                            </table>
                            <button type="submit" name="submit">Submit</button>
                          </div>
                        </form>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
