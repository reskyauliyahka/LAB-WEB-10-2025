<?php
/*
 * File: cek_koneksi.php
 * Deskripsi: Hanya untuk mengetes koneksi ke database.
 */

// 1. Pengaturan Koneksi Database
$db_host = 'localhost';     // Pastikan ini benar
$db_user = 'root';          // Pastikan ini user Anda
$db_pass = '';              // Pastikan ini password Anda (kosong jika default)
$db_name = 'db_manajemen_proyek'; // Pastikan nama database benar

// 2. Membuat Koneksi
echo "Mencoba terhubung ke MySQL di '$db_host' dengan user '$db_user'...<br>";
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// 3. Cek Koneksi
if (!$conn) {
    // Jika koneksi GAGAL
    echo "<h2 style='color: red;'>KONEKSI GAGAL!</h2>";
    echo "Pesan Error: " . mysqli_connect_error();
    echo "<p><strong>Tips:</strong> Periksa kembali <strong>\$db_host, \$db_user,</strong> dan <strong>\$db_pass</strong> di file config.php Anda.</p>";
} else {
    // Jika koneksi BERHASIL
    echo "<h2 style='color: green;'>KONEKSI BERHASIL!</h2>";
    echo "Berhasil terhubung ke database '<strong>$db_name</strong>'.";
    echo "<p>Ini artinya file <strong>config.php</strong> Anda sudah benar. Masalahnya mungkin ada di data login Anda (Langkah 2).</p>";
}

// Tutup koneksi (opsional untuk tes)
if ($conn) {
    mysqli_close($conn);
}
?>