<?php
include('../includes/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $editName = $_POST['editName'];
    $editSec = $_POST['editSec'];
    $editYear = $_POST['editYear'];
    $editdept = $_POST['editdept'];
    $editfaculty = $_POST['editfaculty'];
    $StudentUsername = $_POST['StudentUsername'];

    // Update the Student record in the database
    $sql = "UPDATE student SET name='$editName', section='$editSec', year='$editYear', dept='$editdept',faculty='$editfaculty' WHERE username='$StudentUsername'";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect to the page where you display coordinators
        header("Location: student.php");
        exit();
    } else {
        echo "<script>
                alert('Error updating student: " . $conn->error . "');
                window.location.href = 'student.php';
              </script>";
        exit();
    }
    
    $conn->close();
}
?>
