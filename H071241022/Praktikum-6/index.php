<?php
session_start();
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE username='$username' AND password=MD5('$password')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user'] = $user;
        if ($user['role'] == 'superadmin') header("Location: superadmin/manage_users.php");
        elseif ($user['role'] == 'manager') header("Location: manager/projects.php");
        else header("Location: member/tasks.php");
        exit;
    } else {
        $error = "Login gagal. Cek username atau password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login - Manajemen Proyek</title>
  <?php include 'config/style.php'; ?>
  <style>
    .login-card {
      max-width: 400px;
      margin: 100px auto;
      background: white;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<div class="login-card text-center">
  <h3 class="mb-4">Login</h3>
  <form method="POST">
    <div class="mb-3">
      <input type="text" name="username" class="form-control" placeholder="Username" required>
    </div>
    <div class="mb-3">
      <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>
    <button type="submit" class="btn btn-pink w-100">Login</button>
  </form>
  <?php if (!empty($error)) echo "<p class='text-danger mt-3'>$error</p>"; ?>
</div>

<footer>Praktikum 6 Pemrograman Web</footer>
</body>
</html>
