<?php
session_start();
include 'connect.php';
require '../vendor/autoload.php';
use SendGrid\Mail\Mail;

$email = $_POST["email"];
$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30); // token only valid for 30 minutes

$sql = "UPDATE users SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $token_hash, $expiry, $email);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $resetLink = "http://localhost/WebDevProject/pages/reset_password.php?token=$token";

    $mail = new Mail();
    $mail->setFrom("mindly.service@gmail.com", "Mindly");
    $mail->addTo($email);
    $mail->setSubject("Mindly Password Reset");
    $mail->addContent("text/html", "
        Click the link below to reset your password:<br><br>
        <a href='$resetLink'>$resetLink</a><br><br>
        This link will expire in 30 minutes.");

    try {
        $sendgrid = new \SendGrid(SENDGRID_API_KEY);
        $response = $sendgrid->send($mail);
        if ($response->statusCode() === 202) {
            $_SESSION['reset_success'] = "Password reset email sent. Please check your inbox.";
        } else {
            $_SESSION['reset_error'] = "Failed to send email. Please try again later.";
        }
    } catch (Exception $e) {
        $_SESSION['reset_error'] = "SendGrid error: " . $e->getMessage();
    }
} else {
    $_SESSION['reset_error'] = "Email not found. Please try again.";
}

// Always redirect back to the form page
header("Location: forgot_password.php");
exit();
