<?php
include "micro_sidebar.php";

// Pagination variables
$results_per_page = 250;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $results_per_page;

// Search condition
$search_condition = isset($_GET['search']) ? $_GET['search'] : '';
$search_condition_sql = $search_condition ? "AND (username LIKE '%$search_condition%' OR name LIKE '%$search_condition%' OR year LIKE '%$search_condition%')" : '';

// SQL query to fetch micro_project details with pagination and search
$sql = "SELECT * FROM micro_project $search_condition_sql LIMIT $offset, $results_per_page";
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
                        <h3>Micro Project Details</h3>
                    </div>
                    <div class="col-md-6">
                        <!-- Small Search Bar -->
                        <form method="get" class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search by Regd Number, Name,Year" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                        <form method="post" action="download_details.php">
                            <button type="submit" class="btn btn-success float-right" name="download">Download List</button>
                        </form>
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
                    <!-- Table to display micro_project details -->
                    <table class='table'>
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Tool Name</th>
                                <th>Year</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            // Loop through micro_project details and display them
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $serial_number_start . "</td>";
                                echo "<td>" . $row["username"] . "</td>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td>" . $row["tool_name"] . "</td>";
                                echo "<td>" . $row["year"] . "</td>";
                                echo "<td>" . $row["acceptance_status"] . "</td>";
                                echo "<td>
                                <button type='button' class='btn btn-info btn-sm viewBtn' data-toggle='modal' data-target='#viewModal{$serial_number_start}' data-username='{$row["username"]}'>View</button>
                                    </td>";
                                echo "</tr>";
                    // Modal for Viewing micro_project Details
                    echo "<div class='modal fade' id='viewModal{$serial_number_start}' tabindex='-1' role='dialog' aria-labelledby='viewModalLabel{$serial_number_start}' aria-hidden='true'>
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='viewModalLabel{$serial_number_start}'>Micro Project Details</h5>
                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>
                            <div class='modal-body'>
                            <p><strong>Regd No:</strong> {$row["username"]}</p>
                            <p><strong>Name:</strong> {$row["name"]}</p>
                            <p><strong>Section:</strong> {$row["year"]}</p>
                            <p><strong>Tool:</strong> {$row["tool_name"]}</p>
                            <p><strong>project Title:</strong> {$row["project_title"]}</p>
                            <p><strong>Project Status:</strong> {$row["project_status"]}</p>
                            <p><strong>Whether the Tool Used in the Project:</strong> {$row["usedtool"]}</p>
                            <p><strong>Course Duration(In Weeks):</strong> {$row["duration"]}</p>
                            <p><strong>Is the Certificate Valid?:</strong> {$row["valid"]}</p>
                            <p><strong>Have Presented and submitted Valid Documents?:</strong> {$row["presented"]}</p>
                            <p><strong>Is this Recommended?:</strong> {$row["recommended"]}</p>
                            <p><strong>Marks:</strong> {$row["marks"]}</p>
                            <p><strong>Grade:</strong> {$row["grade"]}</p>
                            <p><strong>Status:</strong> {$row["acceptance_status"]}</p>
                                
                                <!-- Add additional fields as needed -->
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
                    $total_records_sql = "SELECT COUNT(*) FROM micro_project $search_condition";
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
include "footer.html";
?>
