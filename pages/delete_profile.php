<?php
session_start();
include "connect.php";

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$message = "";

// If the user confirms deletion (POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $conn->prepare("DELETE FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        session_unset();
        session_destroy();
        header("Location: goodbye_page.html");
        exit();
    } else {
        $message = "Failed to delete your account.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Confirm Delete | Mindly</title>
  <link rel="stylesheet" href="../assets/css/reset.css">
  <link rel="stylesheet" href="../assets/css/footer.css">
  <link rel="stylesheet" href="../assets/css/profile.css">
</head>
<body>

  <main class="profile-page">

    <div class="section">
      <div class="profile-avatar">
        <img src="../assets/img/clr-primary-dark-logo.webp" alt="Mindly Logo" class="brain-image" />
        <h1 class="deleted-title">Are you sure you want to delete your account?</h1>
        <p>This action is permanent and cannot be undone.</p>
      </div>

      <?php if (!empty($message)): ?>
        <div class="error-box"><?= $message ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="action-buttons">
          <button type="submit" class="btn-danger">Yes, Delete My Account</button>
          <a href="profile.php" class="btn-secondary">Cancel</a>
        </div>
      </form>
    </div>

  </main>

  <footer class="site-footer">
    <p>© Mindly · All rights reserved</p>
    <a href="#">Privacy Policy</a>
  </footer>
</body>
</html>
