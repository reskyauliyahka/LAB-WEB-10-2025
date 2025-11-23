<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Super Admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Super Admin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f4f4f4; }
        .navbar { background-color: #333; overflow: hidden; }
        .navbar a { float: left; display: block; color: white; text-align: center; padding: 14px 20px; text-decoration: none; }
        .navbar a:hover { background-color: #ddd; color: black; }
        .navbar a.logout { float: right; background-color: #d9534f; }
        .container { padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        .form-container { background: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        input, select, button { padding: 10px; margin-top: 5px; width: 100%; box-sizing: border-box; }
        button { background-col or: #007bff; color: white; border: none; cursor: pointer; }
        .btn-delete { background-color: #d9534f; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>

<div class="navbar">
    <a href="index.php">Dashboard</a>
    <a href="manage_users.php">Kelola Pengguna</a>
    <a href="manage_projects.php">Kelola Proyek</a>
    <a href="../logout.php" class="logout">Logout</a>
</div>

<div class="container">