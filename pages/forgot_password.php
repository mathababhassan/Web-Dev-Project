<?php
session_start();
$error = $_SESSION['reset_error'] ?? "";
$success = $_SESSION['reset_success'] ?? "";
unset($_SESSION['reset_error'], $_SESSION['reset_success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password | Mindly</title>
    <link rel="stylesheet" href="../assets/css/auth.css" />

</head>
<body>
<div class="box">
    <?php if ($error): ?>
        <div class="error-box"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success-box"><?= $success ?></div>
    <?php endif; ?>

    <?php if (!$success): ?>
        <h2>Forgot Password</h2>
        <p class="sub-text">Enter your registered email and we'll send you a password reset link.</p>
        <form method="POST" action="send_password_reset.php">
            <input type="email" name="email" class="input-field" placeholder="email@example.com" required>
            <button type="submit">Send Reset Link</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
