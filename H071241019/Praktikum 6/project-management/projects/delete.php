<?php
session_start();
require_once '../config/database.php';

// Cek login dan role
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['super_admin', 'project_manager'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user = getUserData();

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$task_id = $_GET['id'];

// Check permission
if ($user['role'] === 'project_manager') {
    $stmt = $pdo->prepare("
        SELECT t.id 
        FROM tasks t 
        JOIN projects p ON t.project_id = p.id 
        WHERE t.id = ? AND p.manager_id = ?
    ");
    $stmt->execute([$task_id, $user['id']]);
    $can_delete = $stmt->fetch();
    
    if (!$can_delete) {
        header('Location: index.php?error=tidak_ada_akses');
        exit;
    }
}

try {
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$task_id]);
    
    header('Location: index.php?success=dihapus');
    exit;
} catch (PDOException $e) {
    header('Location: index.php?error=gagal_menghapus');
    exit;
}
?>