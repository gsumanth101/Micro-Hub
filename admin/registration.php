<?php
// Include the sidebar.php file which contains necessary dependencies
include 'sidebar.php';

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch events from the database
$events_query = "SELECT event_name FROM event_registration"; // Update with your table name
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
    // Retrieve new registration status
    $registration_enabled = isset($_POST["registration_enabled"]) ? 1 : 0;

    // Update registration settings in the database
    $registration_sql = "UPDATE event_registration SET registration_enabled = $registration_enabled WHERE event_name = '$selected_event'";
    if ($conn->query($registration_sql) === TRUE) {
        // Update successful, refresh registration status
        $registration_query = "SELECT registration_enabled FROM event_registration WHERE event_name = '$selected_event'";
        $registration_result = $conn->query($registration_query);
        if ($registration_result->num_rows > 0) {
            $row = $registration_result->fetch_assoc();
            $registration_enabled = $row["registration_enabled"];
        }
    } else {
        echo "Error updating registration settings: " . $conn->error;
    }
}

// Fetch registration settings for the selected event from the database
$registration_query = "SELECT registration_enabled FROM event_registration WHERE event_name = '$selected_event'";
$registration_result = $conn->query($registration_query);
if ($registration_result->num_rows > 0) {
    $row = $registration_result->fetch_assoc();
    $registration_enabled = $row["registration_enabled"];
} else {
    // If no registration setting found, default to disabled
    $registration_enabled = 0;
}

// Set status based on registration status
$status = $registration_enabled ? "Enabled" : "Disabled";

// Close the database connection
$conn->close();
?>

<!-- HTML content starts here -->
<div class="content-wrapper">
    <p></p>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3>Event Registration</h3>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="container mt-5">
                        <div class="form-group">
                            <label>Select Event:</label>
                            <select class="form-control" name="event">
                                <?php foreach ($events as $event) : ?>
                                    <option value="<?php echo $event; ?>" <?php echo ($selected_event == $event) ? 'selected' : ''; ?>><?php echo $event; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label>Status :</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($status);?>" disabled>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="customSwitch1" name="registration_enabled" <?php echo $registration_enabled ? 'checked' : ''; ?>>
                            <label class="custom-control-label" for="customSwitch1">Enable Registration</label>
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
