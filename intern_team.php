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

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    .team-card {
  transition: transform 0.3s, box-shadow 0.3s;
}

.team-card:hover {
  transform: scale(1.05);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.team-card .card-body {
  transition: background-color 0.3s;
}

.team-card:hover .card-body {
  background-color: #f0f0f0;
}

</style>
  <div class="container mt-5 mx-auto my-5">
    <div class="row justify-content-center">
      <div class="col-md-4 col-3">
        <div class="card team-card">
          <img src="dist\img\team.png" class="card-img-top" alt="Team Member" height="40%" width="60%">
          <div class="card-body">
            <a href="problem_statement.php" type="button" style="text-decoration: none; color: inherit;">
              <h5 class="card-title"><strong><?php echo $team_name ?></strong></h5>
              <p class="card-text">Team Members</p><hr>
              <small> <?php echo $row["mem_name1"] ," - ", $row["mem_reg1"] ?></small><br>
              <small><?php echo $row["mem_name2"] ," - ", $row["mem_reg2"] ?></small><br>
              <small><?php echo $row["mem_name3"] ," - ", $row["mem_reg3"] ?></small><br>
              <small><?php echo $row["mem_name4"] ," - ", $row["mem_reg4"] ?></small><br>
              <small><?php echo $row["mem_name5"] ," - ", $row["mem_reg5"] ?></small><br>
            </a>
          </div>
          <div class="card-footer">
            <small class="text-muted"><?php echo "<strong>Team ID: </strong>",$row["team_id"] ?></small>
          </div>
        </div>
      </div>
      <!-- Repeat for more team members -->
    </div>
  </div>
  
  <!-- Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php
include('footer.html');
?>



