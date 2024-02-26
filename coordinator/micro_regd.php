<?php
include "sidebar.php";

// Pagination variables
$results_per_page = 200;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $results_per_page;

// Search condition
$search_condition = isset($_GET['search']) ? $_GET['search'] : '';
$search_condition = $search_condition ? "WHERE username LIKE '%$search_condition%' OR name LIKE '%$search_condition%' OR section LIKE '%$search_condition%' OR reviewer_name LIKE '%$search_condition%' OR dept LIKE '%$search_condition%'" : '';

// SQL query to fetch Student details with pagination and search
$sql = "SELECT * FROM micro_project_registration $search_condition LIMIT $offset, $results_per_page";
$result = $conn->query($sql);

// Serial number initialization
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
                        <h3>Student Details</h3>
                    </div>
                    <div class="col-md-6">
                        <!-- Small Search Bar -->
                        <form method="get" class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search by Regd Number, Name, Section,Year, or Dept" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                        <!-- Add Student Button -->
                        <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#addStudentModal">Add Registration</button>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive overflow-x-auto">
                    <!-- Table to display Student details -->
                    <table class='table'>
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Mentor</th>
                                <th>External Reviewer</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            // Loop through Student details and display them
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $serial_number_start . "</td>";
                                echo "<td>" . $row["username"] . "</td>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td>" . $row["mentor_name"] . "</td>";
                                echo "<td>" . $row["reviewer_name"] . "</td>";
                                echo "<td>
                                        <button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deleteModal{$serial_number_start}'>Delete</button>
                                    </td>";
                                echo "</tr>";

                            

                            // Delete Modal
                            echo "<div class='modal fade' id='deleteModal{$serial_number_start}' tabindex='-1' role='dialog' aria-labelledby='deleteModalLabel{$serial_number_start}' aria-hidden='true'>
                                    <div class='modal-dialog' role='document'>
                                        <div class='modal-content'>
                                            <div class='modal-header'>
                                                <h5 class='modal-title' id='deleteModalLabel{$serial_number_start}'>Delete Student</h5>
                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                            </div>
                                            <div class='modal-body'>
                                                <p>Are you sure you want to delete this Student?</p>
                                                <form action='delete_micro_project_regd.php' method='post'>
                                                    <input type='hidden' name='StudentUsername' value='{$row["username"]}'>
                                                    <button type='submit' class='btn btn-danger'>Delete</button>
                                                </form>
                                            </div>
                                            <div class='modal-footer'>
                                                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>";
                                // Increment the serial number
                                $serial_number_start++;
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->

            <!-- Pagination -->
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <?php
                    $total_records_sql = "SELECT COUNT(*) FROM micro_project_registration $search_condition";
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

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Add Registration</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add_micro_project_regd.php" method="post">
                <div class="form-group">
                        <label for="addUsername">Registration Number:</label>
                        <input type="text" class="form-control" id="addUsername" name="addUsername" required>
                    </div>
                    <div class="form-group">
                        <label for="addName">Name:</label>
                        <input type="text" class="form-control" id="addName" name="addName" required>
                    </div>


                    <div class="form-group">
                        <label for="mentor">Mentor:</label>
                        <input type="text" class="form-control" id="mentor" name="mentor" required>
                    </div>

                    <div class="form-group">
                        <label for="reviewer">External Reviewer:</label>
                        <input type="text" class="form-control" id="reviewer" name="reviewer" required>
                    </div>


                    <button type="submit" class="btn btn-primary">Add Registration</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php
include "footer.html";
?>
