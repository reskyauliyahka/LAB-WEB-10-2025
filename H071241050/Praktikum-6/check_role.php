<?php
// check_role.php

// Pastikan session sudah dimulai sebelum menggunakan $_SESSION
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Memverifikasi hak akses pengguna saat ini.
 * Jika pengguna belum login atau rolenya tidak diizinkan, akan di-redirect.
 *
 * @param array $allowed_roles Array string role yang diizinkan (misal: ['Super Admin', 'Project Manager'])
 * @return void menunjukkan bahwa fungsi tidak mengembalikan nilai apa pun (hanya melakukan aksi)
 */
function check_role_access(array $allowed_roles) {
    // 1. Cek apakah pengguna sudah login
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        // Belum login: arahkan ke halaman login
        header('Location: login.php');
        exit();
    }

    //RBAC
    $current_role = $_SESSION['role'];

    // 2. Cek apakah role pengguna diizinkan
    if (!in_array($current_role, $allowed_roles)) {
        // Role tidak diizinkan: arahkan ke halaman dashboard sesuai role mereka (atau halaman 'unauthorized')
        $role_slug = str_replace(' ', '_', strtolower($current_role));
        // Mengarahkan ke dashboard mereka sendiri jika tidak memiliki hak akses ke halaman ini
        header('Location: dashboard_' . $role_slug . '.php'); 
        exit();
    }
}

// Fungsi sederhana untuk logout
function logout() {
    session_start();
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}
?>