<?php
session_start();
include "connect.php";

if (!isset($_SESSION['email'])) {
  header("Location: login.html");
  exit();
}

$email = $_SESSION['email'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = trim($_POST['name']);
  $newEmail = trim($_POST['email']);
  $currentPassword = $_POST['current_password'];
  $newPassword = $_POST['new_password'];
  $confirmPassword = $_POST['confirm_password'];

  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
    $_SESSION['message'] = "Current password is incorrect.";
    header("Location: edit_profile.php");
    exit();
  }

  if (!empty($newPassword)) {
    if ($newPassword !== $confirmPassword) {
      $_SESSION['message'] = "New passwords do not match.";
      header("Location: edit_profile.php");
      exit();
    }
    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
  } else {
    $newPasswordHash = $user['password_hash'];
  }

  $update = $conn->prepare("UPDATE users SET name = ?, email = ?, password_hash = ? WHERE email = ?");
  $update->bind_param("ssss", $name, $newEmail, $newPasswordHash, $email);

  if ($update->execute()) {
    $_SESSION['email'] = $newEmail;
    $_SESSION['message'] = "Profile updated successfully.";
    header("Location: profile.php");
    exit();
  } else {
    $_SESSION['message'] = "Error updating profile.";
    header("Location: edit_profile.php");
    exit();
  }
} else {
  $stmt = $conn->prepare("SELECT name, email FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Profile | Mindly</title>
  <link rel="stylesheet" href="../assets/css/reset.css">
  <link rel="stylesheet" href="../assets/css/header.css">
  <link rel="stylesheet" href="../assets/css/footer.css">
  <link rel="stylesheet" href="../assets/css/profile.css">
</head>
<body>

  <!-- Navbar -->
  <header class="navbar">
    <div class="logo-area">
      <img src="../assets/img/clr-primary-dark-logo.webp" alt="Mindly Logo" class="logo" />
      <span class="site-name">Mindly</span>
    </div>
    <nav class="nav-links">
      <a href="dashboard.php">Dashboard</a>
      <a href="profile.php">Profile</a>
      <a href="logout.php">Logout</a>
    </nav>
  </header>

  <main class="profile-page">

    <div class="profile-avatar">
      <div class="avatar-circle">
        <svg class="avatar-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" fill="currentColor"/>
          <path d="M12 14C7.58172 14 4 17.5817 4 22H20C20 17.5817 16.4183 14 12 14Z" fill="currentColor"/>
        </svg>
      </div>
      <h1 class="welcome-text">Edit Profile</h1>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
      <div class="success-box"><?= $_SESSION['message'] ?></div>
      <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <form method="post" action="edit_profile.php">

      <div class="section">
        <h2>FULL NAME</h2>
        <div class="form-group">
          <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required />
        </div>
      </div>

      <div class="section">
        <h2>EMAIL</h2>
        <div class="form-group">
          <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required />
        </div>
      </div>

      <div class="section">
        <h2>CURRENT PASSWORD</h2>
        <div class="form-group">
          <input type="password" id="current_password" name="current_password" placeholder="Enter current password" required />
        </div>
      </div>

      <div class="section">
        <h2>NEW PASSWORD</h2>
        <div class="form-group">
          <input type="password" id="new_password" name="new_password" placeholder="Enter new password (leave blank to keep current)" />
        </div>
      </div>

      <div class="section">
        <h2>CONFIRM NEW PASSWORD</h2>
        <div class="form-group">
          <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" />
        </div>
      </div>

      <div class="action-buttons">
        <button type="submit" class="btn-primary">SAVE CHANGES</button>
        <button type="button" class="btn-secondary" onclick="window.location.href='profile.php'">CANCEL</button>
      </div>

    </form>
  </main>

  <footer class="site-footer">
    <p>© Mindly · All rights reserved</p>
    <a href="#">Privacy Policy</a>
  </footer>

  <script>
    document.getElementById('confirm_password').addEventListener('input', function () {
      const newPassword = document.getElementById('new_password').value;
      const confirmPassword = this.value;

      if (newPassword && confirmPassword && newPassword !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
      } else {
        this.setCustomValidity('');
      }
    });

    document.getElementById('new_password').addEventListener('input', function () {
      const confirmPassword = document.getElementById('confirm_password');

      if (this.value === '') {
        confirmPassword.removeAttribute('required');
        confirmPassword.value = '';
      } else {
        confirmPassword.setAttribute('required', 'required');
      }
    });
  </script>
</body>
</html>
