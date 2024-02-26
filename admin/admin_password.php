<?php
include "sidebar.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve values from the form
    $username = $_POST["username"];
    $newPassword = $_POST["newPassword"];

    // Validate the new password using your criteria (minimum length, uppercase, lowercase, numeric, special character)
    $passwordRegex = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/';
    if (!preg_match($passwordRegex, $newPassword)) {
        echo "<script>
                alert('Password must be at least 8 characters long and include at least 1 uppercase letter, 1 lowercase letter, 1 numeric digit, and 1 special character.');
                window.location.href = 'admin.php';
            </script>";
        exit();
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Prepare and execute the SQL query to update the hashed password
    $sql = "UPDATE admin SET password = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashedPassword, $username);

    // Execute the query
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            // Password updated successfully
            echo "<script>
                    alert('Password updated successfully.');
                    window.location.href = 'admin.php'; // Redirect to another page after success
                </script>";
            exit(); // Stop further execution to prevent the HTML below from being processed
        } else {
            // No rows affected - username not found
            echo "<script>
                    alert('Username not found. Please check the username and try again.');
                    window.location.href = 'admin_password.php';
                </script>";
        }
    } else {
        // Display an error message using a popup
        echo "<script>
                alert('Error: " . $stmt->error . ". Please try again later.');
                window.location.href = 'admin.php';
            </script>";
    }

    // Close the prepared statement
    $stmt->close();
}
?>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <!-- ... (Your existing content-header code) ... -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3>Admin Password Update</h3>
                <div class="card-tools">
                </div>
            </div>
            <div class="card-body">

                <!-- Form for updating admin password -->
                <form id="updatePasswordForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="newPassword">New Password:</label>
                        <input type="password" name="newPassword" class="form-control" required>
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