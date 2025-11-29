<?php
session_start();
require_once '../config/database.php';

// Cek login dan role
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['super_admin', 'project_manager'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user = getUserData();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_proyek = $_POST['nama_proyek'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $manager_id = $user['role'] === 'super_admin' ? $_POST['manager_id'] : $user['id'];
    
    // Validate dates
    if (strtotime($tanggal_selesai) < strtotime($tanggal_mulai)) {
        $error = "Tanggal selesai harus setelah tanggal mulai!";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO projects (nama_proyek, deskripsi, tanggal_mulai, tanggal_selesai, manager_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nama_proyek, $deskripsi, $tanggal_mulai, $tanggal_selesai, $manager_id]);
            
            header('Location: index.php?success=ditambahkan');
            exit;
        } catch (PDOException $e) {
            $error = "Gagal menambahkan proyek: " . $e->getMessage();
        }
    }
}

// Get project managers for super admin
if ($user['role'] === 'super_admin') {
    $stmt = $pdo->query("SELECT id, username FROM users WHERE role = 'project_manager' ORDER BY username");
    $project_managers = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Proyek Baru - Project Management</title>
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
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-folder-plus"></i> Buat Proyek Baru</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="nama_proyek" class="form-label">Nama Proyek</label>
                                <input type="text" class="form-control" id="nama_proyek" name="nama_proyek" required>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                                    </div>
                                </div>
                            </div>

                            <?php if ($user['role'] === 'super_admin'): ?>
                                <div class="mb-3">
                                    <label for="manager_id" class="form-label">Project Manager</label>
                                    <select class="form-select" id="manager_id" name="manager_id" required>
                                        <option value="">Pilih Project Manager</option>
                                        <?php foreach ($project_managers as $pm): ?>
                                            <option value="<?php echo $pm['id']; ?>">
                                                <?php echo htmlspecialchars($pm['username']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Buat Proyek</button>
                                <a href="index.php" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>