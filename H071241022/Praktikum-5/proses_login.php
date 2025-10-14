<?php

session_start();

require_once __DIR__ . '/data.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $input_username = $_POST['username'];
    $input_password = $_POST['password'];
    $found_user = null;

    foreach($users as $user){
        if($user['username'] === $input_username){
            $found_user = $user;
            break;
        }
    }

    if ($found_user){
        if(password_verify($input_password, $found_user['password'])){
            $_SESSION['user'] = $found_user;
            header("Location: dashboard.php");
            exit;
        } else {
            $_SESSION['error'] = "Password salah!";
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Username tidak ditemukan!";
        header("Location: login.php");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>