<?php
session_start();
require_once '../config/database.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user = getUserData();

// Get projects based on role
if ($user['role'] === 'super_admin') {
    $stmt = $pdo->query("
        SELECT p.*, u.username as manager_name 
        FROM projects p 
        JOIN users u ON p.manager_id = u.id 
        ORDER BY p.created_at DESC
    ");
} else {
    $stmt = $pdo->prepare("
        SELECT p.*, u.username as manager_name 
        FROM projects p 
        JOIN users u ON p.manager_id = u.id 
        WHERE p.manager_id = ? 
        ORDER BY p.created_at DESC
    ");
    $stmt->execute([$user['id']]);
}
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Proyek - Project Management</title>
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
            <h2><i class="bi bi-folder"></i> Manajemen Proyek</h2>
            <?php if (in_array($user['role'], ['super_admin', 'project_manager'])): ?>
                <a href="create.php" class="btn btn-primary">
                    <i class="bi bi-folder-plus"></i> Buat Proyek Baru
                </a>
            <?php endif; ?>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                Proyek berhasil <?php echo $_GET['success']; ?>!
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daftar Proyek</h5>
            </div>
            <div class="card-body">
                <?php if ($projects): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Proyek</th>
                                    <th>Deskripsi</th>
                                    <th>Manager</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($projects as $project): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($project['nama_proyek']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars(substr($project['deskripsi'], 0, 50)); ?>...</td>
                                        <td><?php echo htmlspecialchars($project['manager_name']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($project['tanggal_mulai'])); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($project['tanggal_selesai'])); ?></td>
                                        <td>
                                            <a href="../tasks/?project_id=<?php echo $project['id']; ?>" 
                                               class="btn btn-sm btn-info">
                                                <i class="bi bi-list-task"></i> Tugas
                                            </a>
                                            <?php if ($user['role'] === 'super_admin' || ($user['role'] === 'project_manager' && $project['manager_id'] == $user['id'])): ?>
                                                <a href="edit.php?id=<?php echo $project['id']; ?>" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <a href="delete.php?id=<?php echo $project['id']; ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Yakin ingin menghapus proyek ini?')">
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
                    <p class="text-muted text-center">Belum ada proyek.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>