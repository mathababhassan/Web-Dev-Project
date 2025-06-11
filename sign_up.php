<?php 
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $name= $_POST['name'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $confirm_password=$_POST['confirm_password'];

    // sanitize functions
    $name  = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Check email exists
    $checkEmail = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmail);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email Address Already Exists!";
    } else {
        if ($password !== $confirm_password) {
            echo "Passwords do not match!";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $insertQuery = "INSERT INTO users(name, email, password_hash) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("sss", $name, $email, $password_hash);

            if ($stmt->execute()) {
                header("Location: login.html"); 
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }
}
?>

    
