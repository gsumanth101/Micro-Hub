<?php
include('../includes/connection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize inputs as needed

    $coordinatorUsername = $_POST["coordinatorUsername"];
    $editId = $_POST["editId"];
    $editName = $_POST["editName"];
    $editDept = $_POST["editDept"];

    // Check if the ID already exists
    $checkSql = "SELECT id FROM microproject_tools WHERE id='$editId' AND toolname<>'$coordinatorUsername'";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        // ID already exists, show a popup alert
        echo "<script>
                alert('ID already exists. Please choose a different ID.');
                window.location.href = 'manage_tools.php';
              </script>";
        exit();
    }

    // Update coordinator in the database
    $sql = "UPDATE microproject_tools SET id='$editId', toolname='$editName', maxlimit='$editDept' WHERE toolname='$coordinatorUsername'";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the page where you display coordinators
        header("Location: manage_tools.php");
        exit();
    } else {
        // Show a popup alert for the error and redirect back
        echo "<script>
                alert('Error updating Tool: " . $conn->error . "');
                window.location.href = 'manage_tools.php';
              </script>";
        exit();
    }
}

// Close the database connection
$conn->close();
?>
