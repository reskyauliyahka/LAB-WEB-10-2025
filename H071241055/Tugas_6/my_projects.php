<?php
include 'config.php';

// 1. Cek Session dan Role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'project_manager') {
    header("Location: index.php");
    exit();
}

$pm_id = $_SESSION['user_id'];
$error_msg = "";
$success_msg = "";

// 2. Logika CREATE dan UPDATE Proyek
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpan_proyek'])) {
    $nama_proyek = mysqli_real_escape_string($conn, $_POST['nama_proyek']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $project_id_edit = isset($_POST['project_id']) ? (int)$_POST['project_id'] : 0;

    if (empty($nama_proyek)) {
        $error_msg = "Nama proyek tidak boleh kosong.";
    } elseif (!empty($tanggal_mulai) && !empty($tanggal_selesai) && $tanggal_selesai < $tanggal_mulai) {
        // Tambahkan validasi tanggal di sini
        $error_msg = "Error: Tanggal selesai tidak boleh lebih awal dari tanggal mulai.";
    } else {
        if ($project_id_edit > 0) {
            $sql = "UPDATE projects SET nama_proyek = ?, deskripsi = ?, tanggal_mulai = ?, tanggal_selesai = ? WHERE id = ? AND manager_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssii", $nama_proyek, $deskripsi, $tanggal_mulai, $tanggal_selesai, $project_id_edit, $pm_id);
            $success_msg = "Proyek berhasil diperbarui.";
        } else {
            $sql = "INSERT INTO projects (nama_proyek, deskripsi, tanggal_mulai, tanggal_selesai, manager_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssi", $nama_proyek, $deskripsi, $tanggal_mulai, $tanggal_selesai, $pm_id);
            $success_msg = "Proyek baru berhasil dibuat.";
        }
        if (!mysqli_stmt_execute($stmt)) {
            $error_msg = "Error: " . mysqli_error($conn); $success_msg = "";
        }
        mysqli_stmt_close($stmt);
    }
}

// 3. Logika DELETE Proyek
if (isset($_GET['delete_project'])) {
    $project_id_to_delete = (int)$_GET['delete_project'];
    $sql = "DELETE FROM projects WHERE id = ? AND manager_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $project_id_to_delete, $pm_id);
    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) $success_msg = "Berhasil menghapus proyek.";
        else $error_msg = "Error: Anda tidak memiliki izin atau proyek tidak ditemukan.";
    } else $error_msg = "Error: " . mysqli_error($conn);
    mysqli_stmt_close($stmt);
}

// 4. Ambil data untuk Form EDIT
$edit_data = null;
$form_title = "Buat Proyek Baru";
$form_button = "Buat Proyek";
if (isset($_GET['edit_project'])) {
    $project_id_to_edit = (int)$_GET['edit_project'];
    $sql = "SELECT * FROM projects WHERE id = ? AND manager_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $project_id_to_edit, $pm_id);
    mysqli_stmt_execute($stmt);
    $result_edit = mysqli_stmt_get_result($stmt);
    if ($data = mysqli_fetch_assoc($result_edit)) {
        $edit_data = $data;
        $form_title = "Edit Proyek: " . htmlspecialchars($edit_data['nama_proyek']);
        $form_button = "Simpan Perubahan";
    } else $error_msg = "Proyek tidak ditemukan atau Anda tidak punya akses.";
    mysqli_stmt_close($stmt);
}

// 5. Ambil daftar proyek milik PM ini (READ)
$result_projects = mysqli_query($conn, "SELECT * FROM projects WHERE manager_id = $pm_id ORDER BY tanggal_mulai DESC");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyek Saya - Project Manager</title>
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
                        <a href="my_projects.php" class="px-3 py-2 rounded-md text-sm font-medium bg-gray-900">Proyek Saya</a>
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
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Proyek Saya</h1>
        </div>
    </header>

    <main>
        <div class="max-w-7xl mx-auto py-6 px-4">
            
            <?php
            // Tampilkan Pesan Error/Sukses
            if ($error_msg) echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4' role='alert'>$error_msg</div>";
            if ($success_msg) echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4' role='alert'>$success_msg</div>";
            ?>

            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-xl font-semibold text-gray-700 mb-4"><?php echo $form_title; ?></h3>
                <form action="my_projects.php" method="POST" class="space-y-4">
                    <?php if ($edit_data): ?>
                        <input type="hidden" name="project_id" value="<?php echo $edit_data['id']; ?>">
                    <?php endif; ?>
                    
                    <div>
                        <label for="nama_proyek" class="block text-sm font-medium text-gray-700 mb-1">Nama Proyek:</label>
                        <input type="text" id="nama_proyek" name="nama_proyek" value="<?php echo htmlspecialchars($edit_data['nama_proyek'] ?? ''); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi:</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"><?php echo htmlspecialchars($edit_data['deskripsi'] ?? ''); ?></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai:</label>
                            <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="<?php echo $edit_data['tanggal_mulai'] ?? ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai:</label>
                            <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="<?php echo $edit_data['tanggal_selesai'] ?? ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button type="submit" name="simpan_proyek" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 font-medium">
                            <?php echo $form_button; ?>
                        </button>
                        <?php if ($edit_data): ?>
                            <a href="my_projects.php" class="text-sm text-gray-600 hover:text-gray-800">Batal Edit</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-700">Daftar Proyek Anda</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Proyek</th>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo $row['tanggal_mulai']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo $row['tanggal_selesai']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                    <a href='manage_tasks.php?project_id=<?php echo $row['id']; ?>' class='bg-green-600 text-white py-1 px-3 rounded-md hover:bg-green-700 text-xs font-medium'>Kelola Tugas</a>
                                    <a href='my_projects.php?edit_project=<?php echo $row['id']; ?>' class='bg-yellow-500 text-white py-1 px-3 rounded-md hover:bg-yellow-600 text-xs font-medium'>Edit</a>
                                    <a href='my_projects.php?delete_project=<?php echo $row['id']; ?>' 
                                       class='bg-red-600 text-white py-1 px-3 rounded-md hover:bg-red-700 text-xs font-medium' 
                                       onclick="return confirm('Yakin menghapus proyek ini?');">
                                       Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Anda belum memiliki proyek.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>