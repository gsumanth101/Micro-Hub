<?php
include("sidebar.php");

if (isset($_SESSION["username"])) {
  $username = $_SESSION["username"];
  $mentorName = $userData['name'];



  $serial_number_start = 1;
}

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="card-header">
        <div class="row">
          <div class="col-md-6">
            <h3>Benchmark Competitions</h3>
          </div>
          <div class="col-md-6">
            <!-- Small Search Bar -->
            <!-- <form method="get" class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                                </div>
                            </div>
                        </form> -->
            <!-- Add Student Button -->
            <!-- <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#addStudentModal">Add Student</button> -->
          </div>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Submissions</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
          </button>
          <!-- <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
              <i class="fas fa-times"></i>
            </button> -->
        </div>
      </div>
      <div class="card-body p-0">
        <table class="table table-striped projects">
          <thead>
            <tr>
              <th>
                #
              </th>
              <th class="text-center">
                Event Type
              </th>
              <th class="text-cernter">

              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                1
              </td>
              <td class="text-center">
                <p>School Level (Industry Involvement)</p>
              </td>
              <td class="text-right">
                <a href="school_level.php" class="btn btn-primary btn-sm">
                  <i class="fas fa-folder"></i>
                  View
                </a>
              </td>
            </tr>

            <tr>
              <td>
                2
              </td>
              <td class="text-center">
                <p>Institute or University Level</p>
              </td>
              <td class="text-right">
                <button type="button" class="btn btn-primary btn-sm" onclick="top3_bench.php">
                  <i class="fas fa-folder"></i>
                  View
                </button>
              </td>
            </tr>
            <tr>
              <td>
                3
              </td>
              <td class="text-center">
                <p>State or National Level</p>
              </td>
              <td class="text-right">
                <button type="button" class="btn btn-primary btn-sm" onclick="top3_bench.php">
                  <i class="fas fa-folder"></i>
                  View
                </button>
              </td>
            </tr>
            <tr>
              <td>
                4
              </td>
              <td class="text-center">
                <p>International Level</p>
              </td>
              <td class="text-right">
                <button type="button" class="btn btn-primary btn-sm" onclick="top3_bench.php">
                  <i class="fas fa-folder"></i>
                  View
                </button>
              </td>
            </tr>


          </tbody>
        </table>
        <!-- Pagination -->
        <!-- /.card-footer-->
      </div>
    </div>
</div>
<!-- /.card -->

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
include("footer.html");
?>