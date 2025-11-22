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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Manajemen Proyek</title>
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
                        <a href="dashboard.php" class="bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                        
                        <?php if ($role === "super_admin"): ?>
                            <a href="users.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Kelola Users</a>
                        <?php endif; ?>
                        
                        <?php if ($role === "project_manager" || $role === "super_admin"): ?>
                            <a href="projects.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Projects</a>
                            <a href="tasks.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Tasks</a>
                        <?php elseif ($role === "team_member"): ?>
                            <a href="tasks.php?view=my" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">My Tasks</a>
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
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Dashboard</h2>

        <?php if ($role === "super_admin"): ?>
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-md" role="alert">
                <p><i class="bi bi-shield-check"></i> <strong>Super Admin</strong> - Anda memiliki akses penuh ke seluruh sistem.</p>
            </div>

            <?php
            $stat = mysqli_query($connect, "SELECT 
                (SELECT COUNT(*) FROM users) AS users,
                (SELECT COUNT(*) FROM users WHERE role='project_manager') AS pm,
                (SELECT COUNT(*) FROM users WHERE role='team_member') AS tm,
                (SELECT COUNT(*) FROM projects) AS projects,
                (SELECT COUNT(*) FROM tasks) AS tasks,
                (SELECT COUNT(*) FROM tasks WHERE status='selesai') AS tasks_done");
            $row = mysqli_fetch_assoc($stat);
            ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <i class="bi bi-people-fill text-5xl text-indigo-600"></i>
                    <h3 class="text-4xl font-bold mt-2"><?= (int)$row["users"] ?></h3>
                    <p class="text-gray-500">Total Users</p>
                    <small>PM: <?= (int)$row["pm"] ?> | TM: <?= (int)$row["tm"] ?></small>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <i class="bi bi-folder-fill text-5xl text-indigo-600"></i>
                    <h3 class="text-4xl font-bold mt-2"><?= (int)$row["projects"] ?></h3>
                    <p class="text-gray-500">Total Projects</p>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <i class="bi bi-list-task text-5xl text-indigo-600"></i>
                    <h3 class="text-4xl font-bold mt-2"><?= (int)$row["tasks"] ?></h3>
                    <p class="text-gray-500">Total Tasks</p>
                    <small>Selesai: <?= (int)$row["tasks_done"] ?></small>
                </div>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h5 class="text-xl font-semibold"><i class="bi bi-folder2-open"></i> Semua Proyek</h5>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Proyek</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Manager</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Mulai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Selesai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            $q = mysqli_query($connect, "SELECT p.id, p.nama_proyek, p.tanggal_mulai, 
                                                        COALESCE(p.tanggal_selesai,'-') AS tsel, u.username
                                                        FROM projects p 
                                                        JOIN users u ON u.id=p.manager_id 
                                                        ORDER BY p.id DESC");
                            while ($p = mysqli_fetch_assoc($q)):
                            ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= $p['id'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900"><?= htmlspecialchars($p['nama_proyek']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <?= htmlspecialchars($p['username']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= $p['tanggal_mulai'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= $p['tsel'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="tasks.php?project=<?= $p['id'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        <i class="bi bi-list-check"></i> Tasks
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php elseif ($role === "project_manager"): ?>
            <div class="bg-indigo-100 border-l-4 border-indigo-500 text-indigo-700 p-4 mb-6 rounded-md" role="alert">
                <p><i class="bi bi-briefcase"></i> <strong>Project Manager</strong> - Kelola proyek dan tugas Anda.</p>
            </div>

            <?php
            $stat = mysqli_query($connect, "SELECT 
                (SELECT COUNT(*) FROM projects WHERE manager_id=$uid) AS my_projects,
                (SELECT COUNT(*) FROM tasks WHERE project_id IN (SELECT id FROM projects WHERE manager_id=$uid)) AS my_tasks,
                (SELECT COUNT(*) FROM tasks WHERE status='selesai' AND project_id IN (SELECT id FROM projects WHERE manager_id=$uid)) AS done_tasks");
            $row = mysqli_fetch_assoc($stat);
            ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                 <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <i class="bi bi-folder-fill text-5xl text-indigo-600"></i>
                    <h3 class="text-4xl font-bold mt-2"><?= (int)$row["my_projects"] ?></h3>
                    <p class="text-gray-500">Proyek Saya</p>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <i class="bi bi-list-task text-5xl text-indigo-600"></i>
                    <h3 class="text-4xl font-bold mt-2"><?= (int)$row["my_tasks"] ?></h3>
                    <p class="text-gray-500">Total Tasks</p>
                </div>
                 <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <i class="bi bi-check-circle-fill text-5xl text-green-500"></i>
                    <h3 class="text-4xl font-bold mt-2"><?= (int)$row["done_tasks"] ?></h3>
                    <p class="text-gray-500">Tasks Selesai</p>
                </div>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h5 class="text-xl font-semibold"><i class="bi bi-folder2-open"></i> Proyek Saya</h5>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Proyek</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Mulai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Selesai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            $q = mysqli_query($connect, "SELECT id, nama_proyek, tanggal_mulai, 
                                                        COALESCE(tanggal_selesai,'-') AS tsel
                                                        FROM projects WHERE manager_id = $uid 
                                                        ORDER BY id DESC");
                            while ($p = mysqli_fetch_assoc($q)):
                            ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= $p['id'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900"><?= htmlspecialchars($p['nama_proyek']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= $p['tanggal_mulai'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= $p['tsel'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="tasks.php?project=<?= $p['id'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        <i class="bi bi-list-check"></i> Tasks
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php else: ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                <p><i class="bi bi-person-check"></i> <strong>Team Member</strong> - Lihat dan update status tugas Anda.</p>
            </div>

            <?php
            $stat = mysqli_query($connect, "SELECT 
                (SELECT COUNT(*) FROM tasks WHERE assigned_to=$uid) AS my_tasks,
                (SELECT COUNT(*) FROM tasks WHERE assigned_to=$uid AND status='belum') AS pending,
                (SELECT COUNT(*) FROM tasks WHERE assigned_to=$uid AND status='proses') AS progress,
                (SELECT COUNT(*) FROM tasks WHERE assigned_to=$uid AND status='selesai') AS done");
            $row = mysqli_fetch_assoc($stat);
            ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <i class="bi bi-list-task text-5xl text-indigo-600"></i>
                    <h3 class="text-4xl font-bold mt-2"><?= (int)$row["my_tasks"] ?></h3>
                    <p class="text-gray-500">Total Tugas</p>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <i class="bi bi-hourglass-split text-5xl text-yellow-500"></i>
                    <h3 class="text-4xl font-bold mt-2"><?= (int)$row["pending"] ?></h3>
                    <p class="text-gray-500">Belum</p>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <i class="bi bi-clock-history text-5xl text-blue-500"></i>
                    <h3 class="text-4xl font-bold mt-2"><?= (int)$row["progress"] ?></h3>
                    <p class="text-gray-500">Proses</p>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <i class="bi bi-check-circle-fill text-5xl text-green-500"></i>
                    <h3 class="text-4xl font-bold mt-2"><?= (int)$row["done"] ?></h3>
                    <p class="text-gray-500">Selesai</p>
                </div>
            </div>

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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            $stm = mysqli_prepare($connect, "SELECT t.id, t.nama_tugas, t.status, p.nama_proyek
                                                            FROM tasks t 
                                                            JOIN projects p ON p.id=t.project_id
                                                            WHERE t.assigned_to=? 
                                                            ORDER BY t.id DESC");
                            mysqli_stmt_bind_param($stm, "i", $uid);
                            mysqli_stmt_execute($stm);
                            $res = mysqli_stmt_get_result($stm);
                            while ($t = mysqli_fetch_assoc($res)):
                                $badge_class = 'bg-yellow-100 text-yellow-800';
                                if ($t['status'] === 'selesai') $badge_class = 'bg-green-100 text-green-800';
                                if ($t['status'] === 'proses') $badge_class = 'bg-blue-100 text-blue-800';
                            ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= $t['id'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900"><?= htmlspecialchars($t['nama_tugas']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= htmlspecialchars($t['nama_proyek']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?= $badge_class ?>">
                                        <?= $t['status'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
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
        <?php endif; ?>
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