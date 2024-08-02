<?php
include("sidebar.php");

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $mentorName = $userData['name'];
    $cod_slot = $userData['cod_slot'];

    $sql = "SELECT * FROM hack_submission WHERE slot = $cod_slot AND fa_status = 'Accepted' AND credits = 1 AND rank BETWEEN 1 AND 3";
    $result = $conn->query($sql);

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
                        <form method="get" class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
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
                      <th>
                          Regd No.
                      </th>
                      <th>
                          Name
                      </th>
                      <th class="text-center">
                         Event Type
                      </th>
                      <th class="text-cernter">
                        
                      </th>
                  </tr>
              </thead>
              <tbody>
                <?php
                while ($result && $row = $result->fetch_assoc()) {

            echo '<tr>
                <td>
                    ' . $serial_number_start . '
                </td>
                <td>
                    <a>
                        <p class="">' . $row['username'] . '</p>
                    </a>
                </td>
                <td>
                    <a>
                        <p class="">' . $row['name'] . '</p>
                    </a>
                </td>
                <td class="text-center">
                    <a>
                        <p class="">' . $row['event_type'] . '</p>
                    </a>
                </td>
                <td class="text-right">
                    <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href=\'school_cod_view.php?id=' . $row['id'] . '\'">
                        <i class="fas fa-folder"></i>
                        View
                    </button>
                </td>
            </tr>';
            $serial_number_start++;

                }
                ?>

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