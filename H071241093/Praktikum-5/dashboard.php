<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
require_once ("data.php");

$current_user = $_SESSION['user'];
$is_admin = $current_user['username'] === 'adminxxx';

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-image: url(assets/cover2.jpg);
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            padding-bottom: 40px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
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
        table.user-data {
            width: 100%;
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

<div class="container" data-aos="fade-up" data-aos-duration="800">
    <?php if ($is_admin): ?>
        <h1 data-aos="fade-down">Selamat Datang, Admin!</h1>
    <?php else: ?>
        <h1 data-aos="fade-down">Selamat Datang, <?php echo htmlspecialchars($current_user['name']); ?>!</h1>
    <?php endif; ?>

    <a href="logout.php" class="logout-link" data-aos="fade-down" data-aos-delay="100">Logout</a>

    <?php if ($is_admin): ?>
        <h2 data-aos="fade-right" data-aos-delay="200">Data Semua Pengguna</h2>
        <table class="admin-table" data-aos="fade-up" data-aos-delay="300">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
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
        <h2 data-aos="fade-right" data-aos-delay="200">Data Anda</h2>
        <table class="user-data" data-aos="fade-up" data-aos-delay="300">
            <?php foreach ($user_display_data as $label => $value): ?>
                <tr>
                    <td><?php echo $label; ?></td>
                    <td><?php echo htmlspecialchars($value); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
</script>

</body>
</html>