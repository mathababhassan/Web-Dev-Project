<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirm'])) {
    session_unset();
    session_destroy();
    setcookie("remember_token", "", time() - 3600, "/");
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Logout | Mindly</title>
  <link rel="stylesheet" href="../assets/css/logout.css">
</head>
<body>
  <div class="box">
    <h2>Are you sure you want to log out?</h2>
    <form method="POST" class="confirm-buttons">
    <button type="submit" name="confirm" class="nav-button">Yes, Log Out</button>
    <a href="dashboard.php" class="cancel-link">Cancel</a>
    </form>

  </div>
</body>
</html>
