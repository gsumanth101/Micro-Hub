<?php
include("sidebar.php");
$results_per_page = 50;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $results_per_page;

$search_condition = isset($_GET['search']) ? $_GET['search'] : '';
$search_condition = $search_condition ? "WHERE username LIKE '%$search_condition%' OR name LIKE '%$search_condition%' OR section LIKE '%$search_condition%' OR year LIKE '%$search_condition%' OR dept LIKE '%$search_condition%'" : '';

$sql = "SELECT * FROM internship_teams $search_condition LIMIT $offset, $results_per_page";
$result = $conn->query($sql);

$serial_number_start = ($page - 1) * $results_per_page + 1;
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3>Teams</h3>
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
          <h3 class="card-title">Projects</h3>

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
                          Team Name
                      </th>
                      <th>
                          Project Progress
                      </th>
                      <th class="text-center">
                         Redg. Status
                      </th>
                      <th class="text-cernter">
                        
                      </th>
                  </tr>
              </thead>
              <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {

             echo    " <tr>
                      <td>
                        $serial_number_start
                      </td>
                      <td>";
               echo   "<a>
                          <p class=''>" . $row['team_name'] . "</p>
                          <small>Team ID: " . $row['team_id'] . "</small>
                      </a>
                      </td>";
                   echo  '<td class="project_progress">
                          <div class="progress progress-sm">
                              <div class="progress-bar bg-green" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                              </div>
                          </div>
                          <small>
                              0% Complete
                          </small>
                      </td>';
                  echo  '<td class="project-state">
                          <span class="badge badge-success">Success</span>
                      </td>';
                      echo '<td class="project-actions text-right">
                      <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href=\'intern_view.php?team=' . $row['team_id'] . '\'">
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
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <?php
                    $total_records_sql = "SELECT COUNT(*) FROM internship_teams $search_condition";
                    $total_records_result = $conn->query($total_records_sql);
                    $total_records = $total_records_result->fetch_row()[0];
                    $total_pages = ceil($total_records / $results_per_page);

                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo "<li class='page-item'><a class='page-link' href='?page=$i&search=$search_condition'>$i</a></li>";
                    }
                    ?>
                </ul>
            </div>
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
include("footer.html");
?>