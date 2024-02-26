<?php
include('includes/connection.php');
$token = $_POST["token"];

$token_hash = hash("sha256", $token);


$sql = "SELECT * FROM student
        WHERE reset_token_hash = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("token not found");
}

else if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("<script>
    alert('token has expired');
    window.location.href = 'http://localhost/SDT/reset-password.php?token=$token';
</script>");
}

else if (strlen($_POST["password"]) < 8) {
    die("<script>
    alert('Password must be at least 8 characters');
    window.location.href = 'http://localhost/SDT/reset-password.php?token=$token';
</script>");
}

else if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
   die("<script>
    alert('Password must contain at least one letter');
    window.location.href = 'http://localhost/SDT/reset-password.php?token=$token';
</script>");
}

else if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("<script>
    alert('Password must contain at least one number');
    window.location.href = 'http://localhost/SDT/reset-password.php?token=$token';
    </script>");
}

else if ($_POST["password"] !== $_POST["password_confirmation"]) {
    echo("<script>
    alert('Password not matched');
    window.location.href = 'http://localhost/SDT/reset-password.php?token=$token';
    </script>");
}

else{

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$sql = "UPDATE student
        SET password = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("ss", $password_hash, $user["id"]);

$stmt->execute();

echo "<script>
alert('Password updated successfully.');
window.location.href = 'studentlogin.php'; // Redirect to another page after success
</script>";
}
$conn->close();
$stmt->close();
?>