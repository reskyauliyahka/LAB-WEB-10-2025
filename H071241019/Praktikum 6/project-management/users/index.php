<?php
session_start();
require_once '../config/database.php';

// Cek login dan role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: ../auth/login.php');
    exit;
}

$user = getUserData();

// Get all users
$stmt = $pdo->query("
    SELECT u.*, pm.username as project_manager_name 
    FROM users u 
    LEFT JOIN users pm ON u.project_manager_id = pm.id 
    ORDER BY u.role, u.username
");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Project Management</title>
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
            <h2><i class="bi bi-people"></i> Manajemen User</h2>
            <a href="create.php" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Tambah User Baru
            </a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                User berhasil <?php echo $_GET['success']; ?>!
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daftar Semua User</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Project Manager</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user_row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user_row['username']); ?></td>
                                    <td>
                                        <span class="role-badge role-<?php echo $user_row['role']; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $user_row['role'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($user_row['project_manager_name']): ?>
                                            <?php echo htmlspecialchars($user_row['project_manager_name']); ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($user_row['created_at'])); ?></td>
                                    <td>
                                        <?php if ($user_row['id'] != $_SESSION['user_id']): ?>
                                            <a href="delete.php?id=<?php echo $user_row['id']; ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Yakin ingin menghapus user ini?')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Current User</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>