<?php
include('../includes/connection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize inputs as needed
    $coordinatorUsername = $_POST["coordinatorUsername"];

    // Delete coordinator from the database
    $sql = "DELETE FROM coordinator WHERE username='$coordinatorUsername'";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the page where you display coordinators
        header("Location: coordinator.php");
        exit();
    } else {
        // Show a popup alert for the error and redirect back
        echo "<script>
                alert('Error deleting coordinator: " . $conn->error . "');
                window.location.href = 'coordinator.php';
              </script>";
        exit();
    }
}

// Close the database connection
$conn->close();
?>
