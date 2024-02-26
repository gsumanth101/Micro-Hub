<?php
include('../includes/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $StudentUsername = $_POST['StudentUsername'];

    // Delete the Student record from the database
    $sql = "DELETE FROM Student WHERE username='$StudentUsername'";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect to the page where you display students
        header("Location: student.php");
        exit();
    } else {
        // Show a popup alert for the error and redirect back
        echo "<script>
                alert('Error deleting student: " . $conn->error . "');
                window.location.href = 'student.php';
              </script>";
        exit();
    }
}

// Close the database connection
$conn->close();
?>
