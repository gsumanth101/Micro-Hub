<?php
include "sidebar.php";

$message="";

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve values from the form
    $username = $_SESSION["username"];
    $currentPassword = $_POST["currentPassword"];
    $newPassword = $_POST["newPassword"];
    $confirmPassword = $_POST["confirmPassword"];

    // Check if the new password and confirm password match
    if ($newPassword !== $confirmPassword) {
        echo "<script>
                alert('New password and confirm password do not match.');
                window.location.href = 'update_password.php';
            </script>";
        exit();
    }

    // Validate the new password using a regular expression
    $passwordRegex = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/';
    if (!preg_match($passwordRegex, $newPassword)) {
        echo "<script>
                alert('Password must be at least 8 characters long and include at least 1 uppercase letter, 1 lowercase letter, 1 numeric digit, and 1 special character.');
                window.location.href = 'update_password.php';
            </script>";
        exit();
    }

    // Verify the current password before updating
    $sqlVerify = "SELECT password FROM student WHERE username = ?";
    $stmtVerify = $conn->prepare($sqlVerify);
    $stmtVerify->bind_param("s", $username);
    $stmtVerify->execute();
    $stmtVerify->bind_result($storedPassword);

    if ($stmtVerify->fetch() && password_verify($currentPassword, $storedPassword)) {
        // Close the result set
        $stmtVerify->close();

        // Hash the new password
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Prepare and execute the SQL query to update the password
        $sqlUpdate = "UPDATE student SET password = ? WHERE username = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("ss", $hashedNewPassword, $username);

        if ($stmtUpdate->execute()) {
            // Password updated successfully
            echo "<script>
                    alert('Password updated successfully.');
                    window.location.href = 'update_password.php'; // Redirect to another page after success
                </script>";
            exit();
        } else {
            // Display an error message using a popup
            echo "<script>
                    alert('Error: " . $stmtUpdate->error . ". Please try again later.');
                </script>";
        }

        // Close the prepared statement
        $stmtUpdate->close();
    } else {
        // Current password is incorrect
        echo "<script>
                alert('Incorrect current password. Please try again.');
            </script>";
    }

    // Close the prepared statement for verification
    $stmtVerify->close();
}
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="card-header">
            <div class="card-tools">
                <!-- Additional tools if needed -->
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header bg-gradient-navy">
                <h3 class="card-title">Password Update</h3>
            </div>
            <div class="card-body">
                <!-- Form for updating student password -->
                <form id="updatePasswordForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label for="currentPassword">Current Password:</label>
                        <input type="password" name="currentPassword" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="newPassword">New Password:</label>
                        <input type="password" name="newPassword" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password:</label>
                        <input type="password" name="confirmPassword" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Password</button>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
include "footer.html";
?>
