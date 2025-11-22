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
if (!in_array($_SESSION["role"], ["project_manager","super_admin"], true)) {
    http_response_code(403);
    exit("Akses ditolak");
}
require "connect.php";
$role = $_SESSION["role"];
$uid  = (int)$_SESSION["user_id"];
$username = htmlspecialchars($_SESSION["username"]);
function can_manage_project(mysqli $db, int $pid, int $uid, string $role): bool {
    if ($role === "super_admin") return true;
    if ($role !== "project_manager") return false;
    $s = mysqli_prepare($db, "SELECT 1 FROM projects WHERE id=? AND manager_id=?");
    mysqli_stmt_bind_param($s, "ii", $pid, $uid);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    return (bool)mysqli_fetch_row($r);
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "create") {
    $nama = trim($_POST["nama_proyek"] ?? "");
    $desk = trim($_POST["deskripsi"] ?? "");
    $mulai = $_POST["tanggal_mulai"] ?? "";
    $selesai = $_POST["tanggal_selesai"] ?? "";
    if (empty($nama) || empty($mulai)) {
        header("Location: projects.php?error=" . urlencode("Nama proyek dan tanggal mulai wajib diisi")); exit;
    }
    if (!empty($selesai) && $selesai < $mulai) {
        header("Location: projects.php?error=" . urlencode("Tanggal selesai tidak boleh lebih awal dari tanggal mulai")); exit;
    }
    $mulai_val = $mulai ?: NULL;
    $selesai_val = $selesai ?: NULL;
    if ($role === "project_manager") {
        $manager_id = $uid;
    } else {
        $manager_id = (int)($_POST["manager_id"] ?? 0);
        $chk = mysqli_prepare($connect, "SELECT 1 FROM users WHERE id=? AND role='project_manager'");
        mysqli_stmt_bind_param($chk, "i", $manager_id);
        mysqli_stmt_execute($chk);
        $ok = mysqli_fetch_row(mysqli_stmt_get_result($chk));
        if (!$ok) {
            header("Location: projects.php?error=" . urlencode("Manager ID bukan PM yang valid")); exit;
        }
    }
    $stmt = mysqli_prepare($connect, "INSERT INTO projects (nama_proyek, deskripsi, tanggal_mulai, tanggal_selesai, manager_id) VALUES (?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "ssssi", $nama, $desk, $mulai_val, $selesai_val, $manager_id);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: projects.php?success=" . urlencode("Proyek berhasil ditambahkan"));
    } else {
        header("Location: projects.php?error=" . urlencode("Gagal menambahkan proyek"));
    }
    exit;
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "edit") {
    $pid = (int)($_POST["project_id"] ?? 0);
    if (!can_manage_project($connect, $pid, $uid, $role)) {
        http_response_code(403); exit("Akses ditolak");
    }
    $nama = trim($_POST["nama_proyek"] ?? "");
    $desk = trim($_POST["deskripsi"] ?? "");
    $mulai = $_POST["tanggal_mulai"] ?? "";
    $selesai = $_POST["tanggal_selesai"] ?? "";
    if (empty($nama) || empty($mulai)) {
        header("Location: projects.php?error=" . urlencode("Nama proyek dan tanggal mulai wajib diisi")); exit;
    }
    if (!empty($selesai) && $selesai < $mulai) {
        header("Location: projects.php?error=" . urlencode("Tanggal selesai tidak boleh lebih awal dari tanggal mulai")); exit;
    }
    $mulai_val = $mulai ?: NULL;
    $selesai_val = $selesai ?: NULL;
    $stmt = mysqli_prepare($connect, "UPDATE projects SET nama_proyek=?, deskripsi=?, tanggal_mulai=?, tanggal_selesai=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ssssi", $nama, $desk, $mulai_val, $selesai_val, $pid);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: projects.php?success=" . urlencode("Proyek berhasil diupdate"));
    } else {
        header("Location: projects.php?error=" . urlencode("Gagal mengupdate proyek"));
    }
    exit;
}
if (isset($_GET["del"])) {
    $pid = (int)$_GET["del"];
    if (!can_manage_project($connect, $pid, $uid, $role)) {
        http_response_code(403); exit("Akses ditolak");
    }
    $d = mysqli_prepare($connect, "DELETE FROM projects WHERE id=?");
    mysqli_stmt_bind_param($d, "i", $pid);
    if (mysqli_stmt_execute($d)) {
        header("Location: projects.php?success=" . urlencode("Proyek berhasil dihapus"));
    } else {
        header("Location: projects.php?error=" . urlencode("Gagal menghapus proyek"));
    }
    exit;
}
$edit_project = null;
if (isset($_GET["edit"])) {
    $edit_id = (int)$_GET["edit"];
    if (can_manage_project($connect, $edit_id, $uid, $role)) {
        $stmt = mysqli_prepare($connect, "SELECT * FROM projects WHERE id=?");
        mysqli_stmt_bind_param($stmt, "i", $edit_id);
        mysqli_stmt_execute($stmt);
                                $edit_project = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - Manajemen Proyek</title>
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
                        <?php if ($role === "super_admin"): ?>
                            <a href="users.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Kelola Users</a>
                        <?php endif; ?>
                        <a href="projects.php" class="bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium">Projects</a>
                        <a href="tasks.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Tasks</a>
                    </div>
                </div>
                <div class="hidden md:flex items-center ml-4">
                     <span class="text-sm text-gray-300 mr-3">
                        <i class="bi bi-person-circle"></i> 
                        <strong><?= $username ?></strong>
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mr-3">
                        <?= htmlspecialchars($role) ?>
                    </span>
                    <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6"><i class="bi bi-folder-fill"></i> Projects</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="md:col-span-1">
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="bg-gray-800 text-white p-4">
                        <h5 class="text-lg font-semibold">
                            <i class="bi bi-<?= $edit_project ? 'pencil' : 'plus-circle' ?>"></i> 
                            <?= $edit_project ? 'Edit Proyek' : 'Buat Proyek Baru' ?>
                        </h5>
                    </div>
                    <div class="p-6">
                        <form method="post" class="space-y-4">
                            <input type="hidden" name="action" value="<?= $edit_project ? 'edit' : 'create' ?>">
                            <?php if ($edit_project): ?>
                                <input type="hidden" name="project_id" value="<?= $edit_project['id'] ?>">
                            <?php endif; ?>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Proyek <span class="text-red-500">*</span></label>
                                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" name="nama_proyek" 
                                       value="<?= $edit_project ? htmlspecialchars($edit_project['nama_proyek']) : '' ?>" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" name="deskripsi" rows="3"><?= $edit_project ? htmlspecialchars($edit_project['deskripsi']) : '' ?></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai <span class="text-red-500">*</span></label>
                                <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" name="tanggal_mulai" 
                                       value="<?= $edit_project ? $edit_project['tanggal_mulai'] : '' ?>" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                                <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" name="tanggal_selesai" 
                                       value="<?= $edit_project ? $edit_project['tanggal_selesai'] : '' ?>">
                                <small class="text-gray-500">Opsional</small>
                            </div>
                            
                            <?php if ($role === "super_admin" && !$edit_project): ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Manager (PM) <span class="text-red-500">*</span></label>
                                    <select name="manager_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                        <option value="">-- Pilih PM --</option>
                                        <?php
                                        $pm = mysqli_query($connect, "SELECT id, username FROM users WHERE role='project_manager' ORDER BY username");
                                        while ($r = mysqli_fetch_assoc($pm)):
                                        ?>
                                            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['username']) ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                            
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                <i class="bi bi-save mr-2"></i> <?= $edit_project ? 'Update' : 'Simpan' ?>
                            </button>
                            
                            <?php if ($edit_project): ?>
                                <a href="projects.php" class="w-full flex justify-center py-2 px-4 border rounded-md shadow-sm text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 mt-2">
                                    <i class="bi bi-x-circle mr-2"></i> Batal
                                </a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b">
                        <h5 class="text-xl font-semibold"><i class="bi bi-list-ul"></i> Daftar Proyek</h5>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <?php if ($role === "super_admin"): ?>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Manager</th>
                                    <?php endif; ?>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Proyek</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl. Mulai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl. Selesai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php
                                if ($role === "project_manager") {
                                    $stmt = mysqli_prepare($connect, "SELECT id, nama_proyek, tanggal_mulai, COALESCE(tanggal_selesai,'-') AS tsel 
                                                                      FROM projects WHERE manager_id=? ORDER BY id DESC");
                                    mysqli_stmt_bind_param($stmt, "i", $uid);
                                    mysqli_stmt_execute($stmt);
                                    $res = mysqli_stmt_get_result($stmt);
                                } else {
                                    $res = mysqli_query($connect, "SELECT p.id, p.nama_proyek, p.tanggal_mulai, COALESCE(p.tanggal_selesai,'-') AS tsel, u.username AS manager
                                                                   FROM projects p 
                                                                   JOIN users u ON u.id=p.manager_id 
                                                                   ORDER BY p.id DESC");
                                }
                                
                                while ($p = mysqli_fetch_assoc($res)):
                                ?>
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-700"><?= $p['id'] ?></td>
                                    <?php if ($role === "super_admin"): ?>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <?= htmlspecialchars($p['manager']) ?>
                                            </span>
                                        </td>
                                    <?php endif; ?>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900"><?= htmlspecialchars($p['nama_proyek']) ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-700"><?= $p['tanggal_mulai'] ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-700"><?= $p['tsel'] ?></td>
                                    <td class="px-6 py-4 text-sm space-x-2">
                                        <a href="tasks.php?project=<?= $p['id'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                            <i class="bi bi-list-check"></i>
                                        </a>
                                        <?php if (can_manage_project($connect, (int)$p["id"], $uid, $role)): ?>
                                            <a href="projects.php?edit=<?= $p['id'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-md text-gray-900 bg-yellow-400 hover:bg-yellow-500">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="projects.php?del=<?= $p['id'] ?>" 
                                               class="px-3 py-1.5 text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700" 
                                               onclick="return confirm('Yakin hapus proyek ini? Semua tasks akan terhapus!')">
                                                <i class="bi bi-trash"></i>
                                            </a>
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