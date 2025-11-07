<?php
// test_login_debug.php
// Ganti dengan password yang Anda masukkan saat membuat pengguna baru
$input_password_saat_login = 'password1234'; 
// Salin hash lengkap dari kolom 'password' di phpMyAdmin
$hash_dari_database = '$2y$10$1Qmssk8oVEeLUxcUyUD7/OWwjsTlqg5/6/wgcujxbJdxPHkLfkIfi'; 

echo "Password Input: " . $input_password_saat_login . "<br>";
echo "Hash DB: " . $hash_dari_database . "<br><br>";

if (password_verify($input_password_saat_login, $hash_dari_database)) {
    echo "<h1>✅ VERIFIKASI BERHASIL!</h1>";
    echo "Password yang Anda coba masukkan di form login sudah benar.";
} else {
    echo "<h1>❌ VERIFIKASI GAGAL!</h1>";
    echo "Password yang Anda coba masukkan saat ini TIDAK COCOK dengan hash di database.";
    echo "<br>Kemungkinan: Anda salah ketik password saat membuat user, atau salah ketik saat login.";
}
?>