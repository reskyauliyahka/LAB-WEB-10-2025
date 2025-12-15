<?php
session_start();
require_once '../config/database.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user = getUserData();

// Get tasks based on role
if ($user['role'] === 'super_admin') {
    $stmt = $pdo->query("
        SELECT t.*, p.nama_proyek, u.username as assigned_name 
        FROM tasks t 
        JOIN projects p ON t.project_id = p.id 
        JOIN users u ON t.assigned_to = u.id 
        ORDER BY t.created_at DESC
    ");
} elseif ($user['role'] === 'project_manager') {
    $stmt = $pdo->prepare("
        SELECT t.*, p.nama_proyek, u.username as assigned_name 
        FROM tasks t 
        JOIN projects p ON t.project_id = p.id 
        JOIN users u ON t.assigned_to = u.id 
        WHERE p.manager_id = ? 
        ORDER BY t.created_at DESC
    ");
    $stmt->execute([$user['id']]);
} else { // team_member
    $stmt = $pdo->prepare("
        SELECT t.*, p.nama_proyek, u.username as assigned_name 
        FROM tasks t 
        JOIN projects p ON t.project_id = p.id 
        JOIN users u ON t.assigned_to = u.id 
        WHERE t.assigned_to = ? 
        ORDER BY t.created_at DESC
    ");
    $stmt->execute([$user['id']]);
}

$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Tugas - Project Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="../dashboard/">
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-list-task"></i> Manajemen Tugas</h2>
            <?php if (in_array($user['role'], ['super_admin', 'project_manager'])): ?>
                <a href="create.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Buat Tugas Baru
                </a>
            <?php endif; ?>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                Tugas berhasil <?php echo $_GET['success']; ?>!
            </div>
        <?php endif; ?>

        <!-- Filter Status -->
        <div class="row mb-3">
            <div class="col-md-3">
                <select class="form-select" id="statusFilter" onchange="filterTasks()">
                    <option value="">Semua Status</option>
                    <option value="belum">Belum</option>
                    <option value="proses">Proses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daftar Tugas</h5>
            </div>
            <div class="card-body">
                <?php if ($tasks): ?>
                    <div class="table-responsive">
                        <table class="table table-striped" id="tasksTable">
                            <thead>
                                <tr>
                                    <th>Nama Tugas</th>
                                    <th>Deskripsi</th>
                                    <th>Proyek</th>
                                    <th>Ditugaskan ke</th>
                                    <th>Status</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tasks as $task): ?>
                                    <tr class="task-row" data-status="<?php echo $task['status']; ?>">
                                        <td>
                                            <strong><?php echo htmlspecialchars($task['nama_tugas']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars(substr($task['deskripsi'], 0, 50)); ?>...</td>
                                        <td><?php echo htmlspecialchars($task['nama_proyek']); ?></td>
                                        <td><?php echo htmlspecialchars($task['assigned_name']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php 
                                                echo $task['status'] === 'selesai' ? 'success' : 
                                                     ($task['status'] === 'proses' ? 'warning' : 'danger'); 
                                            ?>">
                                                <?php echo ucfirst($task['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($task['created_at'])); ?></td>
                                        <td>
                                            <?php if ($user['role'] === 'team_member' && $task['assigned_to'] == $user['id']): ?>
                                                <select class="form-select form-select-sm status-select" 
                                                        data-task-id="<?php echo $task['id']; ?>" 
                                                        style="width: 120px;">
                                                    <option value="belum" <?php echo $task['status'] === 'belum' ? 'selected' : ''; ?>>Belum</option>
                                                    <option value="proses" <?php echo $task['status'] === 'proses' ? 'selected' : ''; ?>>Proses</option>
                                                    <option value="selesai" <?php echo $task['status'] === 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                                                </select>
                                            <?php endif; ?>
                                            
                                            <?php if (in_array($user['role'], ['super_admin', 'project_manager'])): ?>
                                                <a href="edit.php?id=<?php echo $task['id']; ?>" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <a href="delete.php?id=<?php echo $task['id']; ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Yakin ingin menghapus tugas ini?')">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">Belum ada tugas.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>