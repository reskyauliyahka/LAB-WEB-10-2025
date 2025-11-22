<?php
include 'config.php';

// 1. Cek Session dan Role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_admin') {
    header("Location: index.php");
    exit();
}

$error_msg = "";
$success_msg = "";

// 2. Logika DELETE Proyek
if (isset($_GET['delete_project'])) {
    $project_id_to_delete = (int)$_GET['delete_project'];
    
    $sql = "DELETE FROM projects WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $project_id_to_delete);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_msg = "Berhasil menghapus proyek.";
    } else {
        $error_msg = "Error: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// 3. Ambil data semua proyek (READ)
$result_projects = mysqli_query($conn, "
    SELECT p.id, p.nama_proyek, p.deskripsi, p.tanggal_mulai, p.tanggal_selesai, u.username AS manager_name
    FROM projects p
    JOIN users u ON p.manager_id = u.id
    ORDER BY p.tanggal_mulai DESC
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Proyek - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <nav class="bg-gray-800 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="font-bold text-xl hover:text-gray-300">Manajemen Proyek</a>
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="dashboard.php" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700">Dashboard</a>
                        <a href="manage_users.php" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700">Kelola Pengguna</a>
                        <a href="view_all_projects.php" class="px-3 py-2 rounded-md text-sm font-medium bg-gray-900">Semua Proyek</a>
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
            <h1 class="text-3xl font-bold text-gray-900">Semua Proyek</h1>
        </div>
    </header>

    <main>
        <div class="max-w-7xl mx-auto py-6 px-4">
            
            <?php
            // Tampilkan Pesan Error/Sukses
            if ($error_msg) echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4' role='alert'>$error_msg</div>";
            if ($success_msg) echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4' role='alert'>$success_msg</div>";
            ?>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Proyek</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Manajer Proyek</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Mulai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Selesai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (mysqli_num_rows($result_projects) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result_projects)): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($row['nama_proyek']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($row['manager_name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo $row['tanggal_mulai']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo $row['tanggal_selesai']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href='view_all_projects.php?delete_project=<?php echo $row['id']; ?>' 
                                       class='bg-red-600 text-white py-1 px-3 rounded-md hover:bg-red-700 text-xs font-medium' 
                                       onclick="return confirm('Yakin menghapus proyek ini? Semua tugas di dalamnya akan hilang.');">
                                       Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada proyek yang dibuat.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>