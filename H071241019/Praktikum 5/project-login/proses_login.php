<?php
session_start();
include 'data.php';

$username = $_POST['username'];
$password = $_POST['password'];

$foundUser = null;

foreach ($users as $user) {
    if ($user['username'] === $username) {
        $foundUser = $user;
        break;
    }
}

if ($foundUser && password_verify($password, $foundUser['password'])) {
    $_SESSION['user'] = $foundUser;
    header("Location: dashboard.php");
} else {
    $_SESSION['error'] = "Username atau password salah!";
    header("Location: login.php");
}
exit;
?>
