<?php
include 'config.php';

// 1. Cek Session dan Role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_admin') {
    header("Location: index.php");
    exit();
}

$error_msg = "";
$success_msg = "";

// 2. Logika CREATE User (Tambah Pengguna)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; // Password mentah
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $pm_id = NULL;

    $cek_user = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek_user) > 0) {
        $error_msg = "Username '$username' sudah digunakan.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        if ($role == 'team_member') {
            if (empty($_POST['project_manager_id'])) {
                $error_msg = "Error: Team Member harus memiliki Project Manager.";
            } else {
                $pm_id = (int)$_POST['project_manager_id'];
            }
        }
        
        if (empty($error_msg)) {
            $sql = "INSERT INTO users (username, password, role, project_manager_id) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssi", $username, $hashed_password, $role, $pm_id);
            if (mysqli_stmt_execute($stmt)) {
                $success_msg = "Berhasil menambah pengguna baru: $username";
            } else {
                $error_msg = "Error: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// 3. Logika DELETE User
if (isset($_GET['delete_user'])) {
    $user_id_to_delete = (int)$_GET['delete_user'];
    if ($user_id_to_delete == 1) {
        $error_msg = "Error: Super Admin utama tidak dapat dihapus.";
    } else {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id_to_delete);
        if (mysqli_stmt_execute($stmt)) {
            $success_msg = "Berhasil menghapus pengguna.";
        } else {
            $error_msg = "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}

// 4. Ambil data untuk Tampilan (READ)
$list_pm = mysqli_query($conn, "SELECT id, username FROM users WHERE role = 'project_manager'");
$result_pm = mysqli_query($conn, "SELECT id, username FROM users WHERE role = 'project_manager'");
$result_tm = mysqli_query($conn, "
    SELECT tm.id, tm.username, pm.username AS manager_name 
    FROM users tm LEFT JOIN users pm ON tm.project_manager_id = pm.id
    WHERE tm.role = 'team_member'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - Super Admin</title>
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
                        <a href="manage_users.php" class="px-3 py-2 rounded-md text-sm font-medium bg-gray-900">Kelola Pengguna</a>
                        <a href="view_all_projects.php" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700">Semua Proyek</a>
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
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Pengguna</h1>
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
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Tambah Pengguna Baru</h3>
                <form action="manage_users.php" method="POST" onsubmit="return validasiForm()" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username:</label>
                            <input type="text" id="username" name="username" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password:</label>
                            <input type="password" id="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role:</label>
                            <select id="role" name="role" required onchange="cekRole()" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Role</option>
                                <option value="project_manager">Project Manager</option>
                                <option value="team_member">Team Member</option>
                            </select>
                        </div>
                        <div id="pm_selector" style="display:none;">
                            <label for="project_manager_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Project Manager:</label>
                            <select id="project_manager_id" name="project_manager_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih PM</option>
                                <?php
                                mysqli_data_seek($list_pm, 0);
                                while ($pm = mysqli_fetch_assoc($list_pm)) {
                                    echo "<option value='{$pm['id']}'>" . htmlspecialchars($pm['username']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div>
                        <button type="submit" name="tambah_user" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 font-medium">
                            Tambah Pengguna
                        </button>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-700">Daftar Project Manager</h3>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row_pm = mysqli_fetch_assoc($result_pm)): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($row_pm['username']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href='manage_users.php?delete_user=<?php echo $row_pm['id']; ?>' 
                                       class='bg-red-600 text-white py-1 px-3 rounded-md hover:bg-red-700 text-xs font-medium' 
                                       onclick="return confirm('Yakin menghapus PM ini? Semua proyek dan tugas terkait akan terhapus.');">
                                       Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-700">Daftar Team Member</h3>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project Manager</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row_tm = mysqli_fetch_assoc($result_tm)): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($row_tm['username']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo ($row_tm['manager_name'] ? htmlspecialchars($row_tm['manager_name']) : '<i>Belum diatur</i>'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href='manage_users.php?delete_user=<?php echo $row_tm['id']; ?>' 
                                       class='bg-red-600 text-white py-1 px-3 rounded-md hover:bg-red-700 text-xs font-medium' 
                                       onclick="return confirm('Yakin menghapus user ini?');">
                                       Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </main>
    
    <script>
    // Script JS (SAMA SEPERTI SEBELUMNYA)
    function cekRole() {
        var role = document.getElementById('role').value;
        var pmSelector = document.getElementById('pm_selector');
        
        if (role == 'team_member') {
            pmSelector.style.display = 'block';
        } else {
            pmSelector.style.display = 'none';
        }
    }
    
    function validasiForm() {
        var role = document.getElementById('role').value;
        var pm_id = document.getElementById('project_manager_id').value;
        
        if (role == 'team_member' && pm_id == "") {
            alert('Error: Silakan pilih Project Manager untuk Team Member ini.');
            return false;
        }
        return true;
    }
    </script>

</body>
</html>