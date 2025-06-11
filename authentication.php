<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.html"); // Redirect if not logged in 
}
?>
