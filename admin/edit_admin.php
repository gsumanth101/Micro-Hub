<?php
include('../includes/connection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize inputs as needed

    $adminUsername = $_POST["adminUsername"];
    $editName = $_POST["editName"];
    $editEmail = $_POST["editEmail"];
    $editdept = $_POST["editdept"];

    // Update admin in the database
    $sql = "UPDATE admin SET name='$editName', email='$editEmail', dept='$editdept' WHERE username='$adminUsername'";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the page where you display admins
        header("Location: admin.php");
        exit();
    } else {
        // Show a popup alert for the error and redirect back
        echo "<script>
                alert('Error updating admin: " . $conn->error . "');
                window.location.href = 'admin.php';
              </script>";
        exit();
    }
}

// Close the database connection
$conn->close();
?>
