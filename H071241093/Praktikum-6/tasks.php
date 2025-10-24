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
function can_update_task_status(mysqli $db, int $tid, int $uid): bool {
    $s = mysqli_prepare($db, "SELECT 1 FROM tasks WHERE id=? AND assigned_to=?");
    mysqli_stmt_bind_param($s, "ii", $tid, $uid);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    return (bool)mysqli_fetch_row($r);
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "create") {
    if (!in_array($role, ["project_manager","super_admin"], true)) { http_response_code(403); exit("Akses ditolak"); }
    $pid = (int)($_POST["project_id"] ?? 0);
    if (!can_manage_project($connect, $pid, $uid, $role)) { http_response_code(403); exit("Akses ditolak"); }
    $nama = trim($_POST["nama_tugas"] ?? "");
    $desk = trim($_POST["deskripsi"] ?? "");
    $status = $_POST["status"] ?? "belum";
    $assignee_raw = $_POST["assigned_to"] ?? "";
    if (empty($nama)) {
        header("Location: tasks.php?project={$pid}&error=" . urlencode("Nama tugas wajib diisi")); exit;
    }
    if (!in_array($status, ['belum', 'proses', 'selesai'], true)) { $status = 'belum'; }
    $assignee = ($assignee_raw === "") ? NULL : (int)$assignee_raw;
    if ($assignee !== NULL) {
        $check = mysqli_prepare($connect, "SELECT role, project_manager_id FROM users WHERE id=?");
        mysqli_stmt_bind_param($check, "i", $assignee);
        mysqli_stmt_execute($check);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($check));
        if (!$user || $user['role'] !== 'team_member') {
            header("Location: tasks.php?project={$pid}&error=" . urlencode("Hanya bisa assign ke Team Member")); exit;
        }
        if ($role === 'project_manager') {
            if ((int)$user['project_manager_id'] !== $uid) {
                header("Location: tasks.php?project={$pid}&error=" . urlencode("Team Member tidak berada di bawah PM Anda")); exit;
            }
        }
    }
    $stmt = mysqli_prepare($connect, "INSERT INTO tasks (nama_tugas, deskripsi, status, project_id, assigned_to) VALUES (?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "sssii", $nama, $desk, $status, $pid, $assignee);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: tasks.php?project={$pid}&success=" . urlencode("Task berhasil ditambahkan"));
    } else {
        header("Location: tasks.php?project={$pid}&error=" . urlencode("Gagal menambahkan task"));
    }
    exit;
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "edit") {
    if (!in_array($role, ["project_manager","super_admin"], true)) { http_response_code(403); exit("Akses ditolak"); }
    $tid = (int)($_POST["task_id"] ?? 0);
    $q = mysqli_prepare($connect, "SELECT project_id FROM tasks WHERE id=?");
    mysqli_stmt_bind_param($q, "i", $tid);
    mysqli_stmt_execute($q);
    $res = mysqli_stmt_get_result($q);
    $task = mysqli_fetch_assoc($res);
    if (!$task) { header("Location: tasks.php?error=" . urlencode("Task tidak ditemukan")); exit; }
    $pid = (int)$task["project_id"];
    if (!can_manage_project($connect, $pid, $uid, $role)) { http_response_code(403); exit("Akses ditolak"); }
    $nama = trim($_POST["nama_tugas"] ?? "");
    $desk = trim($_POST["deskripsi"] ?? "");
    $status = $_POST["status"] ?? "belum";
    $assignee_raw = $_POST["assigned_to"] ?? "";
    if (empty($nama)) {
        header("Location: tasks.php?project={$pid}&error=" . urlencode("Nama tugas wajib diisi")); exit;
    }
    if (!in_array($status, ['belum', 'proses', 'selesai'], true)) { $status = 'belum'; }
    $assignee = ($assignee_raw === "") ? NULL : (int)$assignee_raw;
    if ($assignee !== NULL) {
        $check = mysqli_prepare($connect, "SELECT role, project_manager_id FROM users WHERE id=?");
        mysqli_stmt_bind_param($check, "i", $assignee);
        mysqli_stmt_execute($check);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($check));
        if (!$user || $user['role'] !== 'team_member') {
            header("Location: tasks.php?project={$pid}&error=" . urlencode("Hanya bisa assign ke Team Member")); exit;
        }
        if ($role === 'project_manager') {
            if ((int)$user['project_manager_id'] !== $uid) {
                header("Location: tasks.php?project={$pid}&error=" . urlencode("Team Member tidak berada di bawah PM Anda")); exit;
            }
        }
    }
    $stmt = mysqli_prepare($connect, "UPDATE tasks SET nama_tugas=?, deskripsi=?, status=?, assigned_to=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "sssii", $nama, $desk, $status, $assignee, $tid);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: tasks.php?project={$pid}&success=" . urlencode("Task berhasil diupdate"));
    } else {
        header("Location: tasks.php?project={$pid}&error=" . urlencode("Gagal mengupdate task"));
    }
    exit;
}
if (isset($_GET["del"])) {
    $tid = (int)$_GET["del"];
    $q = mysqli_prepare($connect, "SELECT project_id FROM tasks WHERE id=?");
    mysqli_stmt_bind_param($q, "i", $tid);
    mysqli_stmt_execute($q);
    $res = mysqli_stmt_get_result($q);
    $row = mysqli_fetch_assoc($res);
    if (!$row) { header("Location: tasks.php?error=" . urlencode("Task tidak ditemukan")); exit; }
    $pid = (int)$row["project_id"];
    if (!in_array($role, ["project_manager","super_admin"], true)) { http_response_code(403); exit("Akses ditolak"); }
    if (!can_manage_project($connect, $pid, $uid, $role)) { http_response_code(403); exit("Akses ditolak"); }
    $d = mysqli_prepare($connect, "DELETE FROM tasks WHERE id=?");
    mysqli_stmt_bind_param($d, "i", $tid);
    if (mysqli_stmt_execute($d)) {
        header("Location: tasks.php?project={$pid}&success=" . urlencode("Task berhasil dihapus"));
    } else {
        header("Location: tasks.php?project={$pid}&error=" . urlencode("Gagal menghapus task"));
    }
    exit;
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "update_status_member") {
    if ($role !== "team_member") { http_response_code(403); exit("Akses ditolak"); }
    $tid = (int)($_POST["task_id"] ?? 0);
    $status = $_POST["status"] ?? "belum";
    if (!in_array($status, ['belum', 'proses', 'selesai'], true)) {
        header("Location: tasks.php?view=my&error=" . urlencode("Status tidak valid")); exit;
    }
    if (!can_update_task_status($connect, $tid, $uid)) { http_response_code(403); exit("Task bukan milikmu"); }
    $u = mysqli_prepare($connect, "UPDATE tasks SET status=? WHERE id=?");
    if (!$u) { die("Prepare failed: " . mysqli_error($connect)); }
    mysqli_stmt_bind_param($u, "si", $status, $tid);
    if (mysqli_stmt_execute($u)) {
        header("Location: tasks.php?view=my&success=" . urlencode("Status berhasil diupdate"));
    } else {
        die("Execute failed: " . mysqli_stmt_error($u));
    }
    exit;
}

$view = $_GET["view"] ?? "";
$project_id = isset($_GET["project"]) ? (int)$_GET["project"] : 0;
$edit_status_id = isset($_GET["edit_status"]) ? (int)$_GET["edit_status"] : 0;
$edit_task_id = isset($_GET["edit"]) ? (int)$_GET["edit"] : 0;

$edit_task = null;
if ($edit_task_id > 0 && in_array($role, ["project_manager","super_admin"], true)) {
    $q = mysqli_prepare($connect, "SELECT t.*, p.id as project_id FROM tasks t JOIN projects p ON p.id=t.project_id WHERE t.id=?");
    mysqli_stmt_bind_param($q, "i", $edit_task_id);
    mysqli_stmt_execute($q);
    $edit_task = mysqli_fetch_assoc(mysqli_stmt_get_result($q));
    if ($edit_task && !can_manage_project($connect, (int)$edit_task['project_id'], $uid, $role)) {
        $edit_task = null;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks - Manajemen Proyek</title>
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
                        
                        <?php if ($role === "project_manager" || $role === "super_admin"): ?>
                            <a href="projects.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Projects</a>
                            <a href="tasks.php" class="bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium">Tasks</a>
                        <?php else: ?>
                            <a href="tasks.php?view=my" class="bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium">My Tasks</a>
                        <?php endif; ?>
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
        <h2 class="text-3xl font-bold text-gray-800 mb-6"><i class="bi bi-list-task"></i> Tasks</h2>

        <?php
        if ($role === "team_member" && $view === "my") :
        ?>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h5 class="text-xl font-semibold"><i class="bi bi-list-check"></i> Tugas Saya</h5>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Tugas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proyek</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            $stmt = mysqli_prepare($connect, "SELECT t.id, t.nama_tugas, t.deskripsi, t.status, p.nama_proyek
                                                              FROM tasks t 
                                                              JOIN projects p ON p.id=t.project_id
                                                              WHERE t.assigned_to=? 
                                                              ORDER BY t.id DESC");
                            mysqli_stmt_bind_param($stmt, "i", $uid);
                            mysqli_stmt_execute($stmt);
                            $res = mysqli_stmt_get_result($stmt);
                            
                            while ($t = mysqli_fetch_assoc($res)):
                                $badge_class = 'bg-yellow-100 text-yellow-800';
                                if ($t['status'] === 'selesai') $badge_class = 'bg-green-100 text-green-800';
                                if ($t['status'] === 'proses') $badge_class = 'bg-blue-100 text-blue-800';
                            ?>
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700"><?= $t['id'] ?></td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900"><?= htmlspecialchars($t['nama_tugas']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($t['nama_proyek']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate"><?= htmlspecialchars($t['deskripsi'] ?: '-') ?></td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?= $badge_class ?>">
                                        <?= $t['status'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="tasks.php?edit_status=<?= $t['id'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                        <i class="bi bi-pencil"></i> Update Status
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php
        elseif ($role === "team_member" && $edit_status_id > 0) :
            if (!can_update_task_status($connect, $edit_status_id, $uid)):
        ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md" role="alert">
                    <i class="bi bi-x-circle"></i> Akses ditolak - Task ini bukan milik Anda.
                </div>
                <a href="tasks.php?view=my" class="px-4 py-2 border rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="bi bi-arrow-left"></i> Kembali ke My Tasks
                </a>
            <?php
            else:
                $g = mysqli_prepare($connect, "SELECT t.nama_tugas, t.deskripsi, t.status, p.nama_proyek 
                                                FROM tasks t JOIN projects p ON p.id=t.project_id 
                                                WHERE t.id=?");
                mysqli_stmt_bind_param($g, "i", $edit_status_id);
                mysqli_stmt_execute($g);
                $rs = mysqli_stmt_get_result($g);
                $tt = mysqli_fetch_assoc($rs);
                
                if (!$tt):
            ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md" role="alert">
                    <i class="bi bi-x-circle"></i> Task tidak ditemukan.
                </div>
            <?php
                else:
            ?>
                <div class="bg-white shadow-md rounded-lg overflow-hidden max-w-lg mx-auto">
                    <div class="bg-gray-800 text-white p-4">
                        <h5 class="text-lg font-semibold"><i class="bi bi-pencil"></i> Update Status Task</h5>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="bg-blue-100 text-blue-800 p-4 rounded-md text-sm">
                            <p><strong>Nama Task:</strong> <?= htmlspecialchars($tt["nama_tugas"]) ?></p>
                            <p><strong>Proyek:</strong> <?= htmlspecialchars($tt["nama_proyek"]) ?></p>
                            <p><strong>Deskripsi:</strong> <?= htmlspecialchars($tt["deskripsi"] ?: '-') ?></p>
                        </div>
                        
                        <form method="post" class="space-y-4">
                            <input type="hidden" name="action" value="update_status_member">
                            <input type="hidden" name="task_id" value="<?= $edit_status_id ?>">
                            
                            <div>
                                <?php
                                $badge_class = 'bg-yellow-100 text-yellow-800';
                                if ($tt['status'] === 'selesai') $badge_class = 'bg-green-100 text-green-800';
                                if ($tt['status'] === 'proses') $badge_class = 'bg-blue-100 text-blue-800';
                                ?>
                                <label class="block text-sm font-medium text-gray-700">Status Saat Ini:</label>
                                <span class="mt-1 px-2.5 py-0.5 rounded-full text-sm font-medium <?= $badge_class ?>">
                                    <?= $tt['status'] ?>
                                </span>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ubah Status Ke: <span class="text-red-500">*</span></label>
                                <select name="status" class="mt-1 block w-full text-lg rounded-md border-gray-300 shadow-sm" required>
                                    <option value="belum" <?= $tt['status']==='belum' ? 'selected' : '' ?>>🔴 Belum Dikerjakan</option>
                                    <option value="proses" <?= $tt['status']==='proses' ? 'selected' : '' ?>>🟡 Sedang Proses</option>
                                    <option value="selesai" <?= $tt['status']==='selesai' ? 'selected' : '' ?>>🟢 Selesai</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="w-full flex justify-center py-3 px-4 border rounded-md shadow-sm text-lg font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                <i class="bi bi-save mr-2"></i> Simpan Status
                            </button>
                            <a href="tasks.php?view=my" class="w-full flex justify-center py-2 px-4 border rounded-md shadow-sm text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 mt-2">
                                <i class="bi bi-arrow-left mr-2"></i> Kembali
                            </a>
                        </form>
                    </div>
                </div>
            <?php
                endif;
            endif;
        endif;

        if ($project_id > 0 && ($role === "project_manager" || $role === "super_admin")) :
            if (!can_manage_project($connect, $project_id, $uid, $role)):
        ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md" role="alert">Akses ditolak.</div>
            <?php
            else:
                $p = mysqli_prepare($connect, "SELECT nama_proyek FROM projects WHERE id=?");
                mysqli_stmt_bind_param($p, "i", $project_id);
                mysqli_stmt_execute($p);
                $r = mysqli_stmt_get_result($p);
                $proj = mysqli_fetch_assoc($r);
            ?>
                <div class="mb-4">
                    <a href="projects.php" class="px-4 py-2 border rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="bi bi-arrow-left"></i> Kembali ke Projects
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-1">
                        <div class="bg-white shadow-md rounded-lg overflow-hidden">
                            <div class="bg-gray-800 text-white p-4">
                                <h5 class="text-lg font-semibold">
                                    <i class="bi bi-<?= $edit_task ? 'pencil' : 'plus-circle' ?>"></i> 
                                    <?= $edit_task ? 'Edit Task' : 'Buat Task Baru' ?>
                                </h5>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="bg-blue-100 text-blue-800 p-3 rounded-md text-sm">
                                    <strong>Proyek:</strong> <?= htmlspecialchars($proj["nama_proyek"] ?? "Unknown") ?>
                                </div>
                                
                                <form method="post" class="space-y-4">
                                    <input type="hidden" name="action" value="<?= $edit_task ? 'edit' : 'create' ?>">
                                    <input type="hidden" name="project_id" value="<?= $project_id ?>">
                                    <?php if ($edit_task): ?>
                                        <input type="hidden" name="task_id" value="<?= $edit_task['id'] ?>">
                                    <?php endif; ?>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nama Task <span class="text-red-500">*</span></label>
                                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" name="nama_tugas" 
                                               value="<?= $edit_task ? htmlspecialchars($edit_task['nama_tugas']) : '' ?>" required>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                        <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" name="deskripsi" rows="3"><?= $edit_task ? htmlspecialchars($edit_task['deskripsi']) : '' ?></textarea>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                                        <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                            <option value="belum" <?= ($edit_task && $edit_task['status']==='belum') ? 'selected' : '' ?>>Belum</option>
                                            <option value="proses" <?= ($edit_task && $edit_task['status']==='proses') ? 'selected' : '' ?>>Proses</option>
                                            <option value="selesai" <?= ($edit_task && $edit_task['status']==='selesai') ? 'selected' : '' ?>>Selesai</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Assign ke</label>
                                        <select name="assigned_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="">- Tidak ada -</option>
                                            <?php
                                            if ($role === 'project_manager') {
                                                $us = mysqli_prepare($connect, "SELECT id, username FROM users WHERE role='team_member' AND project_manager_id=? ORDER BY username");
                                                mysqli_stmt_bind_param($us, "i", $uid);
                                                mysqli_stmt_execute($us);
                                                $us_res = mysqli_stmt_get_result($us);
                                            } else {
                                                $us_res = mysqli_query($connect, "SELECT id, username FROM users WHERE role='team_member' ORDER BY username");
                                            }
                                            while ($u = mysqli_fetch_assoc($us_res)):
                                            ?>
                                                <option value="<?= $u['id'] ?>" <?= ($edit_task && $edit_task['assigned_to']==$u['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($u['username']) ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                        <small class="text-gray-500">Opsional - hanya Team Member</small>
                                    </div>
                                    
                                    <button type="submit" class="w-full flex justify-center py-2 px-4 border rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                        <i class="bi bi-save mr-2"></i> <?= $edit_task ? 'Update' : 'Simpan' ?>
                                    </button>
                                    
                                    <?php if ($edit_task): ?>
                                        <a href="tasks.php?project=<?= $project_id ?>" class="w-full flex justify-center py-2 px-4 border rounded-md shadow-sm text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 mt-2">
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
                                <h5 class="text-xl font-semibold"><i class="bi bi-list-ul"></i> Daftar Tasks</h5>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Task</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assignee</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php
                                        $stmt = mysqli_prepare($connect, "SELECT t.id, t.nama_tugas, t.status, u.username AS assignee
                                                                          FROM tasks t 
                                                                          LEFT JOIN users u ON u.id=t.assigned_to
                                                                          WHERE t.project_id=? 
                                                                          ORDER BY t.id DESC");
                                        mysqli_stmt_bind_param($stmt, "i", $project_id);
                                        mysqli_stmt_execute($stmt);
                                        $res = mysqli_stmt_get_result($stmt);
                                        
                                        while ($t = mysqli_fetch_assoc($res)):
                                            $badge_class = 'bg-yellow-100 text-yellow-800';
                                            if ($t['status'] === 'selesai') $badge_class = 'bg-green-100 text-green-800';
                                            if ($t['status'] === 'proses') $badge_class = 'bg-blue-100 text-blue-800';
                                        ?>
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-700"><?= $t['id'] ?></td>
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900"><?= htmlspecialchars($t['nama_tugas']) ?></td>
                                            <td class="px-6 py-4 text-sm">
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?= $badge_class ?>">
                                                    <?= $t['status'] ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <?php if ($t['assignee']): ?>
                                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-800">
                                                        <?= htmlspecialchars($t['assignee']) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-400">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 text-sm space-x-2">
                                                <a href="tasks.php?project=<?= $project_id ?>&edit=<?= $t['id'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-md text-gray-900 bg-yellow-400 hover:bg-yellow-500">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="tasks.php?del=<?= $t['id'] ?>" 
                                                   class="px-3 py-1.5 text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700" 
                                                   onclick="return confirm('Yakin hapus task ini?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            endif;
        endif;

        if ($role === "team_member" && !$view && !$edit_status_id) :
        ?>
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4 rounded-md" role="alert">
                <i class="bi bi-info-circle"></i> 
                Buka <a href="tasks.php?view=my" class="font-bold underline">My Tasks</a> untuk melihat tugasmu.
            </div>
        <?php
        endif;

        if (($role === "project_manager" || $role === "super_admin") && !$project_id && !$edit_task_id) :
        ?>
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4 rounded-md" role="alert">
                <i class="bi bi-info-circle"></i> 
                Pilih proyek dari halaman <a href="projects.php" class="font-bold underline">Projects</a> untuk mengelola tasks.
            </div>
        <?php
        endif;
        ?>
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