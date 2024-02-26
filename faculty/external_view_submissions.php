<?php
include('sidebar.php');
if (isset($_GET['studentId'])) {
    $username = $_GET['studentId'];

    // Fetch project details for the specified student
    $query = "SELECT * FROM micro_project WHERE username = ? AND mentor_status='Accepted'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $projectDetails = $result->fetch_assoc();
    } else {
        echo "<script>alert('Already Reviewed'); window.location.href='external_project_submissions.php';</script>";
        exit;
    }
}

// Check if the record is already accepted
if ($projectDetails['acceptance_status'] === 'Accepted') {
    echo "<script>alert('This project has already been accepted. Cannot update.'); window.location.href='external_project_submissions.php';</script>";
    exit;
}

// Process form submission for evaluation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch evaluation form data
    $usedTool = $_POST['usedtool'];
    $projectStatus = $_POST['projectStatus'];
    $durationInWeeks = $_POST['durationInWeeks'];
    $validCertificate = $_POST['validCertificate'];
    $presented = $_POST['presented'];
    $recommended = $_POST['recomended'];
    $marks = $_POST['marks'];
   // $dateOfCertificate = $_POST['date_of_certificate'];

    // Validate the date format
       //     $dateTime = DateTime::createFromFormat('Y-m-d', $dateOfCertificate);
          //  if (!$dateTime) {
        // Handle the case where the date is not in the expected format
          //    echo "Invalid date format.";
        // Add further error handling or return false to stop the process
         //     exit;
       //     }           
       //     $formattedDate = $dateTime->format('Y-m-d');
    

    // Update the project record with evaluation details
    $updateQuery = "UPDATE micro_project SET 
                    usedtool = ?, 
                    project_status = ?, 
                    duration = ?, 
                    valid = ?, 
                    presented = ?, 
                    recommended = ?, 
                    date_of_certificate = ?, 
                    marks = ?, 
                    evaluation_status = 'evaluated',
                    acceptance_status = ? 
                    WHERE username = ?";
                    $updateStmt = $conn->prepare($updateQuery);
                    $updateStmt->bind_param('ssssssdsss', $usedTool, $projectStatus, $durationInWeeks, $validCertificate, $presented, $recommended, $formattedDate, $marks, $projectDetails['acceptance_status'], $username);

    // Error handling
    if (!$updateStmt->execute()) {
        echo "<script>alert('Error updating project evaluation details: " . $updateStmt->error . "'); window.location.href='dashboard.php';</script>";
        exit;
    }

    // Perform relative grading
    $grade = getRelativeGrade($marks);

    // Update grade in the database
    $gradeUpdateQuery = "UPDATE micro_project SET grade = ? WHERE username = ?";
    $gradeUpdateStmt = $conn->prepare($gradeUpdateQuery);
    $gradeUpdateStmt->bind_param('ss', $grade, $username);

    // Error handling
    if (!$gradeUpdateStmt->execute()) {
        echo "<script>alert('Error updating project grade: " . $gradeUpdateStmt->error . "'); window.location.href='dashboard.php';</script>";
        exit;
    }

    // Update acceptance/rejection based on conditions
    if ($projectStatus == 'Completed' && $validCertificate == 'Yes' && $presented == 'Yes' && $usedTool=='Yes' && $durationInWeeks>4 && $marks>=40 && $recommended=='Yes') {
        $acceptance = 'Accepted';
    } else {
        $acceptance = 'Rejected';
    }

    $acceptanceUpdateQuery = "UPDATE micro_project SET acceptance_status = ? WHERE username = ?";
    $acceptanceUpdateStmt = $conn->prepare($acceptanceUpdateQuery);
    $acceptanceUpdateStmt->bind_param('ss', $acceptance, $username);

    // Error handling
    if (!$acceptanceUpdateStmt->execute()) {
        echo "<script>alert('Error updating project acceptance status: " . $acceptanceUpdateStmt->error . "'); window.location.href='dashboard.php';</script>";
        exit;
    }

    echo "<script>alert('Evaluation has been submitted successfully.'); window.location.href='external_project_submissions.php';</script>";
    exit;
}

function getRelativeGrade($marks) {
    // Customize grading ranges as per your requirements
    if ($marks >= 90) {
        return 'S';
    } elseif ($marks >= 80) {
        return 'A';
    } elseif ($marks >= 70) {
        return 'B';
    } elseif ($marks >= 60) {
        return 'C';
    } elseif ($marks >= 50) {
    return 'D';
}  elseif ($marks >= 40) {
    return 'E';
} else {
        return 'F';
    }
} 
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
 <!--   <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div> --><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h4 class="text-center mb-2">Micro Project Review</h4>
                <div class="card-tools">
                </div>
            </div>
            <div class="card-body">
                <div class="container">
                    <h2>Submission Details</h2>
                    <table class="table">
                        <tr>
                            <th>Username</th>
                            <td><?php echo $projectDetails['username']; ?></td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td><?php echo $projectDetails['name']; ?></td>
                        </tr>
                        <tr>
                            <th>Project Title</th>
                            <td><?php echo $projectDetails['project_title']; ?></td>
                        </tr>
                        <tr>
                            <th>Tool Name</th>
                            <td><?php echo $projectDetails['tool_name']; ?></td>
                        </tr>
                        <tr>
                            <th>Tool Name(Other)</th>
                            <td><?php echo $projectDetails['other_tool_name']; ?></td>
                        </tr>
                        <tr>
                            <th>Abstract</th>
                            <td><?php echo $projectDetails['abstract']; ?></td>
                        </tr>
                        <tr>
                            <th>Github</th>
                            <td><a href="<?php echo htmlspecialchars($projectDetails['githublink']); ?>"><?php echo htmlspecialchars($projectDetails['githublink']);?></a></td>
                        </tr>
                    </table>
                
                    <div class="container">
                        <h5>File Previews</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <h4>Certificate</h4>
                                <?php
                                if (isset($projectDetails['certificate'])) {
                                    $certificatePath = "../" . $projectDetails['certificate'];
                                    if (file_exists($certificatePath)) {
                                        echo "<a href='{$certificatePath}' target='_blank' class='btn btn-primary btn-sm'>View PDF</a>";
                                    } else {
                                        echo "Certificate file not found.";
                                    }
                                } else {
                                    echo "Certificate path not available.";
                                }
                                ?>
                            </div>

                            <div class="col-md-2">
                                <h4>PPT</h4>
                                <?php
                                if (isset($projectDetails['ppt'])) {
                                    $pptPath = "../" . $projectDetails['ppt'];
                                    if (file_exists($pptPath)) {
                                        echo "<a href='{$pptPath}' target='_blank' class='btn btn-primary btn-sm'>View PDF</a>";
                                    } else {
                                        echo "PPT file not found.";
                                    }
                                } else {
                                    echo "PPT path not available.";
                                }
                                ?>
                            </div>

                            <div class="col-md-3">
                                <h4>Report</h4>
                                <?php
                                if (isset($projectDetails['report'])) {
                                    $reportPdfPath = "../" . $projectDetails['report'];
                                    if (file_exists($reportPdfPath)) {
                                        echo "<a href='{$reportPdfPath}' target='_blank' class='btn btn-primary btn-sm'>View PDF</a>";
                                    } else {
                                        echo "Report PDF file not found.";
                                    }
                                } else {
                                    echo "Report PDF path not available.";
                                }
                                ?>
                            </div>

                            
                            <div class="col-md-4">
                                <h4>Plagarism Report</h4>
                                <?php
                                if (isset($projectDetails['plag_report'])) {
                                    $plag_reportPdfPath = "../" . $projectDetails['plag_report'];
                                    if (file_exists($plag_reportPdfPath)) {
                                        echo "<a href='{$plag_reportPdfPath}' target='_blank' class='btn btn-primary btn-sm'>View PDF</a>";
                                    } else {
                                        echo "Report PDF file not found.";
                                    }
                                } else {
                                    echo "Report PDF path not available.";
                                }
                                ?>
                            </div> <br><br>

                            <!-- Button to Trigger Modal -->
                            <div class="mt-3 mb-4">
                                <button type='button' class='btn btn-primary btn-success' data-toggle='modal'
                                    data-target='#editModal<?php echo $username; ?>'
                                    data-username='<?php echo $projectDetails["username"]; ?>'>Review</button>

                            <!-- Edit Modal -->
                            <div class='modal fade' id='editModal<?php echo $username; ?>' tabindex='-1'
                                role='dialog' aria-labelledby='editModalLabel<?php echo $username; ?>'
                                aria-hidden='true'>
                                <div class='modal-dialog' role='document'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='editModalLabel<?php echo $username; ?>'>Review
                                                Project</h5>
                                            <button type='button' class='close' data-dismiss='modal'
                                                aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                        </div>
                                        <div class='modal-body'>
                                            <form action='' method='post'>
                                                <div class='form-group'>
                                                    <label for='editusername'>Username:</label>
                                                    <input type='text' class='form-control' id='editusername'
                                                        name='editusername'
                                                        value='<?php echo $projectDetails["username"]; ?>' disabled>
                                                </div>
                                                <div class='form-group'>
                                                    <label for='editName'>Name:</label>
                                                    <input type='text' class='form-control' id='editName'
                                                        name='editName'
                                                        value='<?php echo $projectDetails["name"]; ?>' disabled>
                                                </div>
                                                <div class='form-group'>
                                                    <label for='editYear'>Year:</label>
                                                    <input type='text' class='form-control' id='editYear'
                                                        name='editYear'
                                                        value='<?php echo $projectDetails["year"]; ?>' disabled>
                                                </div>
                                                <div class='form-group'>
                                                    <label for="usedtool">Whether the Registered Tool is Used for
                                                        the Project ?</label>
                                                    <select id="usedtool" name="usedtool" class='form-control'
                                                        required>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                                <br>
                                                <div class='form-group'>
                                                    <label for="projectStatus">Project Status:</label>
                                                    <select id="projectStatus" name="projectStatus"
                                                        class='form-control' required>
                                                        <option value="Completed">Completed</option>
                                                        <option value="Pending">Pending</option>
                                                    </select>
                                                </div>
                                                <br>

                                                <div class='form-group'>
                                                    <label for="durationInWeeks">Duration in Weeks:</label>
                                                    <select id="durationInWeeks" name="durationInWeeks"
                                                        class='form-control' required>
                                                        <option value="1">1 week</option>
                                                        <option value="2">2 weeks</option>
                                                        <option value="3">3 weeks</option>
                                                        <option value="4">4 weeks</option>
                                                        <option value="5">5 weeks</option>
                                                        <option value="6">6 weeks</option>
                                                        <option value="7">7 weeks</option>
                                                        <option value="8">8 weeks</option>
                                                    </select>
                                                </div>
                                                <br>
                                                <div class='form-group'>
                                                    <label for="validCertificate">Uploaded Valid Certificate
                                                        ?</label>
                                                    <select id="validCertificate" name="validCertificate"
                                                        class='form-control' required>
                                                        <option value="Yes">Yes</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </div>
                                                <br>

                                                <div class='form-group'>
                                                    <label for="presented">Have Presented the PPT and Submitted
                                                        the Valid Report?</label>
                                                    <select id="presented" name="presented" class='form-control'
                                                        required>
                                                        <option value="Yes">Yes</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </div>
                                                <br>
                                                <div class='form-group'>
                                                    <label for="recomended">Whether Recomended or Not</label>
                                                    <select id="recomended" name="recomended"
                                                        class='form-control' required>
                                                        <option value="Yes">Yes</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </div>
                                                <br>
                          <!--                      <div class="form-group">
                                                    <label for="date_of_certificate" class="form-label">Date of Certificate:</label>
                                                     <input type="date" id="date_of_certificate" name="date_of_certificate" class="form-control" required>
                                                </div>-->
                                                <br>
                                                <div class='form-group'>
                                                    <label for="marks">Marks (Up to 100):</label>
                                                    <input type="number" id="marks" name="marks" min="0" max="100"
                                                        class='form-control' required>
                                                </div> 

                                                <button type='submit' class='btn btn-primary'>Submit</button>
                                            </form>
                                        </div>
                                        <div class='modal-footer'>
                                            <button type='button' class='btn btn-secondary'
                                                data-dismiss='modal'>Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include 'footer.html';
?>
