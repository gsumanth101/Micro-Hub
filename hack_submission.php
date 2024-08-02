<?php
include('sidebar.php');
$username = $_SESSION['username'];
$sql = "SELECT * FROM student WHERE username='$username'";
$result = mysqli_query($conn, $sql);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $name = $row['name'];
        $regd_no = $row['username'];
        $section = $row['section'];
        $year = $row['year'];
        $slot = $row['slot'];
        $faculty = $row['faculty'];
    } else {
        echo "No records found.";
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Sanitize and validate input data
    $event_name = $_POST['event_name'];
    $org_name = $_POST['org_name'];
    $rank = $_POST['rank'];
    $event_type = $_POST['event'];
    $event_month = $_POST['event_month'];

    // File upload handling for certificate
    $target_dir_cert = "uploads/hack_submission/";
    if (!is_dir($target_dir_cert)) {
        mkdir($target_dir_cert, 0755, true);
    }

    $certificate = $_FILES['certificate'];
    $file_extension_cert = strtolower(pathinfo($certificate['name'], PATHINFO_EXTENSION));
    $target_file_cert = $target_dir_cert . $username . '_' . str_replace(' ', '_', $event_type) . '_certificate.' . $file_extension_cert;
    $upload_ok_cert = 1;

    // Check if certificate file is a valid type
    $allowed_types = array('pdf');
    if (!in_array($file_extension_cert, $allowed_types)) {
        echo "Sorry, only PDF files are allowed for the certificate.";
        $upload_ok_cert = 0;
    }

    // Check certificate file size (5MB max)
    if ($certificate['size'] > 5000000) {
        echo "Sorry, your certificate file is too large.";
        $upload_ok_cert = 0;
    }

    // File upload handling for brochure
    $target_dir_brochure = "uploads/hack_broucher/";
    if (!is_dir($target_dir_brochure)) {
        mkdir($target_dir_brochure, 0755, true);
    }

    $brochure = $_FILES['brochure'];
    $file_extension_brochure = strtolower(pathinfo($brochure['name'], PATHINFO_EXTENSION));
    $target_file_brochure = $target_dir_brochure . $username . '_' . str_replace(' ', '_', $event_type) . '_brochure.' . $file_extension_brochure;
    $upload_ok_brochure = 1;

    // Check if brochure file is a valid type
    if (!in_array($file_extension_brochure, $allowed_types)) {
        echo "Sorry, only PDF files are allowed for the brochure.";
        $upload_ok_brochure = 0;
    }

    // Check brochure file size (5MB max)
    if ($brochure['size'] > 5000000) {
        echo "Sorry, your brochure file is too large.";
        $upload_ok_brochure = 0;
    }

    // Attempt to upload files if all checks pass
    if ($upload_ok_cert && $upload_ok_brochure) {
        if (move_uploaded_file($certificate['tmp_name'], $target_file_cert) && move_uploaded_file($brochure['tmp_name'], $target_file_brochure)) {
            // Insert form data into the database using prepared statements
            $stmt = $conn->prepare("INSERT INTO hack_submission (username, name, section, year, faculty, slot, event_name, org_name, rank, event_type, event_month, certificate_path, brochure_path)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssssss", $username, $name, $section, $year, $faculty, $slot, $event_name, $org_name, $rank, $event_type, $event_month, $target_file_cert, $target_file_brochure);

            if ($stmt->execute()) {
                echo "Record and files uploaded successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your files.";
        }
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
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Submission</h3>
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <form action="hack_submission.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="event_name">Name of the Event</label>
                            <input type="text" class="form-control" name="event_name" id="event_name" placeholder="Name of the Event" required>
                        </div>

                        <div class="form-group">
                            <label for="org_name">Organization/Institute Name</label>
                            <input type="text" class="form-control" name="org_name" id="org_name" placeholder="Name of the Organization/Institute you have participated" required>
                        </div>

                        <div class="form-group">
                            <label for="rank">Rank</label>
                            <select class="form-control" name="rank" id="rank" required>
                                <option value="1-3">Top 3</option>
                                <option value="4-10">Top 4-10</option>
                                <option value="participation">Participation</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="event">Event Type</label>
                            <select class="form-control" name="event" id="event" required>
                                <option value="School Level (Industry Involvement)">School Level (Industry Involvement)</option>
                                <option value="Institute or University Level">Institute/ University Level</option>
                                <option value="State or National Level">State/National Level</option>
                                <option value="International Level">International Level</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="event_month">Event Month</label>
                            <select class="form-control" name="event_month" id="event_month" required>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="certificate">Upload Certificate</label>
                            <input type="file" class="form-control-file" name="certificate" id="certificate" accept=".pdf" required>
                        </div>

                        <div class="form-group">
                            <label for="brochure">Upload Brochure</label>
                            <input type="file" class="form-control-file" name="brochure" id="brochure" accept=".pdf" required>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary float-right" name="submit">Submit</button>
                        </div>
                    </form>
                </div><!-- /.card-body -->
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
include('footer.html');
?>