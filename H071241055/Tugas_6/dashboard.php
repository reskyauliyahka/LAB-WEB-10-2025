<?php
// Sertakan config.php dan mulai session
include 'config.php';

// 1. Cek Session
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// 2. Ambil data session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Manajemen Proyek</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <nav class="bg-gray-800 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="font-bold text-xl hover:text-gray-300">Manajemen Proyek</a>
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="dashboard.php" class="px-3 py-2 rounded-md text-sm font-medium bg-gray-900">Dashboard</a>
                        
                        <?php
                        // Tampilkan menu berdasarkan ROLE
                        if ($role == 'super_admin') {
                            echo '<a href="manage_users.php" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700">Kelola Pengguna</a>';
                            echo '<a href="view_all_projects.php" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700">Semua Proyek</a>';
                        } elseif ($role == 'project_manager') {
                            echo '<a href="my_projects.php" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700">Proyek Saya</a>';
                        } elseif ($role == 'team_member') {
                            echo '<a href="my_tasks.php" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700">Tugas Saya</a>';
                        }
                        ?>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <a href="logout.php" class="bg-red-600 px-3 py-2 rounded-md text-sm font-medium hover:bg-red-700">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4">
            <h1 class="text-3xl font-bold text-gray-900">
                Selamat Datang, <?php echo htmlspecialchars($username); ?>!
            </h1>
            <p class="text-sm text-gray-600">Anda login sebagai: <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($role); ?></span></p>
        </div>
    </header>

    <main>
        <div class="max-w-7xl mx-auto py-6 px-4">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Ringkasan</h2>
                
                <?php
                // Tampilkan konten berdasarkan role
                if ($role == 'super_admin') {
                    echo "<p class='text-gray-700'>Silakan gunakan menu di navigasi atas untuk <span class='font-medium'>Kelola Pengguna</span> atau <span class='font-medium'>Lihat Semua Proyek</span>.</p>";
                } elseif ($role == 'project_manager') {
                    echo "<p class='text-gray-700'>Silakan gunakan menu <span class='font-medium'>Proyek Saya</span> untuk menambah, mengedit, dan mengelola semua proyek dan tugas Anda.</p>";
                } elseif ($role == 'team_member') {
                    echo "<p class='text-gray-700'>Silakan gunakan menu <span class='font-medium'>Tugas Saya</span> untuk melihat semua tugas yang diberikan kepada Anda dan memperbarui statusnya.</p>";
                }
                ?>
                </div>
        </div>
    </main>

</body>
</html>