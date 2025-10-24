<?php
session_start();

if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}
if ($_SESSION["role"] !== "super_admin") {
    http_response_code(403);
    exit("Akses ditolak - Hanya Super Admin");
}
require "connect.php";
$username = htmlspecialchars($_SESSION["username"]);

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "create") {
    $new_username = trim($_POST["username"] ?? "");
    $new_password = $_POST["password"] ?? "";
    $new_role = $_POST["role"] ?? "";
    $pm_id = $_POST["project_manager_id"] ?? "";
    if (empty($new_username) || empty($new_password) || empty($new_role)) {
        header("Location: users.php?error=" . urlencode("Semua field wajib diisi")); exit;
    }
    if (!in_array($new_role, ["project_manager", "team_member"], true)) {
        header("Location: users.php?error=" . urlencode("Role tidak valid")); exit;
    }
    $check = mysqli_prepare($connect, "SELECT id FROM users WHERE username = ?");
    mysqli_stmt_bind_param($check, "s", $new_username);
    mysqli_stmt_execute($check);
    if (mysqli_fetch_row(mysqli_stmt_get_result($check))) {
        header("Location: users.php?error=" . urlencode("Username sudah digunakan")); exit;
    }
    $hashed = password_hash($new_password, PASSWORD_BCRYPT);
    if ($new_role === "team_member") {
        $pm_id_int = (int)$pm_id;
        if ($pm_id_int <= 0) {
            header("Location: users.php?error=" . urlencode("Team Member harus memiliki Project Manager")); exit;
        }
        $check_pm = mysqli_prepare($connect, "SELECT id FROM users WHERE id = ? AND role = 'project_manager'");
        mysqli_stmt_bind_param($check_pm, "i", $pm_id_int);
        mysqli_stmt_execute($check_pm);
        if (!mysqli_fetch_row(mysqli_stmt_get_result($check_pm))) {
            header("Location: users.php?error=" . urlencode("Project Manager tidak valid")); exit;
        }
        $stmt = mysqli_prepare($connect, "INSERT INTO users (username, password, role, project_manager_id) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssi", $new_username, $hashed, $new_role, $pm_id_int);
    } else {
        $stmt = mysqli_prepare($connect, "INSERT INTO users (username, password, role, project_manager_id) VALUES (?, ?, ?, NULL)");
        mysqli_stmt_bind_param($stmt, "sss", $new_username, $hashed, $new_role);
    }
    if (mysqli_stmt_execute($stmt)) {
        header("Location: users.php?success=" . urlencode("User berhasil ditambahkan"));
    } else {
        header("Location: users.php?error=" . urlencode("Gagal menambahkan user"));
    }
    exit;
}
if (isset($_GET["del"])) {
    $del_id = (int)$_GET["del"];
    if ($del_id === (int)$_SESSION["user_id"]) {
        header("Location: users.php?error=" . urlencode("Tidak bisa menghapus akun sendiri")); exit;
    }
    $check = mysqli_prepare($connect, "SELECT role FROM users WHERE id = ?");
    mysqli_stmt_bind_param($check, "i", $del_id);
    mysqli_stmt_execute($check);
    $res = mysqli_stmt_get_result($check);
    $user = mysqli_fetch_assoc($res);
    if (!$user) {
        header("Location: users.php?error=" . urlencode("User tidak ditemukan")); exit;
    }
    if ($user["role"] === "super_admin") {
        header("Location: users.php?error=" . urlencode("Tidak bisa menghapus Super Admin")); exit;
    }
    if ($user["role"] === "project_manager") {
        $check_proj = mysqli_prepare($connect, "SELECT COUNT(*) as cnt FROM projects WHERE manager_id = ?");
        mysqli_stmt_bind_param($check_proj, "i", $del_id);
        mysqli_stmt_execute($check_proj);
        $cnt = mysqli_fetch_assoc(mysqli_stmt_get_result($check_proj));
        if ($cnt["cnt"] > 0) {
            header("Location: users.php?error=" . urlencode("Tidak bisa hapus PM yang masih memiliki proyek aktif")); exit;
        }
    }
    $del_stmt = mysqli_prepare($connect, "DELETE FROM users WHERE id = ?");
    mysqli_stmt_bind_param($del_stmt, "i", $del_id);
    if (mysqli_stmt_execute($del_stmt)) {
        header("Location: users.php?success=" . urlencode("User berhasil dihapus"));
    } else {
        header("Location: users.php?error=" . urlencode("Gagal menghapus user"));
    }
    exit;
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "edit") {
    $edit_id = (int)($_POST["user_id"] ?? 0);
    $new_pm_id = (int)($_POST["project_manager_id"] ?? 0);
    $check = mysqli_prepare($connect, "SELECT role FROM users WHERE id = ?");
    mysqli_stmt_bind_param($check, "i", $edit_id);
    mysqli_stmt_execute($check);
    $user = mysqli_fetch_assoc(mysqli_stmt_get_result($check));
    if (!$user || $user["role"] !== "team_member") {
        header("Location: users.php?error=" . urlencode("Hanya bisa edit Team Member")); exit;
    }
    $check_pm = mysqli_prepare($connect, "SELECT id FROM users WHERE id = ? AND role = 'project_manager'");
    mysqli_stmt_bind_param($check_pm, "i", $new_pm_id);
    mysqli_stmt_execute($check_pm);
    if (!mysqli_fetch_row(mysqli_stmt_get_result($check_pm))) {
        header("Location: users.php?error=" . urlencode("Project Manager tidak valid")); exit;
    }
    $upd = mysqli_prepare($connect, "UPDATE users SET project_manager_id = ? WHERE id = ?");
    mysqli_stmt_bind_param($upd, "ii", $new_pm_id, $edit_id);
    if (mysqli_stmt_execute($upd)) {
        header("Location: users.php?success=" . urlencode("Project Manager berhasil diupdate"));
    } else {
        header("Location: users.php?error=" . urlencode("Gagal update user"));
    }
    exit;
}

$edit_user = null;
if (isset($_GET["edit_pm_for"])) {
    $edit_id = (int)$_GET["edit_pm_for"];
    $stmt = mysqli_prepare($connect, "SELECT id, username, project_manager_id FROM users WHERE id=? AND role='team_member'");
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $edit_user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

$pm_list_query = mysqli_query($connect, "SELECT id, username FROM users WHERE role='project_manager' ORDER BY username");
$pm_list = [];
while ($pm = mysqli_fetch_assoc($pm_list_query)) {
    $pm_list[] = $pm;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Users - Manajemen Proyek</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-gray-100">
    
    <nav class="bg-gray-800 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex-shrink-0">
                    <a href="dashboard.php" class="text-2xl font-bold flex item-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-briefcase"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M22 13.478v4.522a3 3 0 0 1 -3 3h-14a3 3 0 0 1 -3 -3v-4.522l.553 .277a20.999 20.999 0 0 0 18.897 -.002l.55 -.275zm-8 -11.478a3 3 0 0 1 3 3v1h2a3 3 0 0 1 3 3v2.242l-1.447 .724a19.002 19.002 0 0 1 -16.726 .186l-.647 -.32l-1.18 -.59v-2.242a3 3 0 0 1 3 -3h2v-1a3 3 0 0 1 3 -3h4zm-2 8a1 1 0 0 0 -1 1a1 1 0 1 0 2 .01c0 -.562 -.448 -1.01 -1 -1.01zm2 -6h-4a1 1 0 0 0 -1 1v1h6v-1a1 1 0 0 0 -1 -1z" /></svg>Tukang Ku</a>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="dashboard.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                        <a href="users.php" class="bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium">Kelola Users</a>
                        <a href="projects.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Projects</a>
                        <a href="tasks.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Tasks</a>
                    </div>
                </div>
                <div class="hidden md:flex items-center ml-4">
                     <span class="text-sm text-gray-300 mr-3">
                        <i class="bi bi-person-circle"></i> 
                        <strong><?= $username ?></strong>
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mr-3">
                        super_admin
                    </span>
                    <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6"><i class="bi bi-people-fill"></i> Kelola Users</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="md:col-span-1">
                
                <?php if ($edit_user): ?>
                <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                    <div class="bg-yellow-600 text-white p-4">
                        <h5 class="text-lg font-semibold">
                            <i class="bi bi-pencil"></i> Edit PM untuk: <?= htmlspecialchars($edit_user['username']) ?>
                        </h5>
                    </div>
                    <div class="p-6">
                        <form method="post" class="space-y-4">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="user_id" value="<?= $edit_user['id'] ?>">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Project Manager Baru <span class="text-red-500">*</span></label>
                                <select name="project_manager_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <?php foreach ($pm_list as $pm): ?>
                                        <option value="<?= $pm['id'] ?>" <?= $pm['id'] == $edit_user['project_manager_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($pm['username']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                <i class="bi bi-save mr-2"></i> Update PM
                            </button>
                             <a href="users.php" class="w-full flex justify-center py-2 px-4 border rounded-md shadow-sm text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 mt-2">
                                <i class="bi bi-x-circle mr-2"></i> Batal Edit
                            </a>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="bg-gray-800 text-white p-4">
                        <h5 class="text-lg font-semibold"><i class="bi bi-person-plus"></i> Tambah User Baru</h5>
                    </div>
                    <div class="p-6">
                        <form method="post" id="formTambah" class="space-y-4">
                            <input type="hidden" name="action" value="create">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Username <span class="text-red-500">*</span></label>
                                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" name="username" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Password <span class="text-red-500">*</span></label>
                                <input type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" name="password" required minlength="6">
                                <small class="text-gray-500">Min. 6 karakter</small>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Role <span class="text-red-500">*</span></label>
                                <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" name="role" id="roleSelect" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="project_manager">Project Manager</option>
                                    <option value="team_member">Team Member</option>
                                </select>
                            </div>
                            
                            <div id="pmSelectDiv" style="display:none;">
                                <label class="block text-sm font-medium text-gray-700">Project Manager <span class="text-red-500">*</span></label>
                                <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" name="project_manager_id" id="pmSelect">
                                    <option value="">-- Pilih PM --</option>
                                    <?php foreach ($pm_list as $pm): ?>
                                        <option value="<?= $pm['id'] ?>"><?= htmlspecialchars($pm['username']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-gray-500">Wajib untuk Team Member</small>
                            </div>
                            
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                <i class="bi bi-save mr-2"></i> Simpan User
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b">
                        <h5 class="text-xl font-semibold"><i class="bi bi-list-ul"></i> Daftar Users</h5>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Project Manager</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php
                                $users = mysqli_query($connect, "SELECT u.id, u.username, u.role, u.project_manager_id, pm.username AS pm_name
                                                                FROM users u
                                                                LEFT JOIN users pm ON pm.id = u.project_manager_id
                                                                ORDER BY 
                                                                    CASE u.role
                                                                        WHEN 'super_admin' THEN 1
                                                                        WHEN 'project_manager' THEN 2
                                                                        WHEN 'team_member' THEN 3
                                                                    END, u.username");
                                while ($u = mysqli_fetch_assoc($users)):
                                    $badge_color = 'bg-green-100 text-green-800';
                                    if ($u['role'] === 'super_admin') $badge_color = 'bg-red-100 text-red-800';
                                    if ($u['role'] === 'project_manager') $badge_color = 'bg-blue-100 text-blue-800';
                                ?>
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-700"><?= $u['id'] ?></td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900"><?= htmlspecialchars($u['username']) ?></td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?= $badge_color ?>">
                                            <?= htmlspecialchars($u['role']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <?php if ($u['role'] === 'team_member'): ?>
                                            <?php if ($u['pm_name']): ?>
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    <?= htmlspecialchars($u['pm_name']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-red-500">Belum ada</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm space-x-2">
                                        <?php if ($u['role'] === 'team_member'): ?>
                                            <a href="users.php?edit_pm_for=<?= $u['id'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-md text-gray-900 bg-yellow-400 hover:bg-yellow-500">
                                                <i class="bi bi-pencil"></i> Edit PM
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($u['role'] !== 'super_admin'): ?>
                                            <a href="users.php?del=<?= $u['id'] ?>" 
                                               class="px-3 py-1.5 text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700" 
                                               onclick="return confirm('Yakin hapus user <?= htmlspecialchars($u['username']) ?>?')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </a>
                                        <?php else: ?>
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-600">Protected</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('roleSelect').addEventListener('change', function() {
            const pmDiv = document.getElementById('pmSelectDiv');
            const pmSelect = document.getElementById('pmSelect');
            
            if (this.value === 'team_member') {
                pmDiv.style.display = 'block';
                pmSelect.required = true;
            } else {
                pmDiv.style.display = 'none';
                pmSelect.required = false;
                pmSelect.value = '';
            }
        });
    </script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');
        const success = urlParams.get('success');
        let message = '';
        let type = '';
        if (error) {
            message = error;
            type = 'error';
        } else if (success) {
            message = success;
            type = 'success';
        }
        if (message) {
            const toast = document.createElement('div');
            let bgColor = type === 'success' ? 'bg-green-600' : 'bg-red-600';
            let icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
            toast.className = `fixed top-5 right-5 z-50 p-4 rounded-md shadow-lg text-white ${bgColor} flex items-center transition-opacity duration-300 ease-out opacity-0`;
            toast.innerHTML = `<i class="bi ${icon} mr-3 text-xl"></i> <div>${message}</div>`;
            document.body.appendChild(toast);
            window.history.replaceState(null, null, window.location.pathname);
            setTimeout(() => {
                toast.classList.remove('opacity-0');
            }, 100);
            setTimeout(() => {
                toast.classList.add('opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }
    });
    </script>
</body>
</html>