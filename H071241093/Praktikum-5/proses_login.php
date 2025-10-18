<?php
session_start();

require_once ('data.php');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION['login_error'] = "Akses tidak valid.";
    header("Location: login.php");
    exit;
}

$input_username = trim($_POST['username']);
$input_password = $_POST['password'];
$user_found = null;

foreach ($users as $user) {
    if ($user['username'] === $input_username) {
        $user_found = $user;
        break;
    }
}

if ($user_found) {
    if (password_verify($input_password, $user_found['password'])) {
        
        unset($user_found['password']);
        
        $_SESSION['user'] = $user_found; 
        
        header("Location: dashboard.php"); 
        exit;
    } else {
        $_SESSION['login_error'] = "Username atau password salah!";  
        header("Location: login.php");
        exit;
    }
} else {
    $_SESSION['login_error'] = "Username atau password salah!"; 
    header("Location: login.php");
    exit;
}