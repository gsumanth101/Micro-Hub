<?php
include('../includes/connection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize inputs as needed

    $facultyUsername = $_POST["facultyUsername"];
    $editName = $_POST["editName"];
    $editEmail = $_POST["editEmail"];
    $editSection = $_POST["editSection"];
    $editdept = $_POST["editdept"];
    $editrole = $_POST["role"];

    // Update faculty in the database
    $sql = "UPDATE faculty SET name='$editName', email='$editEmail',section='$editSection', dept='$editdept', role='$editrole' WHERE username='$facultyUsername'";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the page where you display facultys
        header("Location: faculty.php");
        exit();
    } else {
        // Show a popup alert for the error and redirect back
        echo "<script>
                alert('Error updating faculty: " . $conn->error . "');
                window.location.href = 'faculty.php';
              </script>";
        exit();
    }
}

// Close the database connection
$conn->close();
?>
