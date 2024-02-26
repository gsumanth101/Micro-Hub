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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve new submission status
    $submission_enabled = isset($_POST["submission_enabled"]) ? 1 : 0;

    // Update submission settings in the database
    $submission_sql = "UPDATE event_submission SET submission_enabled = $submission_enabled WHERE event_name = '$selected_event'";
    if ($conn->query($submission_sql) === TRUE) {
        echo "<br>";
       // echo "<script>
   //     window.location.href = 'submission.php';
   // </script>";
    } else {
        echo "Error updating submission settings: " . $conn->error;
    }
}

// Fetch submission settings for the selected event from the database
$submission_query = "SELECT * FROM event_submission WHERE event_name = '$selected_event'";
$submission_result = $conn->query($submission_query);
if ($submission_result->num_rows > 0) {
    $row = $submission_result->fetch_assoc();
    $submission_enabled = $row["submission_enabled"];
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
                <h3>Event Submission</h3>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="container mt-5">
                        <div class="form-group">
                            <label>Select Event:</label>
                            <select class="form-control" name="event" >
                                <?php foreach ($events as $event) : ?>
                                    <option value="<?php echo $event; ?>" <?php echo ($selected_event == $event) ? 'selected' : ''; ?>><?php echo $event; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <hr>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="customSwitch1" name="submission_enabled" <?php echo $submission_enabled ? 'checked' : ''; ?>>
                            <label class="custom-control-label" for="customSwitch1">Enable Submission</label>
                        </div>
                    </div>
                    <br>
                    <input type="submit" value="Save" class="btn btn-primary">
                </form>
            </div>
        </div>
    </section>
</div>
<?php 
include "footer.html";
?>