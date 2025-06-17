<?php
include 'connect.php';

if (!isset($_GET["token"]) || empty($_GET["token"])) {
    die("Missing token in URL.");
}
$token = $_GET["token"];

$token_hash = hash("sha256", $token);

$sql = "SELECT * FROM users WHERE reset_token_hash = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user === null) {
    die("Invalid or unknown token.");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("This reset link has expired.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>

<div class="box">
    <h2>Reset Password</h2>
    <p class="sub-text">Enter and confirm your new password below.</p>

    <form method="post" action="process_reset_password.php">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <input type="password" id="password" name="password" class="input-field" placeholder="New password" required>

        <input type="password" id="password_confirmation" name="password_confirmation"
        class="input-field" placeholder="Confirm password" required>

        <button type="submit">Send</button>
    </form>
</div>

</body>
</html>
