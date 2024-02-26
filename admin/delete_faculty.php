<?php
include('../includes/connection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize inputs as needed
    $facultyUsername = $_POST["facultyUsername"];

    // Delete faculty from the database
    $sql = "DELETE FROM faculty WHERE username='$facultyUsername'";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the page where you display facultys
        header("Location: faculty.php");
        exit();
    } else {
        // Show a popup alert for the error and redirect back
        echo "<script>
                alert('Error deleting faculty: " . $conn->error . "');
                window.location.href = 'faculty.php';
              </script>";
        exit();
    }
}

// Close the database connection
$conn->close();
?>
