<?php 
session_start();
include "connect.php";
use SendGrid\Mail\Mail;
require '../vendor/autoload.php';

$error = "";
$success = "";


if ($_SERVER["REQUEST_METHOD"] === "POST"){
   $email=$_POST['email'];
   $password=$_POST['password'];

   //email format validation
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Please enter a valid email address.";}
   
    if (empty($error)) {
    // Get the hashed password from DB
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password_hash'])) {
            // Valid login — generate OTP
            $otp = rand(100000, 999999); // 6 digit OTP
            $expiry = date("Y-m-d H:i:s", time() + (5 * 60));

            $update = $conn->prepare("UPDATE users SET otp_code = ?, otp_expiry = ? WHERE email = ?");
            $update->bind_param("sss", $otp, $expiry, $email);
            $update->execute();

            // Send OTP via SendGrid
            $mail = new Mail();
            $mail->setFrom("mindly.service@gmail.com", "Mindly");
            $mail->setSubject("Login OTP Code");
            $mail->addTo($email);
            $mail->addContent("text/plain", "Your OTP is: $otp. It expires in 5 minutes.");

            $sendgrid = new \SendGrid(SENDGRID_API_KEY);

            try {
                $sendgrid->send($mail);
                $_SESSION['otp_email'] = $email;
                $_SESSION['remember'] = isset($_POST['remember']);
                header("Location: verify_otp.php");
                exit();

            } catch (Exception $e) {
                $error = "Error sending OTP: " . $e->getMessage();
            } }
           else {
            // Wrong password
            $error = "Incorrect password.";
        }

    } else {
        // Email doesn't exist
        $error = "No account matches that email address.";
    }
} }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login | Mindly</title>
  <link rel="stylesheet" href="../assets/css/styles.css" />
</head>
<body>

  <main class="auth-page">

    <!-- Left Section: Login Form -->
    <section class="auth-form-section">
      <h1 class="auth-title">Login</h1>

       <?php if (!empty($error)): ?>
        <div class="error-box"><?= $error ?></div>
        <?php endif; ?>

       <?php if (!empty($success)): ?>
        <div class="success-box"><?= $success ?></div>
        <?php endif; ?>
      
      <form action="login.php" method="POST" class="auth-form">

        <label for="email">EMAIL</label>
        <input type="email" id="email" name="email" placeholder="email@example.com" required />

        <label for="password">PASSWORD</label>
        <input type="password" id="password" name="password" placeholder="Password" required />

        <button type="submit" class="btn btn-primary">Login</button>

        <div class="auth-options">
          <label><input type="checkbox" name="remember" /> Remember me</label>
          <a href="forgot_password.php" class="link-small">Forgot password</a>
        </div>
      </form>

    </section>

    <!-- Right Section: Visual + Redirect -->
    <section class="auth-info-section">
      <img src="../assets/img/clr-background-logo.webp" alt="Mindly Logo" class="logo" />
      <h2 class="auth-greeting">Welcome to login</h2>
      <p>Don't have an account?</p>
      <a href="sign_up.php" class="btn btn-secondary">Sign Up</a>
    </section>

  </main>
</body>
</html>
