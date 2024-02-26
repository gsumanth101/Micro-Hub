<?php
include "micro_sidebar.php";

// Pagination variables
$results_per_page = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $results_per_page;

// Search condition
$search_condition = isset($_GET['search']) ? $_GET['search'] : '';
$search_condition = $search_condition ? "WHERE toolname LIKE '%$search_condition%'" : '';

// SQL query to fetch coordinator details with pagination and search
$sql = "SELECT * FROM microproject_tools $search_condition LIMIT $offset, $results_per_page";
$result = $conn->query($sql);

// Serial numbe
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3>Tools</h3>
                    </div>
                    <div class="col-md-6">
                        <!-- Small Search Bar -->
                        <form method="get" class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search by Tool Name" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                        <!-- Add Coordinator Button -->
                        <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#addCoordinatorModal">Add Tool</button>
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
                <!-- Table to display coordinator details -->
                <div class="table-responsive overflow-x-auto">
                    <table class='table'>
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Tool name</th>
                                <th>Limit</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            // Loop through coordinator details and display them
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["toolname"] . "</td>";
                                echo "<td>" . $row["maxlimit"] . "</td>";
                                echo "<td>
                                        <button type='button' class='btn btn-info btn-sm' data-toggle='modal' data-target='#editModal{$row["id"]}' data-username='{$row["toolname"]}'>Update</button>
                                        <button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deleteModal{$row["id"]}'>Delete</button>
                                    </td>";
                                echo "</tr>";

                                // Edit Modal
                                echo "<div class='modal fade' id='editModal{$row["id"]}' tabindex='-1' role='dialog' aria-labelledby='editModalLabel{$row["id"]}' aria-hidden='true'>
                                        <div class='modal-dialog' role='document'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                    <h5 class='modal-title' id='editModalLabel{$row["id"]}'>Edit Tool</h5>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                        <span aria-hidden='true'>&times;</span>
                                                    </button>
                                                </div>
                                                <div class='modal-body'>
                                                    <form action='edit_tools.php' method='post'>
                                                        <div class='form-group'>
                                                            <label for='editId'>Tool Id:</label>
                                                            <input type='number' class='form-control' id='editId' name='editId' value='{$row["id"]}'>
                                                        </div>
                                                        <div class='form-group'>
                                                            <label for='editName'>Tool Name:</label>
                                                            <input type='text' class='form-control' id='editName' name='editName' value='{$row["toolname"]}'>
                                                        </div>
                                                        <div class='form-group'>
                                                            <label for='editDept'>Limit</label>
                                                            <input type='number' class='form-control' id='editDept' name='editDept' value='{$row["maxlimit"]}'>
                                                        </div>
                                                        <input type='hidden' name='coordinatorUsername' value='{$row["toolname"]}'>
                                                        <button type='submit' class='btn btn-primary'>Save changes</button>
                                                    </form>
                                                </div>
                                                <div class='modal-footer'>
                                                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";

                                // Delete Modal
                                echo "<div class='modal fade' id='deleteModal{$row["id"]}' tabindex='-1' role='dialog' aria-labelledby='deleteModalLabel{$row["id"]}' aria-hidden='true'>
                                        <div class='modal-dialog' role='document'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                    <h5 class='modal-title' id='deleteModalLabel{$row["id"]}'>Delete Tool</h5>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                        <span aria-hidden='true'>&times;</span>
                                                    </button>
                                                </div>
                                                <div class='modal-body'>
                                                    <p>Are you sure you want to delete this Tool?</p>
                                                    <form action='delete_tools.php' method='post'>
                                                        <input type='hidden' name='coordinatorUsername' value='{$row["toolname"]}'>
                                                        <button type='submit' class='btn btn-danger'>Delete</button>
                                                    </form>
                                                </div>
                                                <div class='modal-footer'>
                                                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
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
                    $total_records_sql = "SELECT COUNT(*) FROM microproject_tools $search_condition";
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

<!-- Add Coordinator Modal -->
<div class="modal fade" id="addCoordinatorModal" tabindex="-1" role="dialog" aria-labelledby="addCoordinatorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCoordinatorModalLabel">Add Tool</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add_tools.php" method="post">
                <div class="form-group">
                        <label for="addId">Tool ID:</label>
                        <input type="number" class="form-control" id="addId" name="addId" required>
                    </div>
                    <div class="form-group">
                        <label for="addUsername">Tool Name:</label>
                        <input type="text" class="form-control" id="addUsername" name="addUsername" required>
                    </div>
                    <div class="form-group">
                        <label for="addDept">Limit:</label>
                        <input type="number" class="form-control" id="addDept" name="addDept" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Tool</button>
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
