<?php
// Selalu mulai session, bahkan untuk menghancurkannya
session_start();

// 1. Hapus semua variabel session
$_SESSION = array();

// 2. Hancurkan session
session_destroy();

// 3. Alihkan ke halaman login (index.php)
header("Location: index.php");
exit();
?>