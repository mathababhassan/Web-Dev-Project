<?php
if (!isset($_SESSION['email'])) return;

include "connect.php";

$email = $_SESSION['email'];
$remember = $_SESSION['remember'] ?? false;

if ($remember) {
    $token = bin2hex(random_bytes(32));

    $stmt = $conn->prepare("UPDATE users SET remember_token = ? WHERE email = ?");
    $stmt->bind_param("ss", $token, $email);
    $stmt->execute();

    setcookie("remember_token", $token, time() + (86400 * 30), "/", "", false, true);
} else {
    setcookie("remember_token", "", time() - 3600, "/");
    $stmt = $conn->prepare("UPDATE users SET remember_token = NULL WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
}

unset($_SESSION['remember']);
?>
