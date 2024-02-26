<?php
include('../includes/connection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize inputs as needed
    $addId = $_POST["addId"];
    $addname = $_POST["addname"];
    $addDate = $_POST["addDate"];
    $addCollege_name = $_POST["addCollege_name"];
    $addBrochure = $_POST["addBrochure"];
    $addWebsite = $_POST["addWebsite"];

    // Check if the ID already exists
    $checkIdQuery = "SELECT id FROM external_events WHERE id = ?";
    $stmtCheck = $conn->prepare($checkIdQuery);
    $stmtCheck->bind_param("s", $addId);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        // ID already exists, show a popup alert and redirect back
        echo "<script>
                alert('Error: ID already exists.');
                window.location.href = 'manage_external_events.php';
              </script>";
        exit();
    }

    // Close the statement for checking ID uniqueness
    $stmtCheck->close();

    // Prepare and bind the SQL statement with placeholders
    $sql = "INSERT INTO external_events (id, name, date,college_name,brochure,website) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $addId, $addname, $addDate,$addCollege_name,$addBrochure,$addWebsite);

    // Execute the prepared statement
    if ($stmt->execute()) {
        // Redirect to the page where you display tools
        header("Location: manage_external_events.php");
        exit();
    } else {
        // Show a popup alert for the error and redirect back
        echo "<script>
                alert('Error: Something went wrong. Please try again later.');
                window.location.href = 'manage_external_events.php';
              </script>";
        exit();
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
