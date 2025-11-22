<?php
// koneksi.php

$servername = "localhost";
$username = "root"; // Ganti dengan username database Anda
$password = "";     // Ganti dengan password database Anda
$dbname = "db_manajemen_proyek";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    // Keluar dan hentikan eksekusi jika gagal koneksi
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set karakter set untuk mencegah masalah encoding
$conn->set_charset("utf8mb4");


?>