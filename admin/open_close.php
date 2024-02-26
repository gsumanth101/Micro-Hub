<?php
// Include the sidebar.php file which contains necessary dependencies
include 'sidebar.php';


// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch events from the database
$events_query = "SELECT event_name FROM event_table"; // Update with your table name
$events_result = $conn->query($events_query);
$events = array();
if ($events_result->num_rows > 0) {
    while ($row = $events_result->fetch_assoc()) {
        $events[] = $row["event_name"];
    }
}

// Initialize variables
$selected_event = isset($_POST["event"]) ? $_POST["event"] : ($events ? $events[0] : ''); // Default to the first event if available
$registration_enabled = false;
$submission_enabled = false;
$registration_open_date = "";
$registration_close_date = "";
$submission_open_date = "";
$submission_close_date = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve new registration and submission status and dates
    $registration_enabled = isset($_POST["registration_enabled"]) ? 1 : 0;
    $submission_enabled = isset($_POST["submission_enabled"]) ? 1 : 0;
    $registration_open_date = $_POST["registration_open_date"];
    $registration_close_date = $_POST["registration_close_date"];
    $submission_open_date = $_POST["submission_open_date"];
    $submission_close_date = $_POST["submission_close_date"];

    // Update registration settings in the database
    $registration_sql = "UPDATE event_registration SET registration_enabled = $registration_enabled, open_date = '$registration_open_date', close_date = '$registration_close_date' WHERE event_name = '$selected_event'";
    if ($conn->query($registration_sql) === TRUE) {
        echo "Registration settings updated successfully<br>";
    } else {
        echo "Error updating registration settings: " . $conn->error;
    }

    // Update submission settings in the database
    $submission_sql = "UPDATE event_submission SET submission_enabled = $submission_enabled, submission_open_date = '$submission_open_date', submission_close_date = '$submission_close_date' WHERE event_name = '$selected_event'";
    if ($conn->query($submission_sql) === TRUE) {
        echo "Submission settings updated successfully<br>";
    } else {
        echo "Error updating submission settings: " . $conn->error;
    }
}

// Fetch registration settings for the selected event from the database
$registration_query = "SELECT * FROM event_registration WHERE event_name = '$selected_event'";
$registration_result = $conn->query($registration_query);
if ($registration_result->num_rows > 0) {
    $row = $registration_result->fetch_assoc();
    $registration_enabled = $row["registration_enabled"];
    $registration_open_date = $row["open_date"];
    $registration_close_date = $row["close_date"];
}

// Fetch submission settings for the selected event from the database
$submission_query = "SELECT * FROM event_submission WHERE event_name = '$selected_event'";
$submission_result = $conn->query($submission_query);
if ($submission_result->num_rows > 0) {
    $row = $submission_result->fetch_assoc();
    $submission_enabled = $row["submission_enabled"];
    $submission_open_date = $row["submission_open_date"];
    $submission_close_date = $row["submission_close_date"];
}

// Close the database connection
$conn->close();
?>

<!-- HTML content starts here -->
<div class="content-wrapper">
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
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3>Event Details</h3>
            </div>
            <div class="card-body">
                <form action="update_registration_submission_status.php" method="post">
                    <div class="container mt-5">
                        <h2>Event Selection</h2>
                        <div class="form-group">
                            <label>Select Event:</label>
                            <select class="form-control" name="event" onchange="this.form.submit()">
                                <?php foreach ($events as $event) : ?>
                                    <option value="<?php echo $event; ?>" <?php echo ($selected_event == $event) ? 'selected' : ''; ?>><?php echo $event; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <hr>
                        <h2>Registration Settings</h2>
                        <div class="form-group">
                            <label>Registration Open Date:</label>
                            <input type="datetime-local" class="form-control" name="registration_open_date" value="<?php echo $registration_open_date ? date('Y-m-d\TH:i', strtotime($registration_open_date)) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Registration Close Date:</label>
                            <input type="datetime-local" class="form-control" name="registration_close_date" value="<?php echo $registration_close_date ? date('Y-m-d\TH:i', strtotime($registration_close_date)) : ''; ?>">
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="customSwitch1" name="registration_enabled" <?php echo $registration_enabled ? 'checked' : ''; ?>>
                            <label class="custom-control-label" for="customSwitch1">Enable Registration</label>
                        </div>
                        <hr>
                        <h2>Submission Settings</h2>
                        <div class="form-group">
                            <label>Submission Open Date:</label>
                            <input type="datetime-local" class="form-control" name="submission_open_date" value="<?php echo $submission_open_date ? date('Y-m-d\TH:i', strtotime($submission_open_date)) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Submission Close Date:</label>
                            <input type="datetime-local" class="form-control" name="submission_close_date" value="<?php echo $submission_close_date ? date('Y-m-d\TH:i', strtotime($submission_close_date)) : ''; ?>">
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="customSwitch2" name="submission_enabled" <?php echo $submission_enabled ? 'checked' : ''; ?>>
                            <label class="custom-control-label" for="customSwitch2">Enable Submission</label>
                        </div>
                    </div>
                    <br>
                    <input type="submit" value="Save" class="btn btn-primary">
                </form>
            </div>
        </div>
    </section>
</div>
