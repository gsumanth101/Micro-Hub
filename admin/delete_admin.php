<?php
include('../includes/connection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize inputs as needed
    $adminUsername = $_POST["adminUsername"];

    // Delete admin from the database
    $sql = "DELETE FROM admin WHERE username='$adminUsername'";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the page where you display admins
        header("Location: admin.php");
        exit();
    } else {
        // Show a popup alert for the error and redirect back
        echo "<script>
                alert('Error deleting admin: " . $conn->error . "');
                window.location.href = 'admin.php';
              </script>";
        exit();
    }
}

// Close the database connection
$conn->close();
?>
