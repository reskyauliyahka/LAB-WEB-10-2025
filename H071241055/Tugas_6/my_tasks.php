<?php
include 'config.php';

// 1. Cek Session dan Role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'team_member') {
    header("Location: index.php");
    exit();
}

$tm_id = $_SESSION['user_id'];
$error_msg = "";
$success_msg = "";

// 2. Logika UPDATE Status Tugas
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $task_id_to_update = (int)$_POST['task_id'];
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $valid_statuses = ['belum', 'proses', 'selesai'];
    if (in_array($new_status, $valid_statuses)) {
        
        $sql = "UPDATE tasks SET status = ? WHERE id = ? AND assigned_to = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sii", $new_status, $task_id_to_update, $tm_id);
        
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) $success_msg = "Status tugas berhasil diperbarui.";
            else $error_msg = "Error: Gagal memperbarui status (Tugas bukan milik Anda).";
        } else $error_msg = "Error: " . mysqli_error($conn);
        mysqli_stmt_close($stmt);
        
    } else $error_msg = "Error: Status tidak valid.";
}


// 3. Ambil Daftar Tugas (READ)
$result_tasks = mysqli_query($conn, "
    SELECT t.id, t.nama_tugas, t.deskripsi, t.status, p.nama_proyek
    FROM tasks t JOIN projects p ON t.project_id = p.id
    WHERE t.assigned_to = $tm_id
    ORDER BY p.nama_proyek, t.id DESC
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas Saya - Team Member</title>
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
                        <a href="my_tasks.php" class="px-3 py-2 rounded-md text-sm font-medium bg-gray-900">Tugas Saya</a>
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
            <h1 class="text-3xl font-bold text-gray-900">Daftar Tugas Saya</h1>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tugas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Update Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (mysqli_num_rows($result_tasks) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result_tasks)): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($row['nama_proyek']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($row['nama_tugas']); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate"><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <form action="my_tasks.php" method="POST" class="m-0">
                                        <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="update_status" value="1">
                                        
                                        <select name="status" onchange="this.form.submit()" 
                                                class="block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm
                                                <?php 
                                                if ($row['status'] == 'selesai') echo 'bg-green-100 text-green-800 border-green-300';
                                                elseif ($row['status'] == 'proses') echo 'bg-yellow-100 text-yellow-800 border-yellow-300';
                                                else echo 'bg-gray-100 text-gray-800 border-gray-300';
                                                ?>">
                                            <option value="belum" <?php echo ($row['status'] == 'belum' ? 'selected' : ''); ?>>Belum Dikerjakan</option>
                                            <option value="proses" <?php echo ($row['status'] == 'proses' ? 'selected' : ''); ?>>Sedang Dikerjakan</option>
                                            <option value="selesai" <?php echo ($row['status'] == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Anda belum memiliki tugas yang ditugaskan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>