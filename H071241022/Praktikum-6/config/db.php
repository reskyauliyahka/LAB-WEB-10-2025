<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db = 'db_manajemen_proyek';

$conn = new mysqli($host, $user, $password, $db);

if($conn->connect_error){
    die("Koneksi gagal: " . $conn->connect_error);
}
?>