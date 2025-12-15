<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Project Manager') {
    header("Location: ../login.php");
    exit();
}

$manager_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Project Manager</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Anda bisa menambahkan styling spesifik di sini jika perlu */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="date"], textarea, select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .full-width { grid-column: 1 / -1; }
    </style>
</head>
<body>

<div class="navbar">
    <a href="index.php">Dashboard</a>
    <a href="manage_projects.php">Kelola Proyek Saya</a>
    <a href="../logout.php" class="logout">Logout</a>
</div>

<div class="container">