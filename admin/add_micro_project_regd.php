<?php
include('../includes/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $addUsername = $_POST['addUsername'];
    $addName = $_POST['addName'];
    $mentor = $_POST['mentor'];
    $reviewer= $_POST['reviewer'];


    // Check if the username already exists
    $stmtCheck = $conn->prepare("SELECT * FROM micro_project_registration WHERE username = ?");
    $stmtCheck->bind_param("s", $addUsername);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        echo "Username already exists";
        $stmtCheck->close();
        $conn->close();
        header("Location: micro_regd.php");
        exit();
    }

    $stmtCheck->close();

    // Insert the new micro_project_registration record into the database
    $stmtInsert = $conn->prepare("INSERT INTO micro_project_registration (username, name, mentor_name,  reviewer_name) VALUES (?, ?, ?, ?)");
    $stmtInsert->bind_param("ssss", $addUsername, $addName, $mentor, $reviewer);

    if ($stmtInsert->execute()) {
        header("Location: micro_regd.php");
    } else {
        echo "<script>
                alert('Error adding micro_regd: " . $conn->error . "');
                window.location.href = 'micro_regd.php';
              </script>";
        exit();
    }

    $stmtInsert->close();
    $conn->close();
}
?>