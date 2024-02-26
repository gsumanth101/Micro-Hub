<?php
include('includes/connection.php');
$email = $_POST["email"];


$token = bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);

$expiry = date("Y-m-d H:i:s", time() + 60 * 10);


$sql = "UPDATE student
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("sss", $token_hash, $expiry, $email);

$stmt->execute();

$stmt1 = $conn->prepare("SELECT * FROM student WHERE BINARY email = ?");
$stmt1->bind_param("s", $email);
$stmt1->execute();
$result = $stmt1->get_result();
$name ="";
if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    $name = $userData['name'];
}

if ($conn->affected_rows) {

    
    $mail = require __DIR__ . "/mailer.php";

    $mail->setFrom("contact.microhub@gmail.com","MicroHub");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END
    Dear $name, <br><br>
    
    You have requested for password reset. Here is the Link to reset your password. This Link is valid only for 10 Minutes.<br>
    Click <a href="http://localhost/SDT/reset-password.php?token=$token">here</a> 
    to reset your password.<br><br><br>
    
    Thanks & Regards,<br>
    Team, <br> 
    MicroHub,<br>
    Software Development Team -- KAREOSS.
    END;

    try {

        $mail->send();

    } catch (Exception $e) {

        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";

    }

}


echo "<script>
alert('Message sent, please check your inbox/Spam.');
window.location.href = 'studentlogin.php'; // Redirect to another page after success
</script>";
