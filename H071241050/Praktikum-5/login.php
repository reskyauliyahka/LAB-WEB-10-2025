<?php
session_start();
// jika sudah login, redirect ke dashboard
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php'); //perintah header() untuk redirect
    exit;
}

// Ambil pesan error jika ada 
$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>Form Login</h2>
      <p class="small">Masuk dengan username dan password</p>
    </div>

    <?php if ($error): ?>
      <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="proses_login.php" method="post"> //kirim data form ke server
      <div class="form-row">
        <label for="username">Username</label>
        <input id="username" name="username" type="text" required autofocus>
      </div>
      <div class="form-row">
        <label for="password">Password</label>
        <input id="password" name="password" type="password" required>
      </div>
      <div class="form-row" style="text-align:right;">
        <button type="submit">Login</button>
      </div>
    </form>

    <p class="small">Contoh akun: <strong>adminxxx/admin123</strong> atau user seperti <strong>naldi_aja/naldi123</strong></p>
  </div>
</body>
</html>
