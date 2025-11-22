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

// 2. Validasi Akses Proyek
if (!isset($_GET['project_id'])) die("Error: Project ID tidak ditemukan.");
$project_id = (int)$_GET['project_id'];

$sql_cek_proyek = "SELECT id, nama_proyek FROM projects WHERE id = ? AND manager_id = ?";
$stmt_cek = mysqli_prepare($conn, $sql_cek_proyek);
mysqli_stmt_bind_param($stmt_cek, "ii", $project_id, $pm_id);
mysqli_stmt_execute($stmt_cek);
$result_cek = mysqli_stmt_get_result($stmt_cek);

if (mysqli_num_rows($result_cek) == 0) die("Akses ditolak. Anda bukan manajer proyek ini.");
$project_data = mysqli_fetch_assoc($result_cek);
$nama_proyek_header = htmlspecialchars($project_data['nama_proyek']);
mysqli_stmt_close($stmt_cek);

// 3. Logika CREATE dan UPDATE Tugas
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpan_tugas'])) {
    $nama_tugas = mysqli_real_escape_string($conn, $_POST['nama_tugas']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $assigned_to = (int)$_POST['assigned_to'];
    $task_id_edit = isset($_POST['task_id']) ? (int)$_POST['task_id'] : 0;

    if (empty($nama_tugas)) {
        $error_msg = "Nama tugas tidak boleh kosong.";
    } else {
        if ($task_id_edit > 0) {
            $sql = "UPDATE tasks SET nama_tugas = ?, deskripsi = ?, status = ?, assigned_to = ? WHERE id = ? AND project_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssiii", $nama_tugas, $deskripsi, $status, $assigned_to, $task_id_edit, $project_id);
            $success_msg = "Tugas berhasil diperbarui.";
        } else {
            $sql = "INSERT INTO tasks (nama_tugas, deskripsi, status, assigned_to, project_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssii", $nama_tugas, $deskripsi, $status, $assigned_to, $project_id);
            $success_msg = "Tugas baru berhasil ditambahkan.";
        }
        if (!mysqli_stmt_execute($stmt)) { $error_msg = "Error: " . mysqli_error($conn); $success_msg = ""; }
        mysqli_stmt_close($stmt);
    }
}

// 4. Logika DELETE Tugas
if (isset($_GET['delete_task'])) {
    $task_id_to_delete = (int)$_GET['delete_task'];
    $sql = "DELETE FROM tasks WHERE id = ? AND project_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $task_id_to_delete, $project_id);
    if (mysqli_stmt_execute($stmt)) $success_msg = "Berhasil menghapus tugas.";
    else $error_msg = "Error: " . mysqli_error($conn);
    mysqli_stmt_close($stmt);
}

// 5. Ambil data untuk Form EDIT
$edit_data_tugas = null;
$form_title = "Tambah Tugas Baru";
$form_button = "Tambah Tugas";
if (isset($_GET['edit_task'])) {
    $task_id_to_edit = (int)$_GET['edit_task'];
    $sql = "SELECT * FROM tasks WHERE id = ? AND project_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $task_id_to_edit, $project_id);
    mysqli_stmt_execute($stmt);
    $result_edit_tugas = mysqli_stmt_get_result($stmt);
    if ($data = mysqli_fetch_assoc($result_edit_tugas)) {
        $edit_data_tugas = $data;
        $form_title = "Edit Tugas: " . htmlspecialchars($edit_data_tugas['nama_tugas']);
        $form_button = "Simpan Perubahan";
    } else $error_msg = "Tugas tidak ditemukan.";
    mysqli_stmt_close($stmt);
}

// 6. Ambil Daftar Team Member
$list_tm = mysqli_query($conn, "SELECT id, username FROM users WHERE role = 'team_member' AND project_manager_id = $pm_id");

// 7. Ambil Daftar Tugas (READ)
$result_tasks = mysqli_query($conn, "
    SELECT t.*, u.username AS team_member_name
    FROM tasks t LEFT JOIN users u ON t.assigned_to = u.id
    WHERE t.project_id = $project_id ORDER BY t.id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Tugas - <?php echo $nama_proyek_header; ?></title>
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
            <h1 class="text-3xl font-bold text-gray-900">Kelola Tugas</h1>
            <p class="text-sm text-gray-600">Untuk Proyek: <span class="font-semibold text-gray-800"><?php echo $nama_proyek_header; ?></span></p>
        </div>
    </header>

    <main>
        <div class="max-w-7xl mx-auto py-6 px-4">
            
            <div class="mb-4">
                <a href="my_projects.php" class="text-sm text-blue-600 hover:text-blue-800">&laquo; Kembali ke Daftar Proyek</a>
            </div>

            <?php
            // Tampilkan Pesan Error/Sukses
            if ($error_msg) echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4' role='alert'>$error_msg</div>";
            if ($success_msg) echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4' role='alert'>$success_msg</div>";
            ?>

            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-xl font-semibold text-gray-700 mb-4"><?php echo $form_title; ?></h3>
                <form action="manage_tasks.php?project_id=<?php echo $project_id; ?>" method="POST" class="space-y-4">
                    <?php if ($edit_data_tugas): ?>
                        <input type="hidden" name="task_id" value="<?php echo $edit_data_tugas['id']; ?>">
                    <?php endif; ?>
                    
                    <div>
                        <label for="nama_tugas" class="block text-sm font-medium text-gray-700 mb-1">Nama Tugas:</label>
                        <input type="text" id="nama_tugas" name="nama_tugas" value="<?php echo htmlspecialchars($edit_data_tugas['nama_tugas'] ?? ''); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Tugas:</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"><?php echo htmlspecialchars($edit_data_tugas['deskripsi'] ?? ''); ?></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">Ditugaskan Kepada:</label>
                            <select id="assigned_to" name="assigned_to" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Team Member</option>
                                <?php
                                mysqli_data_seek($list_tm, 0);
                                while ($tm = mysqli_fetch_assoc($list_tm)) {
                                    $selected = ($edit_data_tugas && $edit_data_tugas['assigned_to'] == $tm['id']) ? 'selected' : '';
                                    echo "<option value='{$tm['id']}' $selected>" . htmlspecialchars($tm['username']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status:</label>
                            <select id="status" name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="belum" <?php echo ($edit_data_tugas && $edit_data_tugas['status'] == 'belum') ? 'selected' : ''; ?>>Belum Dikerjakan</option>
                                <option value="proses" <?php echo ($edit_data_tugas && $edit_data_tugas['status'] == 'proses') ? 'selected' : ''; ?>>Sedang Dikerjakan</option>
                                <option value="selesai" <?php echo ($edit_data_tugas && $edit_data_tugas['status'] == 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button type="submit" name="simpan_tugas" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 font-medium">
                            <?php echo $form_button; ?>
                        </button>
                        <?php if ($edit_data_tugas): ?>
                            <a href="manage_tasks.php?project_id=<?php echo $project_id; ?>" class="text-sm text-gray-600 hover:text-gray-800">Batal Edit</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-700">Daftar Tugas</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tugas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Team Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (mysqli_num_rows($result_tasks) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result_tasks)): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($row['nama_tugas']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo ($row['team_member_name'] ? htmlspecialchars($row['team_member_name']) : '<i>N/A</i>'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php 
                                        if ($row['status'] == 'selesai') echo 'bg-green-100 text-green-800';
                                        elseif ($row['status'] == 'proses') echo 'bg-yellow-100 text-yellow-800';
                                        else echo 'bg-gray-100 text-gray-800';
                                        ?>">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                    <a href='manage_tasks.php?project_id=<?php echo $project_id; ?>&edit_task=<?php echo $row['id']; ?>' class='bg-yellow-500 text-white py-1 px-3 rounded-md hover:bg-yellow-600 text-xs font-medium'>Edit</a>
                                    <a href='manage_tasks.php?project_id=<?php echo $project_id; ?>&delete_task=<?php echo $row['id']; ?>' 
                                       class='bg-red-600 text-white py-1 px-3 rounded-md hover:bg-red-700 text-xs font-medium' 
                                       onclick="return confirm('Yakin menghapus tugas ini?');">
                                       Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada tugas untuk proyek ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>