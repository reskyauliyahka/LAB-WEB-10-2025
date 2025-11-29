<?php
session_start();
require_once '../config/database.php';

// Cek login dan role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: ../auth/login.php');
    exit;
}

$user = getUserData();

// Get all project managers for dropdown
$stmt = $pdo->query("SELECT id, username FROM users WHERE role = 'project_manager'");
$project_managers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $project_manager_id = $_POST['project_manager_id'] ?: null;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role, project_manager_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $password, $role, $project_manager_id]);
        
        header('Location: index.php?success=ditambahkan');
        exit;
    } catch (PDOException $e) {
        $error = "Gagal menambahkan user: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User - Project Management</title>
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
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-person-plus"></i> Tambah User Baru</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="project_manager">Project Manager</option>
                                    <option value="team_member">Team Member</option>
                                </select>
                            </div>

                            <div class="mb-3" id="projectManagerField" style="display: none;">
                                <label for="project_manager_id" class="form-label">Project Manager</label>
                                <select class="form-select" id="project_manager_id" name="project_manager_id">
                                    <option value="">Pilih Project Manager</option>
                                    <?php foreach ($project_managers as $pm): ?>
                                        <option value="<?php echo $pm['id']; ?>">
                                            <?php echo htmlspecialchars($pm['username']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Tambah User</button>
                                <a href="index.php" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('role').addEventListener('change', function() {
            const projectManagerField = document.getElementById('projectManagerField');
            if (this.value === 'team_member') {
                projectManagerField.style.display = 'block';
                document.getElementById('project_manager_id').required = true;
            } else {
                projectManagerField.style.display = 'none';
                document.getElementById('project_manager_id').required = false;
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>