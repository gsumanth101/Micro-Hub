<?php
include 'sidebar.php';
$username = $_SESSION['username'];

$sql = "SELECT * FROM caps_teams WHERE mem_regd1='$username'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$row = mysqli_fetch_array($result);
$team_name = $row['team_name'];
$team_id = $row['team_id'];
$guide_username = $row['guide_username'];

if (isset($_POST['submit'])) {
    $area_research = $_POST['area_research'];
    $problem_statement = $_POST['problem_statement'];

    // Sanitize user inputs
    $area_research = mysqli_real_escape_string($conn, $_POST['area_research']);
    $problem_statement = mysqli_real_escape_string($conn, $_POST['problem_statement']);

    // Prepare and bind the insert statement
    $insert_query = "INSERT INTO caps_problems (team_id, team_name,guide_username, area_research, problem_statement) VALUES (?, ?, ?, ?, ?)";
    $insert_statement = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($insert_statement, "sssss", $team_id, $team_name,$guide_username, $area_research, $problem_statement);

    // Execute the insert statement
    $insert_result = mysqli_stmt_execute($insert_statement);

    if (!$insert_result) {
        die("Insert query failed: " . mysqli_error($conn));
    }
    if ($insert_result) {
        echo "<script>alert('Problem statement submitted successfully!');</script>";
        echo "<script>window.location.href = 'caps_team.php';</script>";
    }
}
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
                        <li class="breadcrumb-item"><a href="#">Captone Project</a></li>
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
                    <form action="caps_problems.php" method="post">
                        <div class="form-group">
                            <label for="team_id">Team ID:</label>
                            <input type="text" class="form-control" name="team_id" id="team_id" value="<?php echo $team_id; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="team_name">Team Name:</label>
                            <input type="text" class="form-control" name="team_name" id="team_name" value="<?php echo $team_name; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="area_research">Area of Research</label>
                            <input type="text" name="area_research" class="form-control" id="area_research" required></i>
                        </div>
                        <div class="form-group">
                            <label for="problem_statement">Project <Title></Title></label>
                            <textarea type="text" name="problem_statement" id="problem_statement" class="form-control" required></textarea>
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