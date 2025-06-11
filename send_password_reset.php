<?php
include 'connect.php';
require 'vendor/autoload.php';

use SendGrid\Mail\Mail;

$email = $_POST["email"];

$token = bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);

$expiry = date("Y-m-d H:i:s", time() + 60 * 30); // the token will only be valid for 30 minutes

$sql = "UPDATE users
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("sss", $token_hash, $expiry, $email);

$stmt->execute();

if ($conn->affected_rows) {
    
    $resetLink = "http://localhost/WebDevProject/reset_password.php?token=$token";

    $emailToSend = new Mail();
    $emailToSend->setFrom("mindly.service@gmail.com", "Mindly");
    $emailToSend->addTo($email);
    $emailToSend->setSubject("Mindly Password Reset");
    $emailToSend->addContent(
        "text/html",
        "Click the link below to reset your password. It expires in 30 minutes:<br><br>
        <a href='$resetLink'>$resetLink</a>"
    );

    $sendgrid = new \SendGrid(SENDGRID_API_KEY);

    try {
        $response = $sendgrid->send($emailToSend);
        if ($response->statusCode() === 202) {
            echo "Password reset email sent. Please check your inbox.";
        } else {
            echo "Failed to send email. Code: " . $response->statusCode();
        }
    } catch (Exception $e) {
        echo "SendGrid error: " . $e->getMessage();
    }
    
} else {
    echo "Email not found or update failed.";
}
