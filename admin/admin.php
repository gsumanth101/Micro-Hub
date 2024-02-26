<?php
include "sidebar.php";

// Pagination variables
$results_per_page = 250;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $results_per_page;

// Search condition
$search_condition = isset($_GET['search']) ? $_GET['search'] : '';
$search_condition = $search_condition ? "WHERE username LIKE '%$search_condition%' OR name LIKE '%$search_condition%' OR dept LIKE '%$search_condition%'" : '';

// SQL query to fetch admin details with pagination and search
$sql = "SELECT * FROM admin $search_condition LIMIT $offset, $results_per_page";
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
                        <h3>admin Details</h3>
                    </div>
                    <div class="col-md-6">
                        <!-- Small Search Bar -->
                        <form method="get" class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search by Name, Section,Year, or Department" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                        <!-- Add admin Button -->
                        <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#addadminModal">Add admin</button>
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
                    <!-- Table to display admin details -->
                    <table class='table'> 
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            // Loop through admin details and display them
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $serial_number_start . "</td>";
                                echo "<td>" . $row["username"] . "</td>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td>" . $row["email"] . "</td>";
                                echo "<td>" . $row["dept"] . "</td>";
                                echo "<td>
                                        <button type='button' class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editModal{$serial_number_start}' data-username='{$row["username"]}'>Edit</button>
                                        <button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deleteModal{$serial_number_start}'>Delete</button>
                                    </td>";
                                echo "</tr>";

                            // Edit Modal
                            echo "<div class='modal fade' id='editModal{$serial_number_start}' tabindex='-1' role='dialog' aria-labelledby='editModalLabel{$serial_number_start}' aria-hidden='true'>
                                    <div class='modal-dialog' role='document'>
                                        <div class='modal-content'>
                                            <div class='modal-header'>
                                                <h5 class='modal-title' id='editModalLabel{$serial_number_start}'>Edit admin</h5>
                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                            </div>
                                            <div class='modal-body'>
                                                <form action='edit_admin.php' method='post'>
                                                    <div class='form-group'>
                                                        <label for='editName'>Name:</label>
                                                        <input type='text' class='form-control' id='editName' name='editName' value='{$row["name"]}'>
                                                    </div>
                                                    <div class='form-group'>
                                                        <label for='editEmail'>Email:</label>
                                                        <input type='email' class='form-control' id='editEmail' name='editEmail' value='{$row["email"]}'>
                                                     </div>
                            
                                                    <div class='form-group'>
                                                        <label for='editdept'>Department:</label>
                                                        <input type='text' class='form-control' id='editdept' name='editdept' value='{$row["dept"]}'>
                                                    </div>
                                                    <input type='hidden' name='adminUsername' value='{$row["username"]}'>
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
                            echo "<div class='modal fade' id='deleteModal{$serial_number_start}' tabindex='-1' role='dialog' aria-labelledby='deleteModalLabel{$serial_number_start}' aria-hidden='true'>
                                    <div class='modal-dialog' role='document'>
                                        <div class='modal-content'>
                                            <div class='modal-header'>
                                                <h5 class='modal-title' id='deleteModalLabel{$serial_number_start}'>Delete admin</h5>
                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                            </div>
                                            <div class='modal-body'>
                                                <p>Are you sure you want to delete this admin?</p>
                                                <form action='delete_admin.php' method='post'>
                                                    <input type='hidden' name='adminUsername' value='{$row["username"]}'>
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
                    $total_records_sql = "SELECT COUNT(*) FROM admin $search_condition";
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

<!-- Add admin Modal -->
<div class="modal fade" id="addadminModal" tabindex="-1" role="dialog" aria-labelledby="addadminModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addadminModalLabel">Add admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add_admin.php" method="post">
                <div class="form-group">
                        <label for="addUsername">Username:</label>
                        <input type="text" class="form-control" id="addUsername" name="addUsername" required>
                    </div>
                    <div class="form-group">
                        <label for="addName">Name:</label>
                        <input type="text" class="form-control" id="addName" name="addName" required>
                    </div>
                    <div class="form-group">
                        <label for="addEmail">Email:</label>
                        <input type="email" class="form-control" id="addEmail" name="addEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="adddept">Department:</label>
                        <input type="text" class="form-control" id="adddept" name="adddept" required>
                    </div>
                    <div class="form-group">
                        <label for="addPass">Password:</label>
                        <input type="password" class="form-control" id="addPass" name="addPass" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add admin</button>
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
