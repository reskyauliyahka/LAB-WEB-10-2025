<?php
session_start();
if (!isset($_SESSION['user'])) {
    // jika belum ada/ogin
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    header('Location: login.php');
    exit;
}

require 'data.php'; // untuk menampilkan seluruh data
$current = $_SESSION['user'];
$isAdmin = (isset($current['username']) && $current['username'] === 'adminxxx');
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container"> //wadah utama semua isi dashboard.
    <div class="topbar">
      <div>
        <?php if ($isAdmin): ?>
          <h3>Selamat Datang, Admin!</h3>
          <p class="small">Anda memiliki akses penuh.</p>
        <?php else: ?>
          <h3>Selamat Datang, <?= htmlspecialchars($current['name'] ?? $current['username']) ?>!</h3>
          <p class="small">Halo, ini halaman profil Anda.</p>
        <?php endif; ?>
      </div>
      <div>
        <a class="logout" href="logout.php">Logout</a>
      </div>
    </div>

    <?php if ($isAdmin): ?>
      <h4>Daftar Pengguna</h4>
      <table class="table">
        <thead>
          <tr>
            <th>No</th>
            <th>Username</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Faculty</th>
            <th>Batch</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $i => $u): ?> //untuk setiap pasangan key dan value dari array $users
            <tr>
              <td><?= $i+1 ?></td>
              <td><?= htmlspecialchars($u['username'] ?? '') ?></td>
              <td><?= htmlspecialchars($u['name'] ?? '') ?></td>
              <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
              <td><?= htmlspecialchars($u['gender'] ?? '-') ?></td>
              <td><?= htmlspecialchars($u['faculty'] ?? '-') ?></td>
              <td><?= htmlspecialchars($u['batch'] ?? '-') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <h4>Data Anda</h4>
      <table class="table">
        <tr><th>Username</th><td><?= htmlspecialchars($current['username'] ?? '-') ?></td></tr>
        <tr><th>Nama</th><td><?= htmlspecialchars($current['name'] ?? '-') ?></td></tr>
        <tr><th>Email</th><td><?= htmlspecialchars($current['email'] ?? '-') ?></td></tr>
        <tr><th>Gender</th><td><?= htmlspecialchars($current['gender'] ?? '-') ?></td></tr>
        <tr><th>Faculty</th><td><?= htmlspecialchars($current['faculty'] ?? '-') ?></td></tr>
        <tr><th>Batch</th><td><?= htmlspecialchars($current['batch'] ?? '-') ?></td></tr>
      </table>
    <?php endif; ?>
  </div>
</body>
</html>
