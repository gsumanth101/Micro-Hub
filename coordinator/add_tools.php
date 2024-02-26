<?php
include('../includes/connection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize inputs as needed
    $addId = $_POST["addId"];
    $addUsername = $_POST["addUsername"];
    $addDept = $_POST["addDept"];

    // Check if the ID already exists
    $checkIdQuery = "SELECT id FROM microproject_tools WHERE id = ?";
    $stmtCheck = $conn->prepare($checkIdQuery);
    $stmtCheck->bind_param("s", $addId);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        // ID already exists, show a popup alert and redirect back
        echo "<script>
                alert('Error: ID already exists.');
                window.location.href = 'manage_tools.php';
              </script>";
        exit();
    }

    // Close the statement for checking ID uniqueness
    $stmtCheck->close();

    // Prepare and bind the SQL statement with placeholders
    $sql = "INSERT INTO microproject_tools (id, toolname, maxlimit) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $addId, $addUsername, $addDept);

    // Execute the prepared statement
    if ($stmt->execute()) {
        // Redirect to the page where you display tools
        header("Location: manage_tools.php");
        exit();
    } else {
        // Show a popup alert for the error and redirect back
        echo "<script>
                alert('Error: Something went wrong. Please try again later.');
                window.location.href = 'manage_tools.php';
              </script>";
        exit();
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
