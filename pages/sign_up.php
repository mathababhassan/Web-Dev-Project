<?php
session_start();
include "connect.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $password = $_POST['password'];
  $confirm = $_POST['confirm_password'];

  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows > 0) {
    $error = "Email already exists! <a href='login.php'>Login instead</a>.";
  } else if ($password !== $confirm) {
    $error = "Passwords do not match!";
  } else {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users(name, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hash);

    if ($stmt->execute()) {
      $success = "Account created! You can now <a href='login.php'>log in</a>.";
    } else {
      $error = "Something went wrong. Please try again.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up | Mindly</title>
  <link rel="stylesheet" href="../assets/css/styles.css" />
</head>
<body>

  <main class="auth-page">
    <!-- Left Section: Sign Up Form -->
    <section class="auth-form-section">
      <h1 class="auth-title">Sign Up</h1>

      <?php if (!empty($error)): ?>
        <div class="error-box"><?= $error ?></div>
        <?php endif; ?>

       <?php if (!empty($success)): ?>
        <div class="success-box"><?= $success ?></div>
        <?php endif; ?>

        <div id="js-error-box" class="error-box" style="display:none;"></div>

      <form action="sign_up.php" method="POST" class="auth-form">

        <label for="name">FULL NAME</label>
        <input type="text" id="name" name="name" placeholder="Spongebob Squarepants" required />

        <label for="email">EMAIL</label>
        <input type="email" id="email" name="email" placeholder="spongebob@krustycrab.com" required />

        <label for="password">PASSWORD</label>
        <input type="password" id="password" name="password" placeholder="Password" required />
      
        <div id="power-point" style="height: 8px; width: 1%; background: #ccc; margin-top: 6px; transition: width 0.3s;"></div>
        <small id="strength-text" style="color: white; display: block; margin-top: 4px;"></small>

        <label for="confirm">CONFIRM PASSWORD</label>
        <input type="password" id="confirm" name="confirm_password" placeholder="Password" required />

        <button type="submit" class="btn btn-primary">Sign Up</button>

        <p class="auth-redirect">
          Already have an account? <a href="login.php" class="link-small">Login</a>
        </p>
      </form>
    </section>
    </div>

    <!-- Right Section: Logo -->
    <section class="auth-info-section">
      <img src="../assets/img/clr-background-logo.webp" alt="Mindly Logo" class="logo" />
    </section>

  </main>

<script src="sign_up.js"></script>
<script src="password_strength_check.js"></script>

</body>
</html>
