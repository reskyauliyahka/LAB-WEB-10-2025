<?php
session_start();
require "connect.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: login.html"); exit;
}

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

$sql = "SELECT id, username, password, role FROM users WHERE username = ?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($res);

$ok = false;
if ($user) {
  if (password_verify($password, $user["password"])) {
    $ok = true;
  } else {
    if (hash_equals($user["password"], $password)) {
      $ok = true;
    }
  }
}

if ($ok) {
  $_SESSION["user_id"] = (int)$user["id"];
  $_SESSION["username"] = $user["username"];
  $_SESSION["role"] = $user["role"];
  session_regenerate_id(true);
  header("Location: dashboard.php?success=" . urlencode("Login berhasil, selamat datang!"));
  exit;
}

header("Location: login.html?error=" . urlencode("Login gagal. Cek username/password."));
exit;
?><?php
session_start();
require "connect.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: login.html"); exit;
}

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

$sql = "SELECT id, username, password, role FROM users WHERE username = ?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($res);

$ok = false;
if ($user) {
  if (password_verify($password, $user["password"])) {
    $ok = true;
  } else {
    if (hash_equals($user["password"], $password)) {
      $ok = true;
    }
  }
}

if ($ok) {
  $_SESSION["user_id"] = (int)$user["id"];
  $_SESSION["username"] = $user["username"];
  $_SESSION["role"] = $user["role"];
  session_regenerate_id(true);
  header("Location: dashboard.php?success=" . urlencode("Login berhasil, selamat datang!"));
  exit;
}

header("Location: login.html?error=" . urlencode("Login gagal. Cek username/password."));
exit;
?>