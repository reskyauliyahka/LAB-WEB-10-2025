<?php
session_start();

// Kosongkan semua variabel session
$_SESSION = array();

// Hancurkan session
session_destroy();

// Arahkan kembali ke halaman login
header('Location: login.php');
exit;
?>