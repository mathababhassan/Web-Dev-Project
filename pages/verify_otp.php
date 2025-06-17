<?php
session_start();
include "connect.php";

if (!isset($_SESSION['otp_email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['otp_email'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $otp = $_POST['otp']; // Combined 6 digits otp from JS

    $stmt = $conn->prepare("SELECT otp_code, otp_expiry FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $current_time = date("Y-m-d H:i:s");
        if ($row['otp_code'] === $otp && $current_time < $row['otp_expiry']) {
            // OTP is correct and not expired
            $clear = $conn->prepare("UPDATE users SET otp_code = NULL, otp_expiry = NULL WHERE email = ?");
            $clear->bind_param("s", $email);
            $clear->execute();

            // Get user_id
            $id_stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
            $id_stmt->bind_param("s", $email);
            $id_stmt->execute();
            $id_result = $id_stmt->get_result();
            $user = $id_result->fetch_assoc();

            // Set session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $email;
            unset($_SESSION['otp_email']);

            include "remember_handler.php";

            header("Location: dashboard.php");
            exit();

        } else {
            $message = " OTP is invalid or expired.";
        }
    } else {
        $message = " User not found.";
    }
}
?>

<!-- HTML  -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP | Mindly</title>
    <link rel="stylesheet" href="../assets/css/auth.css" />
       
</head>

<body>
    <div class="box">
        <h2>Enter OTP</h2>
        <p class="sub-text">We've sent a 6-digit verification code to your email. Please check your inbox or spam folder.</p>
        <?php if ($message): ?>
            <div class="msg"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST" id="otpForm">
            <div class="input-container">
                <input type="text" maxlength="1" name="digit1" class="input-box" required autofocus>
                <input type="text" maxlength="1" name="digit2" class="input-box" required>
                <input type="text" maxlength="1" name="digit3" class="input-box" required>
                <input type="text" maxlength="1" name="digit4" class="input-box" required>
                <input type="text" maxlength="1" name="digit5" class="input-box" required>
                <input type="text" maxlength="1" name="digit6" class="input-box" required>
            </div>
            <input type="hidden" name="otp" id="otp">
            <button type="submit">Verify OTP</button>
        </form>
    </div>

    <script src="otp.js"></script>
</body>
</html>
