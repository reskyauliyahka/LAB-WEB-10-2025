<?php
session_start();
include 'data.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$isAdmin = $user['username'] === 'adminxxx';
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<style>
    body {
        font-family: Arial;
        background: #eef2f3;
        padding: 20px;
    }

    .header {
        font-size: 24px;
        margin-bottom: 10px;
        color: #333;
        font-weight: bold;
    }

    .sub {
        color:#555;
        margin-bottom: 20px;
    }

    .logout {
        background: #ff4b5c;
        padding: 10px 18px;
        color: white;
        text-decoration:none;
        float:right;
        border-radius:6px;
        transition:0.3s;
    }

    .logout:hover { background:#d63b49; }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top:20px;
        background:white;
        border-radius:12px;
        overflow:hidden;
    }

    th {
        background:#5B86E5;
        color:white;
        padding:12px;
        text-align:left;
    }

    td {
        padding:10px;
        border-bottom:1px solid #ddd;
    }

    tr:hover { background:#f1f1f1; }

    .card {
        background:white;
        padding:15px;
        width:300px;
        border-radius:12px;
        box-shadow:0 0 10px rgba(0,0,0,0.1);
        margin-bottom:20px;
    }

</style>
</head>
<body>

<a class="logout" href="logout.php">Logout</a>

<h2 class="header">
    <?= $isAdmin ? "Selamat Datang, Admin!" : "Selamat Datang, " . $user['name'] . "!" ?>
</h2>
<p class="sub"><?= $isAdmin ? "Mode Administrator" : "Mode Pengguna" ?></p>

<?php if ($isAdmin): ?>

    <table>
        <tr>
            <th>Email</th>
            <th>Username</th>
            <th>Nama</th>
            <th>Gender</th>
            <th>Fakultas</th>
            <th>Angkatan</th>
        </tr>

        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['email'] ?></td>
            <td><?= $u['username'] ?></td>
            <td><?= $u['name'] ?></td>
            <td><?= $u['gender'] ?? '-' ?></td>
            <td><?= $u['faculty'] ?? '-' ?></td>
            <td><?= $u['batch'] ?? '-' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

<?php else: ?>

<div class="card">
    <h3>Profil Kamu</h3>
    <hr>
    <?php foreach ($user as $key => $value): ?>
        <?php if ($key !== 'password'): ?>
            <p><strong><?= ucfirst($key) ?>:</strong> <?= $value ?></p>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<?php endif; ?>

</body>
</html>
