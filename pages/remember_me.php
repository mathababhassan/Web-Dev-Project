<?php
include "connect.php";

//  Only try auto-login if user not already logged in
if (!isset($_SESSION['email']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['name'];
    }
}
?>
