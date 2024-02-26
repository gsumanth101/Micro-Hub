<?php
include 'sidebar.php';

$username = $_SESSION["username"];
$successMessage = '';
$updateButton = '';
$showForm = true; // Flag to determine if the form should be displayed

// Fetch data from microproject_tools table
$sql = "SELECT * FROM microproject_tools";
$result = $conn->query($sql);

$toolOptions = []; // Array to store tool options
$mentorOptions = []; // Array to store mentor options

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Extract tool details
        $toolName = $row['toolname'];
        $remainingSlots = 10 - $row['id']; // Assuming the limit is 10 for each tool

        // Generate option for tool select
        $toolOptions[$toolName] = '<option value="' . $toolName . '"';

        // Disable option if the limit exceeds
        if ($remainingSlots <= 0) {
            $toolOptions[$toolName] .= ' disabled';
        }

        // Fetch current count of registrations for the tool
        $currentCount = $row['id'];
        $toolOptions[$toolName] .= '>' . $toolName . ' (Remaining: ' . $remainingSlots . ', Current Count: ' . $currentCount . ')</option>';
    }
}

// Fetch data from faculty table
$sql = "SELECT * FROM faculty";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Extract mentor details
        $mentorName = $row['name'];
        $remainingSlots = 10 - $row['id']; // Assuming the limit is 10 for each mentor

        // Generate option for mentor select
        $mentorOptions[$mentorName] = '<option value="' . $mentorName . '"';

        // Disable option if the limit exceeds
        if ($remainingSlots <= 0) {
            $mentorOptions[$mentorName] .= ' disabled';
        }

        // Fetch current count of registrations for the mentor
        $currentCount = $row['id'];
        $mentorOptions[$mentorName] .= '>' . $mentorName . ' (Remaining: ' . $remainingSlots . ', Current Count: ' . $currentCount . ')</option>';
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
                    <h1>Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h4 class="text-center mb-2">Micro Project Registration Form</h4>
                <div class="card-tools">
                </div>
            </div>
            <div class="card-body">
                <div class="container">

                    <?php if ($showForm): ?>
                        <!-- Display form only if $showForm is true -->
                        <form action="registration.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                            <div class="mb-3">
                                <label>Registration Number :</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($userData['username']); ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label>Name :</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($userData['name']); ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label>Section :</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($userData['section']); ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label>Year :</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($userData['year']); ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="tool_name" class="form-label">Tool Name:</label>
                                <select name="tool_name" class="custom-select form-control-border border-width-3" required>
                                    <option value="">Select a tool</option> <!-- Blank option -->

                                    <?php
                                    // Output tool options
                                    foreach ($toolOptions as $toolOption) {
                                        echo $toolOption;
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="mentor" class="form-label">Mentor:</label>
                                <select name="mentor" class="custom-select form-control-border border-width-3" required>
                                    <option value="">Select the Mentor</option> <!-- Blank option -->

                                    <?php
                                    // Output mentor options
                                    foreach ($mentorOptions as $mentorOption) {
                                        echo $mentorOption;
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="platform" class="form-label">Choose the Platform:</label>
                                <select name="platform" class="custom-select form-control-border border-width-3" required>
                                    <option value="">Select the Platform</option> <!-- Blank option -->
                                    <?php
                                    // Fetch data from microproject_tools table
                                    $sql = "SELECT * FROM platform";
                                    $result = $conn->query($sql);

                                    // Populate dropdown with data from the database
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<option value="' . $row['platform'] . '">' . $row['platform'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="Noofweeks" class="form-label">Course duration:</label>
                                <select name="Noofweeks" class="custom-select form-control-border border-width-3" required>
                                    <option value="">Course Duration</option> <!-- Blank option -->
                                    <?php
                                    $sql = "SELECT * FROM duration";
                                    $result = $conn->query($sql);

                                    // Populate dropdown with data from the database
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<option value="' . $row['Noofweeks'] . '">' . $row['duration'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="courselink">Course Link:</label>
                                <input type="text" name="courselink" class="form-control form-control-border border-width-3" id="courselink" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    <?php endif; ?>

                    <?php if (!empty($successMessage)): ?>
                        <!-- Display success message if $successMessage is not empty -->
                        <div class="alert alert-success mt-3" role="alert">
                            <?php echo $successMessage; ?>
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

        // Validate required fields
        var toolName = document.getElementsByName('tool_name')[0].value;
        var mentor = document.getElementsByName('mentor')[0].value;

        if (!toolName || !mentor) {
            alert('Please fill in all required fields.');
            return false;
        }

        return true;
    }
</script>

<?php
include 'footer.html';
?>
