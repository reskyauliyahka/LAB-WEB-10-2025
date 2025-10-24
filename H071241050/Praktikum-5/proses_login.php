<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php'); //perintah header() untuk redirect
    exit;
}

require 'data.php'; // memuat $users

$inputUsername = isset($_POST['username']) ? trim($_POST['username']) : '';
$inputPassword = isset($_POST['password']) ? $_POST['password'] : '';

if ($inputUsername === '' || $inputPassword === '') {
    $_SESSION['error'] = 'Username dan password harus diisi.';
    header('Location: login.php');
    exit;
}

// cari user berdasarkan username
$found = null;
foreach ($users as $u) {
    if (isset($u['username']) && $u['username'] === $inputUsername) {
        $found = $u;
        break;
    }
}

if (!$found) {
    $_SESSION['error'] = 'Username atau password salah!';
    header('Location: login.php');
    exit;
}

// verifikasi password
if (!isset($found['password']) || !password_verify($inputPassword, $found['password'])) {
    $_SESSION['error'] = 'Username atau password salah!';
    header('Location: login.php');
    exit;
}

// sukses: set session user (jangan simpan password di session)
unset($found['password']);
session_regenerate_id(true);
$_SESSION['user'] = $found;

header('Location: dashboard.php');
exit;
