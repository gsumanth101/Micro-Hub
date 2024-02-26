<?php
include('../includes/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $addUsername = $_POST['addUsername'];
    $addName = $_POST['addName'];
    $addSec = $_POST['addSec'];
    $addYear = $_POST['addYear'];
    $adddept = $_POST['adddept'];
    $addfaculty = $_POST['addfaculty'];
    $addPass = password_hash($_POST['addPass'], PASSWORD_DEFAULT); // Hash the password

    // Check if the username already exists
    $stmtCheck = $conn->prepare("SELECT * FROM Student WHERE username = ?");
    $stmtCheck->bind_param("s", $addUsername);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        echo "Username already exists";
        $stmtCheck->close();
        $conn->close();
        header("Location: student.php");
        exit();
    }

    $stmtCheck->close();

    // Insert the new Student record into the database
    $stmtInsert = $conn->prepare("INSERT INTO Student (username, name,faculty, section,  year, dept, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmtInsert->bind_param("ssssss", $addUsername, $addName,$addfaculty ,$addSec, $addYear, $adddept, $addPass);

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
