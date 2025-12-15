<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

// Cek login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user = getUserData();

// Only team members can update their own task status
if ($user['role'] !== 'team_member') {
    echo json_encode(['success' => false, 'message' => 'Forbidden']);
    exit;
}

if (!isset($_GET['id']) || !isset($_GET['status'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$task_id = $_GET['id'];
$new_status = $_GET['status'];

// Verify that the task is assigned to this user
$stmt = $pdo->prepare("SELECT id FROM tasks WHERE id = ? AND assigned_to = ?");
$stmt->execute([$task_id, $user['id']]);
$task = $stmt->fetch();

if (!$task) {
    echo json_encode(['success' => false, 'message' => 'Task not found or not assigned to you']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $task_id]);
    
    echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>