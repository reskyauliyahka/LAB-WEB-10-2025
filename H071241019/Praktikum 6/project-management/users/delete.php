<?php
session_start();
require_once '../config/database.php';

// Cek login dan role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: ../auth/login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_GET['id'];

// Cek apakah user mencoba menghapus dirinya sendiri
if ($user_id == $_SESSION['user_id']) {
    header('Location: index.php?error=tidak_bisa_menghapus_diri_sendiri');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    
    header('Location: index.php?success=dihapus');
    exit;
} catch (PDOException $e) {
    header('Location: index.php?error=gagal_menghapus');
    exit;
}
?>