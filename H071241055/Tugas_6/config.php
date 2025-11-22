<?php
/*
 * File: config.php
 * Deskripsi: File konfigurasi untuk koneksi database.
 */

// 1. Pengaturan Koneksi Database
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'db_manajemen_proyek';

// 2. Membuat Koneksi
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// 3. Cek Koneksi
if (!$conn) {
    // Jika koneksi gagal, tampilkan pesan error dan hentikan skrip
    die("Koneksi Gagal: " . mysqli_connect_error());
}

// 4. Pengaturan zona waktu (Opsional tapi disarankan)
date_default_timezone_set('Asia/Jakarta');

// 5. Memulai Session
// Kita akan butuh session untuk sistem login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>