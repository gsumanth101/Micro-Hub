<?php
  include "sidebar.php";
?>

  <!-- /.navbar -->

 


<!-- ########################## ************Content Place***************** ########################-->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Dashboard</h1>
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
    <div class="card-header">
      <h4 class="text-center mb-2">Faculty Details</h4>

      <div class="card-tools">
      </div>
    </div>
    <div class="card-body">
      <div class="container mt-3">

        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th scope="row">Name</th>
                    <td><?php echo htmlspecialchars($userData['name']);?></td>
                </tr>
                <tr>
                    <th scope="row">Username</th>
                    <td><?php echo htmlspecialchars($userData['username']);?></td>
                </tr>
                <tr>
                  <th scope="row">Section</th>
                  <td><?php echo htmlspecialchars($userData['section']); ?></td>
                </tr>
                <tr>
                  <th scope="row">Department</th>
                  <td><?php echo htmlspecialchars($userData['dept']); ?></td>
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

