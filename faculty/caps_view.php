<?php
include('sidebar.php');

if (isset($_SESSION["username"])) {
    $team = $_GET['team'];
    $username = $_SESSION["username"];
    $mentorName = $userData['name'];

    $sql = "SELECT * FROM caps_teams WHERE team_id = '$team'";
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);
    $team_name = $row['team_name'];
    $mentor_name = $row['guide_name'];
    $team_id = $row['team_id'];

    if (mysqli_num_rows($result) == 0) {
        echo '
        <div class="text-center"><br><br>
          <img src="dist\img\cartoon.jpeg" class="card-img-top" alt="Team Member" style="width: 300px; height: 400px;">
        <h6 class="text" style="color:red">You are not Registered</h6>
      </div><br><br><br><br>';
        include('footer.html');
        exit();
    }
    $team = "SELECT * FROM caps_teams WHERE team_id = '$team_id'";
    $team_result = $conn->query($team);
    $team_row = mysqli_fetch_assoc($team_result);
    $team_name = $team_row['team_name'];

    $ps = "SELECT * FROM caps_problems WHERE team_id = '$team_id'";
    $team_ps = $conn->query($ps);
    $team_ps_row = mysqli_fetch_assoc($team_ps);
    $area_research = $team_ps_row['area_research'];
    $project_title = $team_ps_row['problem_statement'];
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Team Detail</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function removeTeam(teamId) {
            $.ajax({
                url: 'remove_guide.php',
                type: 'POST',
                data: {
                    team_id: teamId
                },
                success: function(response) {
                    if (response == 'success') {
                        alert('Guide removed successfully.');
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Failed to remove guide: ' + response);
                    }
                },
                error: function(xhr, status, error) {
                    alert('AJAX Error: ' + error);
                }
            });
        }
    </script>
</head>

<body>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Team Detail</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Capstone Project</a></li>
                            <li class="breadcrumb-item active">Team Detail</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card">
                <div class="card-header bg-orange">
                    <h3 class="card-title">Team Detail</h3>
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
                                <!-- <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Problem ID</span>
                                        <span class="info-box-number text-center text-muted mb-0"><?php echo $mentor_name ?></span>
                                    </div>
                                </div>
                            </div> -->
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
                                    <div class="post">
                                        <div class="user-block">
                                            <span class="username">
                                                <a href="#"></a>
                                            </span><br>
                                            <h3 class="text-primary"><i class="fas fa-users"></i> Team Details</h3>
                                            <p class="text-muted"></p>
                                            <table class="table">
                                                <tbody>
                                                    <?php if (!empty($team_row["mem_name1"]) && !empty($team_row["mem_regd1"])) : ?>
                                                        <tr>
                                                            <td><?php echo $team_row["mem_name1"]; ?></td>
                                                            <td><?php echo $team_row["mem_regd1"]; ?></td>
                                                        </tr>
                                                    <?php endif; ?>
                                                    <?php if (!empty($team_row["mem_name2"]) && !empty($team_row["mem_regd2"])) : ?>
                                                        <tr>
                                                            <td><?php echo $team_row["mem_name2"]; ?></td>
                                                            <td><?php echo $team_row["mem_regd2"]; ?></td>
                                                        </tr>
                                                    <?php endif; ?>
                                                    <?php if (!empty($team_row["mem_name3"]) && !empty($team_row["mem_regd3"])) : ?>
                                                        <tr>
                                                            <td><?php echo $team_row["mem_name3"]; ?></td>
                                                            <td><?php echo $team_row["mem_regd3"]; ?></td>
                                                        </tr>
                                                    <?php endif; ?>
                                                    <?php if (!empty($team_row["mem_name4"]) && !empty($team_row["mem_regd4"])) : ?>
                                                        <tr>
                                                            <td><?php echo $team_row["mem_name4"]; ?></td>
                                                            <td><?php echo $team_row["mem_regd4"]; ?></td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="user-block">
                                            <h3 class="text-primary"><i class="fas fa-project-diagram"></i> Project Details</h3>
                                            <table class="table">
                                                <tbody>
                                                    <?php if (!empty($area_research) && !empty($project_title)) : ?>
                                                        <tr>
                                                            <td><strong>Area of Research:</strong></td>
                                                            <td><?php echo $area_research; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Project Title:</strong></td>
                                                            <td><?php echo $project_title; ?></td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#removeModal">Remove</button> -->

                                        <!-- Remove Modal -->
                                        <div class="modal fade" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="removeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="removeModalLabel">Remove Team</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to remove this team?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-danger" onclick="removeTeam('<?php echo $team_id ?>')">Confirm</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include('footer.html'); ?>

</body>

</html>