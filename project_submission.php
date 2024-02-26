<?php
include 'sidebar.php';

$username = $_SESSION["username"];
$successMessage = '';
$updateButton = '';
$showForm = false; // Initially set to false
$mentor = '';
$reviewer = '';

// Check if submissions are enabled
$submissionEnabledSql = "SELECT submission_enabled FROM event_submission";
$submissionEnabledResult = $conn->query($submissionEnabledSql);

if ($submissionEnabledResult) {
    $submissionEnabledRow = $submissionEnabledResult->fetch_assoc();
    $submissionEnabled = $submissionEnabledRow['submission_enabled'];

    if ($submissionEnabled == 0) {
        $showForm = false;
        $submissionStatusMessage = "Submissions closed.";
    }
}

// Check if the user is registered in micro_project_registration table
$checkRegistrationSql = "SELECT * FROM micro_project_registration WHERE username = ?";
$checkRegistrationStmt = $conn->prepare($checkRegistrationSql);
$checkRegistrationStmt->bind_param("s", $username);
$checkRegistrationStmt->execute();
$registrationResult = $checkRegistrationStmt->get_result();

if ($registrationResult->num_rows > 0 && $submissionEnabled == 1) {
    $showForm = true; // Set to true if registered and submissions enabled
} else {
    if ($submissionEnabled == 1) {
        $registrationStatusMessage = "You are not registered for the Micro Project.";
    }
}

function uploadFile($file, $targetDir, $allowedTypes, $maxSize, $username) {
    $fileName = $username . '_' . basename($file["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Check if file type is allowed
    if (!in_array($fileType, $allowedTypes)) {
        return "Invalid file type.";
    }

    // Check file size
    if ($file["size"] > $maxSize) {
        return "File size exceeds the limit.";
    }

    // Upload file
    if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
        return $targetFilePath;
    } else {
        return "Error uploading file.";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && $showForm) {
    // Check if the user has already submitted
    $checkSql = "SELECT * FROM micro_project WHERE username = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // User has already submitted, set flag to false
        $showForm = false;
        $successMessage = "You have already submitted  Micro Project.";
    } else {
        // Continue with form processing
        $projectTitle = $_POST['projectTitle'];
        $toolName = $_POST["tool_name"];
        $abstract = $_POST["abstract"];
        $regd = $username; // Assuming $username is already set
        $name = $userData["name"]; // Assuming $userData is already populated
        $year = $userData["year"]; // Assuming $userData is already populated
        $section = $userData["section"]; // Assuming $userData is already populated
        $githublink = $_POST["githublink"];

        // Fetch mentor name from registration data
        $sql = "SELECT * FROM micro_project_registration WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $mentor = $row['mentor_name'];
            $reviewer = $row['reviewer_name'];
        }

        // Upload files with username prefix
        $certificatePath = uploadFile($_FILES["certificate"], "uploads/certificate/", ["pdf"], 1000000, $username);
        $pptPath = uploadFile($_FILES["ppt"], "uploads/ppt/", ["pdf"], 1000000, $username);
        $reportPath = uploadFile($_FILES["report"], "uploads/report/", ["pdf"], 1000000, $username);
        $plag_reportPath = uploadFile($_FILES["plag_report"], "uploads/plag_report/", ["pdf"], 1000000, $username);

        // Insert data into the database using prepared statements
        $insertSql = "INSERT INTO micro_project (username, name, section, year, project_title, tool_name, mentor_name,reviewer_name, abstract, certificate, ppt, report, plag_report, githublink)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("ssssssssssssss", $regd, $name, $section, $year, $projectTitle, $toolName, $mentor,$reviewer, $abstract, $certificatePath, $pptPath, $reportPath, $plag_reportPath, $githublink);

        if ($stmt->execute()) {
            $showForm = false;
            $successMessage = "Submission successful!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper mt-2">

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header bg-gradient-navy">
                <h4 class="text-center mb-2">Micro Project Submission Form</h4>
                <div class="card-tools">
                </div>
            </div>
            <div class="card-body">
                <div class="container">

                    <?php if ($showForm): ?>
                        <!-- Display form only if $showForm is true -->
           <!-- Display form only if $showForm is true -->
           <form action="project_submission.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                            <div class="mb-3">
                                <label>Registration Number :</label>
                                <input type="text" class="form-control" placeholder="<?php echo htmlspecialchars($userData['username']);?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label>Name :</label>
                                <input type="text" class="form-control" placeholder="<?php echo htmlspecialchars($userData['name']);?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label>Section :</label>
                                <input type="text" class="form-control" placeholder="<?php echo htmlspecialchars($userData['section']);?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label>Year :</label>
                                <input type="text" class="form-control" placeholder="<?php echo htmlspecialchars($userData['year']);?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="projectTitle">Project Title</label>
                                <input type="text" name="projectTitle" class="form-control form-control-border border-width-2" id="projectTitle" required>
                            </div>
                            <div class="mb-3">
                                <label for="tool_name" class="form-label">Tool Name:</label>
                                <select name="tool_name" class="custom-select form-control-border border-width-3" required>
                                    <option value="">Select a tool</option> <!-- Blank option -->

                                    <?php
                                        // Fetch data from microproject_tools table
                                        $sql = "SELECT * FROM microproject_tools";
                                        $result = $conn->query($sql);

                                        // Populate dropdown with data from the database
                                        if ($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                                echo '<option value="' . $row['toolname'] . '">' . $row['toolname'] . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">No tools available</option>';
                                        }
                                        
                                    ?>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="othertool">Tool Name (<em style="color:red;">If you have selected other tool</em>)</label>
                                <input type="text" name="othertool" class="form-control form-control-border border-width-2" id="othertool">
                            </div>

                            <div class="mb-3">
                                <label for="mentor" class="form-label">Mentor:</label>
                                <?php
                                
                                $sql = "SELECT * FROM micro_project_registration where $username = username ";
                                $result = $conn->query($sql);
                                
                                
                                // Populate dropdown with data from the database
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    $mentor = $row['mentor_name'];
                                    $reviewer = $row['reviewer_name'];
                                } else {
                                    echo '<option value="">No mentors available</option>';
                                }
                                ?>
                                <input type="text" class="form-control" placeholder="<?php echo htmlspecialchars($mentor);?>" disabled>
                            </div>

                            
                         <!--   <div class="mb-3">
                                <label for="mentor" class="form-label">External Reviwer:</label> -->

                             <!--   <input type="text" class="form-control" placeholder="<?php echo htmlspecialchars($reviewer);?>" disabled>
                            </div> -->

                            <div class="mb-3">
                                <label for="abstract" class="form-label">Abstract:</label>
                                <textarea name="abstract" class="form-control" rows="4" placeholder="Upto 500 words" required></textarea>
                            </div>
                            <div>
                                <label for="githublink">GitHub link</label>
                                <input type="text" name="githublink" class="form-control form-control-border border-width-2" id="githublink" placeholder="www.github.com" required>
                            </div>

                            <div class="mb-3" >
                                <label for="certificate" class="form-label">Certificate (PDF, Max 1 MB):</label>
                                <input type="file" name="certificate" class="form-control" accept=".pdf" required>
                            </div>

                            <div class="mb-3">
                                <label for="ppt" class="form-label">PPT (PDF, Max 1 MB):</label>
                                <input type="file" name="ppt" class="form-control" accept=".pdf" required>
                            </div>

                            <div class="mb-3">
                                <label for="report" class="form-label">Report (PDF, Max 1 MB):</label>
                                <input type="file" name="report" class="form-control" accept=".pdf" required>
                            </div>

                            <div class="mb-3">
                                <label for="plag_report" class="form-label">Plagarism Report (PDF, Max 1 MB):</label>
                                <input type="file" name="plag_report" class="form-control" accept=".pdf" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>

                    <?php elseif (isset($registrationStatusMessage)): ?>
                        <!-- Display registration status message if not registered -->
                        <div class="alert alert-danger" role="alert">
                            <?php echo $registrationStatusMessage; ?>
                        </div>
                    <?php elseif (isset($submissionStatusMessage)): ?>
                        <!-- Display submission status message when submissions are closed -->
                        <div class="alert alert-danger" role="alert">
                            <?php echo $submissionStatusMessage; ?>
                        </div>
                    <?php else: ?>
                        <!-- Display a generic message if neither registered nor submissions closed -->
                        <div class="alert alert-info" role="alert">
                            Your Project has been Submitted and it is Under Review.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- /.card-body -->

            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function validateForm() {
        // Validate file sizes
        var certificateFile = document.getElementsByName('certificate')[0].files[0];
        var pptFile = document.getElementsByName('ppt')[0].files[0];
        var reportFile = document.getElementsByName('report')[0].files[0];
        var plag_reportFile = document.getElementsByName('plag_report')[0].files[0]; // Added this line

        var maxSize = 1000 * 1024; // 1 MB in bytes

        if (certificateFile && certificateFile.size > maxSize) {
            alert('Certificate file size exceeds the limit (1 MB).');
            return false;
        }

        if (pptFile && pptFile.size > maxSize) {
            alert('PPT file size exceeds the limit (1 MB).');
            return false;
        }

        if (reportFile && reportFile.size > maxSize) {
            alert('Report file size exceeds the limit (1 MB).');
            return false;
        }

        if (plag_reportFile && plag_reportFile.size > maxSize) {
            alert('Plagiarism Report file size exceeds the limit (1 MB).');
            return false;
        }

        // Validate required fields
        var toolName = document.getElementsByName('tool_name')[0].value;
        var mentor = document.getElementsByName('mentor')[0].value;
        var abstract = document.getElementsByName('abstract')[0].value;
        var submissionDate = document.getElementsByName('submission_date')[0].value;

        if (!toolName || !mentor || !abstract || !submissionDate) {
            alert('Please fill in all required fields.');
            return false;
        }

        return true;
    }
</script>

<?php
include 'footer.html';
?>
