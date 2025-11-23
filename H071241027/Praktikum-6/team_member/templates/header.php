<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Team Member') {
    header("Location: ../login.php");
    exit();
}

$member_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Team Member</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .task-card {
            background: white;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 5px solid #007bff;
            border-radius: 5px;
        }
        .task-card h3 { margin-top: 0; }
        .project-title { font-size: 0.9em; color: #6c757d; }
        select, button { padding: 8px; }
    </style>
</head>
<body>

<div class="navbar">
    <a href="index.php">Dashboard Tugas Saya</a>
    <a href="../logout.php" class="logout">Logout</a>
</div>

<div class="container">