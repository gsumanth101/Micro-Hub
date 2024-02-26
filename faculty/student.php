<?php
include "sidebar.php";

// Check if the user is logged in
$username = isset($_SESSION["username"]) ? $_SESSION["username"] : '';

if (empty($username)) {
    echo "NA";
} else {
    // Pagination variables
    $results_per_page = 15;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * $results_per_page;

    // Query the database to get the corresponding name
    $query = "SELECT name FROM faculty WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    
    if ($stmt->execute()) {
        $stmt->bind_result($name);

        if ($stmt->fetch()) {
            // Output the name


            // Close the first statement
            $stmt->close();

            // Search condition
            $search_condition = isset($_GET['search']) ? $_GET['search'] : '';
            $search_condition = $search_condition ? "AND (username LIKE '%$search_condition%' OR name LIKE '%$search_condition%' OR section LIKE '%$search_condition%' OR year LIKE '%$search_condition%' OR dept LIKE '%$search_condition%')" : '';

            // Query the student table using the fetched name with limit and offset for pagination
            $studentQuery = "SELECT * FROM student WHERE faculty = ? $search_condition LIMIT ?, ?";
            $studentStmt = $conn->prepare($studentQuery);

            if ($studentStmt) {
                $studentStmt->bind_param("sii", $name, $offset, $results_per_page);

                if ($studentStmt->execute()) {
                    $studentResult = $studentStmt->get_result();

                    // Calculate total number of pages
                    $total_records_sql = "SELECT COUNT(*) FROM student WHERE faculty = ? $search_condition";
                    $total_records_stmt = $conn->prepare($total_records_sql);

                    if ($total_records_stmt) {
                        $total_records_stmt->bind_param("s", $name);

                        if ($total_records_stmt->execute()) {
                            $total_records_result = $total_records_stmt->get_result();
                            $total_records = $total_records_result->fetch_row()[0];
                            $total_pages = ceil($total_records / $results_per_page);

                            // Serial number initialization
                            $serial_number_start = ($page - 1) * $results_per_page + 1;
                        } else {
                            die("Error executing total records query: " . $total_records_stmt->error);
                        }

                        // Close the second statement
                        $total_records_stmt->close();
                    } else {
                        die("Error in total records query: " . $conn->error);
                    }
                } else {
                    die("Error executing student query: " . $studentStmt->error);
                }
            } else {
                die("Error in student query: " . $conn->error);
            }
        } else {
            echo "Name not found for the given username.";
        }
    } else {
        die("Error executing faculty query: " . $stmt->error);
    }
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
                        <h3>Student Details</h3>
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
                                <th>Section</th>
                                <th>Year</th>
                                <th>Department</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Loop through Student details and display them
                            while ($row = $studentResult->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $serial_number_start . "</td>";
                                echo "<td>" . $row["username"] . "</td>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td>" . $row["section"] . "</td>";
                                echo "<td>" . $row["year"] . "</td>";
                                echo "<td>" . $row["dept"] . "</td>";
                                echo "<td>
                                <button type='button' class='btn btn-info btn-sm viewBtn' data-toggle='modal' data-target='#viewModal{$serial_number_start}' data-username='{$row["username"]}'>View</button>

                                    </td>";
                                echo "</tr>";
                                
                    // Modal for Viewing Student Details
                    echo "<div class='modal fade' id='viewModal{$serial_number_start}' tabindex='-1' role='dialog' aria-labelledby='viewModalLabel{$serial_number_start}' aria-hidden='true'>
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='viewModalLabel{$serial_number_start}'>Student Details</h5>
                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>
                            <div class='modal-body'>
                                <p><strong>Regd No:</strong> {$row["username"]}</p>
                                <p><strong>Name:</strong> {$row["name"]}</p>
                                <p><strong>Section:</strong> {$row["section"]}</p>
                                <p><strong>Year:</strong> {$row["year"]}</p>
                                <p><strong>Department:</strong> {$row["dept"]}</p>
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
                <ul class="pagination pagination-sm m-0 float-right" id="pagination">
                    <?php
                    $pagination_search = isset($_GET['search']) ? "&search=" . urlencode($_GET['search']) : '';
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo "<li class='page-item'><a class='page-link' href='?page=$i$pagination_search'>$i</a></li>";
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
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>

<!-- Bootstrap JS (and Popper.js) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


<?php
include "footer.html";
?>
