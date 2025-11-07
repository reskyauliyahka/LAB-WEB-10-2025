<?php
// hapus_proyek.php - Menangani penghapusan proyek dari Admin atau PM

session_start();
require 'koneksi.php'; // Ambil objek koneksi $conn

// Pastikan user_id dan role ada di sesi
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Ambil ID proyek dari URL dan bersihkan
$project_id = $_GET['id'] ?? 0;
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];
$redirect_url = ($user_role === 'Super Admin') ? 'dashboard_super_admin.php' : 'manage_projects.php';

if ($project_id <= 0) {
    header("Location: $redirect_url?message=" . urlencode("Error: ID Proyek tidak valid."));
    exit();
}

$conn->begin_transaction(); // Mulai Transaksi

try {
    // 1. Hapus Tasks Terkait DAHULU (Mengatasi kegagalan CASCADE/FK)
    // Query ini akan dijalankan terlepas dari role, karena tugas harus dihapus sebelum proyek.
    $stmt_tasks = $conn->prepare("DELETE FROM tasks WHERE project_id = ?");
    $stmt_tasks->bind_param("i", $project_id);
    $stmt_tasks->execute();
    $stmt_tasks->close();

    // 2. Tentukan Logika Penghapusan Proyek berdasarkan Role
    if ($user_role === 'Project Manager') {
        // PM hanya bisa menghapus proyek MILIKNYA
        $sql = "DELETE FROM projects WHERE id=? AND manager_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $project_id, $user_id);
    } elseif ($user_role === 'Super Admin') {
        // Admin bisa menghapus proyek APAPUN
        $sql = "DELETE FROM projects WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $project_id);
    } else {
        throw new Exception("Akses Ditolak: Peran tidak diizinkan.");
    }

    // 3. Eksekusi Penghapusan Proyek
    if (!$stmt->execute()) {
        throw new Exception("Gagal menjalankan DELETE query proyek.");
    }
    
    // Cek apakah ada baris yang terhapus (validasi RBAC PM)
    if ($stmt->affected_rows === 0 && $user_role === 'Project Manager') {
        throw new Exception("Akses Ditolak: Proyek tidak ditemukan atau bukan milik Anda.");
    }

    $stmt->close();
    $conn->commit(); // Komit jika semuanya berhasil

    header("Location: $redirect_url?message=" . urlencode("Proyek berhasil dihapus."));
    exit();

} catch (Exception $e) {
    $conn->rollback(); // Rollback jika ada error
    header("Location: $redirect_url?message=" . urlencode("Error Hapus: " . $e->getMessage()));
    exit();
}
?>