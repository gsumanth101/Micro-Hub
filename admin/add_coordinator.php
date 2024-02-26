<?php
include('../includes/connection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize inputs as needed
    $addUsername = $_POST["addUsername"];
    $addName = $_POST["addName"];
    $addEmail = $_POST["addEmail"];
    $addDept = $_POST["addDept"];
    $addPass = password_hash($_POST["addPass"], PASSWORD_DEFAULT); // Hash the password

    // Check if the username is unique
    $checkUsernameQuery = "SELECT id FROM coordinator WHERE username = ?";
    $stmtCheck = $conn->prepare($checkUsernameQuery);
    $stmtCheck->bind_param("s", $addUsername);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        // Username already exists, show a popup alert and redirect back
        echo "<script>
                alert('Error: Username already exists. Please choose a different username.');
                window.location.href = 'coordinator.php';
              </script>";
        exit();
    }

    // Close the statement for checking username uniqueness
    $stmtCheck->close();

    // Prepare and bind the SQL statement with placeholders
    $sql = "INSERT INTO coordinator (username, name, email, Dept, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $addUsername, $addName, $addEmail, $addDept, $addPass);

    // Execute the prepared statement
    if ($stmt->execute()) {
        // Redirect to the page where you display coordinators
        header("Location: coordinator.php");
        exit();
    } else {
        // Show a popup alert for the error and redirect back
        echo "<script>
                alert('Error: Something went wrong. Please try again later.');
                window.location.href = 'coordinator.php';
              </script>";
        exit();
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
