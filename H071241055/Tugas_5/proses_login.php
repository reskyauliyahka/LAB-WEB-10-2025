<?php
session_start();
require 'data.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_input = $_POST['username'];
    $password_input = $_POST['password'];
    
    $found_user = null;
    
    foreach ($users as $user) {
        if ($user['username'] === $username_input) {
            $found_user = $user;
            break;
        }        
    }

    if ($found_user && password_verify($password_input, $found_user['password'])) {
        // Login Berhasil
        $_SESSION['user_data'] = $found_user;
        header('Location: dashboard.php');
        exit;
    } else {
        // Login Gagal
        header('Location: login.php?error=1');
        exit;
    }
} else {
    // Jika file diakses langsung, kembalikan ke login
    header('Location: login.php');
    exit;
}
?>