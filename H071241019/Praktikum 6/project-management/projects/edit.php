<?php
session_start();
require_once '../config/database.php';

// Cek login dan role
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['super_admin', 'project_manager'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user = getUserData();

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$task_id = $_GET['id'];

// Get task data
$stmt = $pdo->prepare("
    SELECT t.*, p.manager_id 
    FROM tasks t 
    JOIN projects p ON t.project_id = p.id 
    WHERE t.id = ?
");
$stmt->execute([$task_id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

// Check permission
if (!$task || ($user['role'] === 'project_manager' && $task['manager_id'] != $user['id'])) {
    header('Location: index.php');
    exit;
}

// Get projects for dropdown
if ($user['role'] === 'super_admin') {
    $stmt = $pdo->query("SELECT id, nama_proyek FROM projects ORDER BY nama_proyek");
} else {
    $stmt = $pdo->prepare("SELECT id, nama_proyek FROM projects WHERE manager_id = ? ORDER BY nama_proyek");
    $stmt->execute([$user['id']]);
}
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get team members for dropdown
$stmt = $pdo->query("SELECT id, username FROM users WHERE role = 'team_member' ORDER BY username");
$team_members = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_tugas = $_POST['nama_tugas'];
    $deskripsi = $_POST['deskripsi'];
    $project_id = $_POST['project_id'];
    $assigned_to = $_POST['assigned_to'];
    $status = $_POST['status'];
    
    try {
        $stmt = $pdo->prepare("UPDATE tasks SET nama_tugas = ?, deskripsi = ?, project_id = ?, assigned_to = ?, status = ? WHERE id = ?");
        $stmt->execute([$nama_tugas, $deskripsi, $project_id, $assigned_to, $status, $task_id]);
        
        header('Location: index.php?success=diupdate');
        exit;
    } catch (PDOException $e) {
        $error = "Gagal mengupdate tugas: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas - Project Management</title>
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
                        <h5 class="mb-0"><i class="bi bi-pencil"></i> Edit Tugas</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="nama_tugas" class="form-label">Nama Tugas</label>
                                <input type="text" class="form-control" id="nama_tugas" name="nama_tugas" 
                                       value="<?php echo htmlspecialchars($task['nama_tugas']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required><?php echo htmlspecialchars($task['deskripsi']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="project_id" class="form-label">Proyek</label>
                                <select class="form-select" id="project_id" name="project_id" required>
                                    <option value="">Pilih Proyek</option>
                                    <?php foreach ($projects as $project): ?>
                                        <option value="<?php echo $project['id']; ?>" 
                                            <?php echo $project['id'] == $task['project_id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($project['nama_proyek']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="assigned_to" class="form-label">Ditugaskan ke</label>
                                <select class="form-select" id="assigned_to" name="assigned_to" required>
                                    <option value="">Pilih Team Member</option>
                                    <?php foreach ($team_members as $member): ?>
                                        <option value="<?php echo $member['id']; ?>" 
                                            <?php echo $member['id'] == $task['assigned_to'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($member['username']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="belum" <?php echo $task['status'] === 'belum' ? 'selected' : ''; ?>>Belum</option>
                                    <option value="proses" <?php echo $task['status'] === 'proses' ? 'selected' : ''; ?>>Proses</option>
                                    <option value="selesai" <?php echo $task['status'] === 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Update Tugas</button>
                                <a href="index.php" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>