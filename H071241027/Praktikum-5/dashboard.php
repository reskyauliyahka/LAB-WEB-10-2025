<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$current_user = $_SESSION['user'];
$is_admin = $current_user['username'] === 'adminxxx';

$users_data = [
    [
        'email' => 'admin@gmail.com',
        'username' => 'adminxxx',
        'name' => 'Admin'
    ],
    [
        'email' => 'naldi@gmail.com',
        'username' => 'naldi_aja',
        'name' => 'Muh. Rinaldi Ruslan',
        'gender' => 'Female',
        'faculty' => 'MIPA',
        'batch' => '2023'
    ],
    [
        'email' => 'ervin@gmail.com',
        'username' => 'ervin',
        'name' => 'Muhammad Ervin',
        'gender' => 'Male',
        'faculty' => 'Hukum',
        'batch' => '2023'
    ],
    [
        'email' => 'yusta@gmail.com',
        'username' => 'yusra59',
        'name' => 'Yusra Airlangga',
        'gender' => 'Female',
        'faculty' => 'Keperawatan',
        'batch' => '2021'
    ],
    [
        'email' => 'muslih@gmail.com',
        'username' => 'muslih23',
        'name' => 'Muslih',
        'gender' => 'Male',
        'faculty' => 'Teknik',
        'batch' => '2020'
    ]
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f0ffef;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        a.logout-link {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 20px;
        }
        a.logout-link:hover {
            text-decoration: underline;
        }
        h2 {
            font-size: 1.5em;
            margin-top: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 5px;
        }
        table.admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table.admin-table th, table.admin-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table.admin-table th {
            background-color: #f8f8f8;
            font-weight: bold;
        }
        /* Style untuk Tampilan User Biasa */
        table.user-data {
            width: 50%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table.user-data td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        table.user-data td:first-child {
            width: 30%;
            font-weight: bold;
            background-color: #f8f8f8;
        }
    </style>
</head>
<body>

<div class="container">
    <?php if ($is_admin): ?>
        <h1>Selamat Datang, Admin!</h1>
    <?php else: ?>
        <h1>Selamat Datang, <?php echo htmlspecialchars($current_user['name']); ?>!</h1>
    <?php endif; ?>

    <a href="logout.php" class="logout-link">Logout</a>

    <?php if ($is_admin): ?>
        <h2>Data Semua Pengguna</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users_data as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: 
        $user_display_data = [
            'Nama' => $current_user['name'],
            'Username' => $current_user['username'],
            'Email' => $current_user['email'],
            'Gender' => $current_user['gender'] ?? '-',
            'Fakultas' => $current_user['faculty'] ?? '-',
            'Angkatan' => $current_user['batch'] ?? '-'
        ];
    ?>
        <h2>Data Anda</h2>
        <table class="user-data">
            <?php foreach ($user_display_data as $label => $value): ?>
                <tr>
                    <td><?php echo $label; ?></td>
                    <td><?php echo htmlspecialchars($value); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

</body>
</html>