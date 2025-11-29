<?php
session_start();
require_once '../config/database.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user = getUserData();

// Redirect team member ke tasks
if ($user['role'] === 'team_member') {
    header('Location: ../tasks/');
    exit;
}

// Hitung stats
$stats = [];

// Total projects
if ($user['role'] === 'super_admin') {
    $stmt = $pdo->query("SELECT COUNT(*) FROM projects");
} else {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM projects WHERE manager_id = ?");
    $stmt->execute([$user['id']]);
}
$stats['total_projects'] = $stmt->fetchColumn();

// Total tasks
if ($user['role'] === 'super_admin') {
    $stmt = $pdo->query("SELECT COUNT(*) FROM tasks");
} else {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks t JOIN projects p ON t.project_id = p.id WHERE p.manager_id = ?");
    $stmt->execute([$user['id']]);
}
$stats['total_tasks'] = $stmt->fetchColumn();

// Tasks by status
if ($user['role'] === 'super_admin') {
    $stmt = $pdo->query("SELECT status, COUNT(*) as count FROM tasks GROUP BY status");
} else {
    $stmt = $pdo->prepare("SELECT t.status, COUNT(*) as count FROM tasks t JOIN projects p ON t.project_id = p.id WHERE p.manager_id = ? GROUP BY t.status");
    $stmt->execute([$user['id']]);
}
$tasks_by_status = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stats['tasks_belum'] = 0;
$stats['tasks_proses'] = 0;
$stats['tasks_selesai'] = 0;

foreach ($tasks_by_status as $task) {
    switch ($task['status']) {
        case 'belum': $stats['tasks_belum'] = $task['count']; break;
        case 'proses': $stats['tasks_proses'] = $task['count']; break;
        case 'selesai': $stats['tasks_selesai'] = $task['count']; break;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Project Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-kanban"></i> Project Management
            </a>
            
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="bi bi-person-circle"></i> 
                    <?php echo htmlspecialchars($user['username']); ?>
                    <span class="role-badge role-<?php echo $user['role']; ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $user['role'])); ?>
                    </span>
                </span>
                <a class="nav-link" href="../auth/logout.php">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="stats-number"><?php echo $stats['total_projects']; ?></div>
                    <div class="stats-label">Total Proyek</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="stats-number"><?php echo $stats['total_tasks']; ?></div>
                    <div class="stats-label">Total Tugas</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stats-card">
                    <div class="stats-number text-warning"><?php echo $stats['tasks_belum']; ?></div>
                    <div class="stats-label">Belum</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stats-card">
                    <div class="stats-number text-info"><?php echo $stats['tasks_proses']; ?></div>
                    <div class="stats-label">Proses</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stats-card">
                    <div class="stats-number text-success"><?php echo $stats['tasks_selesai']; ?></div>
                    <div class="stats-label">Selesai</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php if ($user['role'] === 'super_admin'): ?>
                                <div class="col-md-3 mb-2">
                                    <a href="../users/create.php" class="btn btn-primary w-100">
                                        <i class="bi bi-person-plus"></i> Tambah User
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (in_array($user['role'], ['super_admin', 'project_manager'])): ?>
                                <div class="col-md-3 mb-2">
                                    <a href="../projects/create.php" class="btn btn-success w-100">
                                        <i class="bi bi-folder-plus"></i> Buat Proyek
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="../tasks/create.php" class="btn btn-info w-100">
                                        <i class="bi bi-plus-circle"></i> Buat Tugas
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="col-md-3 mb-2">
                                <a href="../projects/" class="btn btn-warning w-100">
                                    <i class="bi bi-folder"></i> Lihat Proyek
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Projects -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-clock"></i> Proyek Terbaru</h5>
                        <a href="../projects/" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        <?php
                        if ($user['role'] === 'super_admin') {
                            $stmt = $pdo->query("SELECT p.*, u.username as manager_name 
                                               FROM projects p 
                                               JOIN users u ON p.manager_id = u.id 
                                               ORDER BY p.created_at DESC 
                                               LIMIT 5");
                        } else {
                            $stmt = $pdo->prepare("SELECT p.*, u.username as manager_name 
                                                 FROM projects p 
                                                 JOIN users u ON p.manager_id = u.id 
                                                 WHERE p.manager_id = ? 
                                                 ORDER BY p.created_at DESC 
                                                 LIMIT 5");
                            $stmt->execute([$user['id']]);
                        }
                        $recent_projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        if ($recent_projects): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama Proyek</th>
                                            <th>Manager</th>
                                            <th>Tanggal Mulai</th>
                                            <th>Tanggal Selesai</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_projects as $project): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($project['nama_proyek']); ?></td>
                                                <td><?php echo htmlspecialchars($project['manager_name']); ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($project['tanggal_mulai'])); ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($project['tanggal_selesai'])); ?></td>
                                                <td>
                                                    <a href="../projects/?view=<?php echo $project['id']; ?>" 
                                                       class="btn btn-sm btn-outline-primary">Lihat</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted text-center">Belum ada proyek.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>