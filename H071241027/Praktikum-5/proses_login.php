<?php
session_start();

$users = [
    [
        'email' => 'admin@gmail.com',
        'username' => 'adminxxx',
        'name' => 'Admin',
        'password' => password_hash('admin123', PASSWORD_DEFAULT)
    ],
    [
        'email' => 'wanul@gmail.com',
        'username' => 'wanul25',
        'name' => 'Moch Ichwanul',
        'password' => password_hash('wanul123', PASSWORD_DEFAULT),
        'gender' => 'Male',
        'faculty' => 'MIPA',
        'batch' => '2024'
    ],
    [
        'email' => 'alif@gmail.com',
        'username' => 'ervin',
        'name' => 'Muhammad Alif Sakti',
        'password' => password_hash('alif123', PASSWORD_DEFAULT),
        'gender' => 'Male',
        'faculty' => 'Hukum',
        'batch' => '2023'
    ],
    [
        'email' => 'ucup@gmail.com',
        'username' => 'ucup77',
        'name' => 'Syech Yusuf',
        'password' => password_hash('yusuf123', PASSWORD_DEFAULT),
        'gender' => 'Male',
        'faculty' => 'Keperawatan',
        'batch' => '2021'
    ],
    
];

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