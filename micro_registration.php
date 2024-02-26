<?php
include "sidebar.php";

// Check if registration is enabled
$sql_registration_enabled = "SELECT registration_enabled FROM event_registration";
$result_registration_enabled = $conn->query($sql_registration_enabled);
$row_registration_enabled = $result_registration_enabled->fetch_assoc();
$registration_enabled = $row_registration_enabled['registration_enabled'];

if ($registration_enabled == 1) {
    // Fetch tools from the database
    $sql = "SELECT * FROM microproject_tools";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Micro Project Tools</h1>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">'; // Start the row container

        while ($row = $result->fetch_assoc()) {
            // Extract tool details
            $toolName = $row['toolname'];
            $remainingSlots = $row['maxlimit'];

            // Generate HTML card for each tool with solid color
            $randomColor = generateSolidColor();
            echo '<div class="col-lg-3 col-6">
                    <div class="small-box" style="background-color: ' . $randomColor . '; width: 210px; min-height: 200px;">
                        <div class="inner">
                            <p>' . $toolName . '</p>
                        </div>
                        <div class="icon">
                            <i class="fab fa-' . $toolName . '"></i>
                        </div><br>
                        <div class="card-footer" style="color: black;">
                        <a href="registration.php?tool=' . urlencode($toolName) . '" class="small-box-footer" onclick="updateRegistrationLimit(\'' . $toolName . '\');">(Remaining: ' . $remainingSlots . ') <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                    
                    </div>
                </div>';
        }

        echo '</div>'; // End the row container
    } else {
        echo "No tools found in the database.";
    }

    echo '</div>';
    echo '</div>';
} else {
    // If registration is disabled, display a message
    echo '<div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Registrations Closed</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                            Registrations are currently closed.
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>';
}

include 'footer.html';

function generateSolidColor() {
    do {
        $randomColor = '#' . substr(md5(mt_rand()), 0, 6); // Generate a random color
        $rgb = sscanf($randomColor, "#%02x%02x%02x");
        $brightness = ($rgb[0] * 299 + $rgb[1] * 587 + $rgb[2] * 114) / 1000; // Calculate luminance
    } while ($brightness < 128); // Adjust this threshold as needed for your definition of 'dark'

    return $randomColor;
}
?>
