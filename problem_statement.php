<?php
include('sidebar.php');


$username = $_SESSION['username'];

// Fetch the team information
$sql = "SELECT * FROM internship_teams WHERE mem_reg1='$username'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo '
    <div class="text-center"><br><br>
      <img src="dist/img/cartoon.jpeg" class="card-img-top" alt="Team Member" style="width: 300px; height: 400px;">
      <h6 class="text" style="color:red">You are not Team Lead</h6>
    </div><br><br><br><br>';
    include('footer.html');
    exit();
}

$row = mysqli_fetch_assoc($result);
$team_name = $row['team_name'];
$team_id = $row['team_id'];

// Handle form submission
if (isset($_POST['submit'])) {
    $problem_id = $_POST['problem_id'];

    // Check if the team already has an assignment
    $sql = "SELECT * FROM team_assignments WHERE team_id='$team_id'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo '<div class="text-center"><br><br>
        <img src="dist/img/cartoon.jpeg" class="card-img-top" alt="Team Member" style="width: 300px; height: 400px;">
        <h6 class="text" style="color:red">Team is already assigned a problem</h6>
        </div><br><br><br><br>';
        include('footer.html');
        exit();
    }

    // Check and decrement the global limit
    $sql = "SELECT global_limit FROM problem_statement WHERE problem_id='$problem_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if ($row['global_limit'] > 0) {


        // Assign the problem to the team
        $sql = "INSERT INTO team_assignments (team_id, team_name, problem_id) VALUES ('" . mysqli_real_escape_string($conn, $team_id) . "', '" . mysqli_real_escape_string($conn, $team_name) . "', '" . mysqli_real_escape_string($conn, $problem_id) . "')";

        if (mysqli_query($conn, $sql)) {
            // Decrement the global limit
            $sql = "UPDATE problem_statement SET global_limit = global_limit - 1 WHERE problem_id='$problem_id'";
            mysqli_query($conn, $sql);
            echo '<script>alert("Team Assignment Added Successfully")</script>';
            echo '<script>window.location.href = "intern_team.php";</script>';
            exit();
        } else {
            echo '<script>alert("Error in Adding Team Assignment: ' . mysqli_error($conn) . '")</script>';
        }
    } else {
        echo '<script>alert("This problem statement has reached its limit")</script>';
    }
}

// Get problem statements that haven't exceeded their individual global limit
$sql = "SELECT * FROM problem_statement WHERE global_limit > 0";
$result = mysqli_query($conn, $sql);

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Problem Statements</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Phenosoft Internship</a></li>
                        <li class="breadcrumb-item active">Problem Statements</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Select Problem Statements</h3>
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <form action="problem_statement.php" method="post">
                        <div class="form-group">
                            <label for="team_id">Team ID:</label>
                            <input type="text" class="form-control" name="team_id" id="team_id" value="<?php echo $team_id; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="team_name">Team Name:</label>
                            <input type="text" class="form-control" name="team_name" id="team_name" value="<?php echo $team_name; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="problem_id">Problem Statement</label>
                            <select class="form-control" id="problem_id" name="problem_id">
                                <?php
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $problem_id = $row['problem_id'];
                                    $problem_statement = $row['problem_statement'];
                                    echo "<option value='$problem_id'>$problem_id - $problem_statement({$row['global_limit']} left)</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                        </div>
                    </form>
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
