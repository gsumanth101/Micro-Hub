<?php
include "sidebar.php";

// Check if the user is logged in
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $mentorName = $userData['name'];

    // Assuming $conn is your database connection object
    $recordsPerPage = 10;  // Adjust this based on your requirements

    // Get the current page number, assuming it comes from the query string
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

    // Calculate the offset for the query
    $offset = ($page - 1) * $recordsPerPage;

    // Fetch data for the current page
    $query = "SELECT * FROM micro_project WHERE reviewer_name = ? AND mentor_status='Accepted' LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sii', $mentorName, $offset, $recordsPerPage);
    $stmt->execute();

    // Get the result set from the query
    $studentResult = $stmt->get_result();

    // Calculate total pages
    $countQuery = "SELECT COUNT(*) as total FROM micro_project WHERE reviewer_name = ? AND mentor_status='Accepted' ";
    $countStmt = $conn->prepare($countQuery);
    $countStmt->bind_param('s', $mentorName);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $totalRecords = $countResult->fetch_assoc()['total'];
    $total_pages = ceil($totalRecords / $recordsPerPage);
    $serial_number_start = ($page - 1) * $recordsPerPage + 1;
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
                                    <input type="text" class="form-control" placeholder="Search by Name, Section,Year, or Regd No." name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
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
                <div class="table-responsive overflow-x-auto mb-3">
                    <!-- Table to display Student details -->
                    <table class='table'>
                    <thead>
                                <tr>
                                 <!--   <th>S.No</th>-->
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Project Title</th>
                                    <th>Tool Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        <tbody>
                            <?php
                            // Loop through Student details and display them
                            while ($row = $studentResult->fetch_assoc()) {
                                echo "<tr>";
                               // echo "<td>" . $serial_number_start . "</td>";
                                echo "<td>" . $row["username"] . "</td>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td>" . $row["project_title"] . "</td>";
                                echo "<td>" . $row["tool_name"] . "</td>";
                                echo "<td>
                                        <button type='button' class='btn btn-info btn-sm viewBtn' onclick=\"window.location.href='external_view_submissions.php?studentId={$row['username']}'\">View</button><br>
                                        <button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deleteModal{$serial_number_start}'>Remove</button>
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
                                            <p>Are you sure you want to delete this Project?</p>
                                            <form action='delete_submission.php' method='post'>
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
                <!-- Your existing pagination code -->
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
