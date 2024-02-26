<?php
include('../includes/connection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize inputs as needed
    $coordinatorUsername = $_POST["coordinatorUsername"];

    // Delete coordinator from the database
    $sql = "DELETE FROM microproject_tools WHERE toolname='$coordinatorUsername'";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the page where you display coordinators
        header("Location: manage_tools.php");
        exit();
    } else {
        // Show a popup alert for the error and redirect back
        echo "<script>
                alert('Error deleting Tool: " . $conn->error . "');
                window.location.href = 'manage_tools.php';
              </script>";
        exit();
    }
}

// Close the database connection
$conn->close();
?>
