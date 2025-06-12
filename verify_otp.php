<?php
session_start();
include "connect.php";

if (!isset($_SESSION['otp_email'])) {
    header("Location: login.html");
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

            $_SESSION['email'] = $email;
            unset($_SESSION['otp_email']);

            header("Location: dashboard.html");
            exit();
        } else {
            $message = " OTP is invalid or expired.";
        }
    } else {
        $message = " User not found.";
    }
}
?>

<!-- HTML + Styling + 6 boxes + JS -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP | Mindly</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f8fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 8px rgba(0,0,0,0.08);
            width: 350px;
        }
        h2 {
            text-align: center;
            color: rgb(55, 210, 158);
        }
        .msg {
            text-align: center;
            color: red;
            margin-bottom: 10px;
        }
        .otp-container {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .otp-box {
            width: 45px;
            height: 50px;
            text-align: center;
            font-size: 22px;
            border: 2px solid #ccc;
            border-radius: 8px;
        }
        .otp-box:focus {
            border-color:rgb(55, 210, 158);
            outline: none;
        }
        button {
            width: 100%;
            padding: 10px;
            background:rgb(55, 210, 158);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover {
            background:rgb(67, 164, 107);
        }
    </style>
</head>

<body>
    <div class="box">
        <h2>Enter OTP</h2>
        <?php if ($message): ?>
            <div class="msg"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST" id="otpForm">
            <div class="otp-container">
                <input type="text" maxlength="1" name="digit1" class="otp-box" required autofocus>
                <input type="text" maxlength="1" name="digit2" class="otp-box" required>
                <input type="text" maxlength="1" name="digit3" class="otp-box" required>
                <input type="text" maxlength="1" name="digit4" class="otp-box" required>
                <input type="text" maxlength="1" name="digit5" class="otp-box" required>
                <input type="text" maxlength="1" name="digit6" class="otp-box" required>
            </div>
            <input type="hidden" name="otp" id="otp">
            <button type="submit">Verify OTP</button>
        </form>
    </div>

    <script src="otp.js"></script>
</body>
</html>
