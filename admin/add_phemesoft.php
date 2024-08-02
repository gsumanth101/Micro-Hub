<?php
include('../includes/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $addUsername = $_POST['addUsername'];
    $addName = $_POST['addName'];

    $addPass = password_hash($_POST['addPass'], PASSWORD_DEFAULT); // Hash the password

    // Check if the username already exists
    $stmtCheck = $conn->prepare("SELECT * FROM phemesoft WHERE username = ?");
    $stmtCheck->bind_param("s", $addUsername);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        echo "Username already exists";
        $stmtCheck->close();
        $conn->close();
        header("Location: dashboard.php");
        exit();
    }

    $stmtCheck->close();

    // Insert the new Student record into the database
    $stmtInsert = $conn->prepare("INSERT INTO student (username, name, password) VALUES (?, ?, ?)");
    $stmtInsert->bind_param("sss", $addUsername, $addName, $addPass);

    if ($stmtInsert->execute()) {
        header("Location: student.php");
    } else {
        echo "<script>
                alert('Error adding student: " . $conn->error . "');
                window.location.href = 'student.php';
              </script>";
        exit();
    }

    $stmtInsert->close();
    $conn->close();
}
?>
