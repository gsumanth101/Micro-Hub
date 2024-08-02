<?php
include('sidebar.php');
if (isset($_SESSION["username"])) {
    $id = $_GET['id'];
    $username = $_SESSION["username"];
    $mentorName = $userData['name'];

    $sql = "SELECT * FROM hack_submission WHERE id = '$id' and fa_status = 'Accepted'";
    $result = $conn->query($sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
    } else {
        $row = null;
    }
    $name = $row['name'];
    $regd_no = $row['username'];

    if (mysqli_num_rows($result) == 0) {
        echo '
        <div class="text-center"><br><br>
          <img src="dist\img\cartoon.jpeg" class="card-img-top" alt="Team Member" style="width: 300px; height: 400px;">
        <h6 class="text" style="color:red">You are not Registered</h6>
      </div><br><br><br><br>';
        include('footer.html');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $action = isset($_POST['action']) ? $_POST['action'] : null;

        // Validate input
        if ($id && ($action === 'accept' || $action === 'reject')) {
            $status = $action === 'accept' ? 'Accepted' : 'Rejected';

            // Prepare SQL query to update status
            $query = "UPDATE hack_submission SET fa_status = ? WHERE id = ?";
            $stmt = $conn->prepare($query);

            // Check if prepare() failed
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }

            // Bind parameters and execute
            $stmt->bind_param("si", $status, $id);

                // If the action is 'accept', update the marks
                if ($action === 'accept') {
                    $random_marks = 0;
                    if($row['rank'] == '1-3') {
                        $random_marks = rand(97, 99);
                    } else if($row['event_type'] == '4-10') {
                        $random_marks = rand(81-90);
                    } else {
                    $random_marks = 0;
                    }

                    $update_sql = "UPDATE hack_submission SET marks = ? WHERE id = ?";
                    $stmt_update = $conn->prepare($update_sql);

                    // Check if prepare() failed
                    if ($stmt_update === false) {
                        die('Prepare failed: ' . htmlspecialchars($conn->error));
                    }

                    // Bind parameters and execute
                    $stmt_update->bind_param("ii", $random_marks, $id);

                    if ($stmt_update->execute()) {
                        echo "Submission successfully updated.";
                        echo "<script>alert('Submission successfully updated.'); window.location.href = 'hack_sub.php';</script>";
                    } else {
                        echo "An error occurred while updating marks for submission ID: $id<br>";
                    }
                    $stmt_update->close();
                }
            } else {
                echo "An error occurred. Please try again.";
            }
            $stmt->close();
        } 
    }


?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Benchmark Competitions</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Benchmark Competitions</a></li>
                        <li class="breadcrumb-item active">Submission</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header bg-orange">
                <h3 class="card-title">Submissions</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12 order-2 order-md-3">
                        <div class="row">
                            <div class="col-12 col-md-5">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Name</span>
                                        <span class="info-box-number text-center text-muted mb-0"><?php echo $name ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Regd No</span>
                                        <span class="info-box-number text-center text-muted mb-0"><?php echo $regd_no ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="post">
                                <div class="user-block">
                                    <span class="username">
                                        <a href="#"></a>
                                    </span>
                                    <!-- <span class="description">Shared publicly - 7:45 PM today</span> -->
                                </div>
                                <!-- /.user-block -->

                                <!-- <p>
                        <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 1 v2</a>
                      </p> -->
                            </div>
                        </div>
                    </div>
                </div>
                <p>
                <p>
                <table class="table table-bordered">
                    <tr>
                        <th>Event Type</th>
                        <td><?php echo $row['event_type'] ?></td>
                    </tr>
                    <tr>
                        <th>Event Name</th>
                        <td><?php echo $row['event_name'] ?></td>
                    </tr>
                    <tr>
                        <th>Organization Name</th>
                        <td><?php echo $row['org_name'] ?></td>
                    </tr>
                    <tr>
                        <th>Rank</th>
                        <td><?php echo $row['rank'] ?></td>
                    </tr>
                </table><br>
                <div class="row">
                    <div class="col-md-3">
                        <h4>Report</h4>
                        <?php
                        if (isset($row['certificate_path'])) {
                            $certificatePath = "../" . $row['certificate_path'];
                            if (file_exists($certificatePath)) {
                                echo "<a href='{$certificatePath}' target='_blank' class='btn btn-primary btn-sm'>View PDF</a>";
                            } else {
                                echo "Report PDF file not found.";
                            }
                        } else {
                            echo "Report PDF path not available.";
                        }
                        ?>
                    </div>
                    <div class="col-md-3">
                        <h4>Brochure</h4>
                        <?php
                        if (isset($row['brochure_path'])) {
                            $BroucherPath = "../" . $row['brochure_path'];
                            if (file_exists($BroucherPath)) {
                                echo "<a href='{$BroucherPath}' target='_blank' class='btn btn-primary btn-sm'>View PDF</a>";
                            } else {
                                echo "Brochure PDF file not found.";
                            }
                        } else {
                            echo "Brochure PDF path not available.";
                        }
                        ?>
                    </div>
                </div>
                </p>
                </p>
            </div>
            <div class="card-footer">
                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="row">
                        <div class="float-left">
                            <button type="submit" name="action" value="accept" class="btn btn-success">Accept</button>
                        </div>
                        <div class="float-left mx-2">
                            <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.card-body -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
include('footer.html');
?>
