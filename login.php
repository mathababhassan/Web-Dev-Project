<?php 
session_start();
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST"){
   $email=$_POST['email'];
   $password=$_POST['password'];
   
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
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['name'];
            header("Location: dashboard.html");
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No account matches that email address.";
    }
}
?>
