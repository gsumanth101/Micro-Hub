<?php
include('sidebar.php');


$sql = "SELECT * FROM caps_teams WHERE mem_regd1='$username' OR mem_regd1='$username' OR mem_regd2='$username' OR mem_regd3='$username' OR mem_regd4='$username' ";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$team_name = $row['team_name'];
$guide_username = $row['guide_username'];
$team_id = $row['team_id'];


if (!empty($guide_username)) {
    echo "<script>window.location.href = 'caps_team.php';</script>";
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $team_id = $_POST['team_id'];
        $guide_username = $_POST['guide_username'];

        // Get the guide_name from the faculty table
        $sql = "SELECT name FROM faculty WHERE username='$guide_username'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $guide_name = $row['name'];

        // Update the caps_teams table
        $sql = "UPDATE caps_teams SET guide_username='$guide_username', guide_name='$guide_name' WHERE team_id='$team_id'";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Guide Selected successfully'); window.location.href = 'caps_team.php';</script>";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
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
                    <h1>Guide</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Capston Project</a></li>
                        <li class="breadcrumb-item active">Guide</li>
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
                    <h3 class="card-title">Select Guide</h3>
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <form action="caps_guide.php" method="post">
                        <div class="form-group">
                            <label for="team_id">Team ID:</label>
                            <input type="text" class="form-control" name="team_id" id="team_id" value="<?php echo $team_id?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="team_name">Team Name:</label>
                            <input type="text" class="form-control" name="team_name" id="team_name" value="<?php echo $team_name?>" readonly>
                        </div>


                        <div class="form-group">
                            <label for="guide_username">Guides</label>
                            <select class="form-control" name="guide_username" id="guide_username">
                                <?php
                                $sql = "SELECT faculty.username, faculty.name, COUNT(caps_teams.guide_username) as team_count
                                        FROM faculty
                                        LEFT JOIN caps_teams ON faculty.username = caps_teams.guide_username
                                        GROUP BY faculty.username
                                        HAVING team_count < 4";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . $row['username'] . "'>" . $row['name'] . "-  Available slots:  " . (4 - $row['team_count']) . "</option>";
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
