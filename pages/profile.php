<?php
session_start();
include "connect.php";

if (!isset($_SESSION['email'])) {
  header("Location: login.html");
  exit();
}

$email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT name, email FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
  echo "User not found.";
  exit();
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profile | Mindly</title>
  <link rel="stylesheet" href="../assets/css/reset.css">
  <link rel="stylesheet" href="../assets/css/header.css">
  <link rel="stylesheet" href="../assets/css/footer.css">
  <link rel="stylesheet" href="../assets/css/profile.css">
</head>
<body>

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
      <h1 class="welcome-text">Welcome back, <?= htmlspecialchars($user['name']) ?></h1>
    </div>

    <div class="section">
      <h2>FULL NAME</h2>
      <div class="form-group">
        <input type="text" value="<?= htmlspecialchars($user['name']) ?>" readonly />
      </div>
    </div>

    <div class="section">
      <h2>EMAIL</h2>
      <div class="form-group">
        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" readonly />
      </div>
    </div>

    <div class="section">
      <h2>PASSWORD</h2>
      <div class="form-group">
        <input type="password" value="******************" readonly />
      </div>
    </div>

    <div class="action-buttons">
      <button type="button" class="btn-primary" onclick="window.location.href='edit_profile.php'">EDIT DETAILS</button>
      <button type="button" class="btn-danger" onclick="window.location.href='delete_profile.php'">DELETE ACCOUNT</button>
    </div>
  </main>

  <footer class="site-footer">
    <p>© Mindly · All rights reserved</p>
    <a href="#">Privacy Policy</a>
  </footer>
</body>
</html>
