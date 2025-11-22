<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

require_once __DIR__ . '/data.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #ffe6eb;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 50px 0;
            margin: 0;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 18px;
            box-shadow: 0 6px 25px rgba(0,0,0,0.1);
            width: 90%;
            max-width: 900px;
            text-align: center;

        }

        h2 {
            color: #ff4d6d;
            font-size: 26px;
            margin-bottom: 10px;
        }

        p {
            color: #555;
            margin-bottom: 25px;
            font-size: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 15px
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #ffccd5;
            color: #333;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #fff0f2;
        }

        tr:hover {
            background-color: #ffe6eb;
            transition: 0.2s;
        }

        .btn {
            display: inline-block;
            background: #ff4d6d;
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 25px;
            font-weight: bold;
            transition: background 0.3s ease, transform 0.1s ease;
        }

        .btn:hover {
            background: #ff758f;
            transform: scale(1.05);
        }

        .logout-btn {
            background: #ff3366;
            margin-top: 35px;
        }

        .logout-btn:hover {
            background: #ff6688;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($user['username'] === 'adminxxx'): ?>
            <h2>Selamat Datang, Admin!</h2>
            <p>Berikut daftar seluruh pengguna:</p>

            <table>
                <tr>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Fakultas</th>
                    <th>Angkatan</th>
                </tr>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['username']); ?></td>
                        <td><?= htmlspecialchars($u['name']); ?></td>
                        <td><?= htmlspecialchars($u['email']); ?></td>
                        <td><?= $u['gender'] ?? '-'; ?></td>
                        <td><?= $u['faculty'] ?? '-'; ?></td>
                        <td><?= $u['batch'] ?? '-'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <h2>Selamat Datang, <?= htmlspecialchars($user['name']); ?>!</h2>
            <p>Berikut data akun Anda:</p>

            <div style="
                background:#fff6f8;
                border: 1px solid #ffccd5;
                border-radius:12px;
                padding:25px;
                margin:25px auto;
                text-align:left;
                width:70%;
                box-shadow:0 4px 10px rgba(0,0,0,0.05);
            ">
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
                <?php if (isset($user['gender'])): ?>
                    <p><strong>Gender:</strong> <?= htmlspecialchars($user['gender']); ?></p>
                <?php endif; ?>
                <?php if (isset($user['faculty'])): ?>
                    <p><strong>Fakultas:</strong> <?= htmlspecialchars($user['faculty']); ?></p>
                <?php endif; ?>
                <?php if (isset($user['batch'])): ?>
                    <p><strong>Angkatan:</strong> <?= htmlspecialchars($user['batch']); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <a href="logout.php" class="btn logout-btn">Logout</a>
    </div>
</body>
</html>
