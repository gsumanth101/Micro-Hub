<?php
include "sidebar.php";

$username = $userData['username'];
$status = '';
$mentor_status = '';
$review_status = '';
$sql = "SELECT * FROM micro_project WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $status = 'Submitted';
    while ($row = $result->fetch_assoc()) {
        $mentor_status = $row['mentor_status'];
        if (isset($row['acceptance_status']) && ($row['acceptance_status'] == 'Accepted' || $row['acceptance_status'] == 'Rejected')) {
          $review_status = 'Evaluated';
      } else {
          $review_status = '';
      }
      
    }
} else {
    $status = 'Not Submitted';
}
?>




 


<!-- ########################## ************Content Place***************** ########################-->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Submission Status</h1>
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

  <!-- Default box -->
  <div class="card">
    <div class="card-header bg-gradient-navy">

      <div class="card-tools">
      </div>
    </div>
    <div class="card-body">
      <div class="container mt-3">

<table class="table table-sm">
  <thead>
    <tr>
      <th scope="col">S.no</th>
      <th scope="col">Event</th>
      <th scope="col">Submission Status</th>
      <th scope="col">Mentor Review Status</th>
      <th scope="col">External Review Status</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Micro Project</td>
      <td><?php echo $status ?></td>
      <td><?php echo $mentor_status ?></td>
      <td><?php echo $review_status ?></td>
    </tr>
  </tbody>
</table>
    </div>
    </div>
    <!-- /.card-body -->

    <!-- /.card-footer-->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- ########################## ************ End of Content Place***************** ########################-->

<?php
include "footer.html";
?>
