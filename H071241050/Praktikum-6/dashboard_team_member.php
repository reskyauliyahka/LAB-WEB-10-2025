<?php
// dashboard_team_member.php - Menggunakan Bootstrap 5

require_once 'check_role.php';
check_role_access(['Team Member']); 

require_once 'koneksi.php';

$team_member_id = $_SESSION['user_id'];
$message = '';

// --- Logika Update Status Tugas (Team Member) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $task_id = $_POST['task_id'];
    $new_status = $_POST['status']; 

    $stmt_check = $conn->prepare("SELECT id FROM tasks WHERE id = ? AND assigned_to = ?");
    $stmt_check->bind_param("ii", $task_id, $team_member_id);
    $stmt_check->execute();
    
    if ($stmt_check->get_result()->num_rows > 0) {
        $stmt_check->close();
        
        $stmt_update = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ? AND assigned_to = ?");
        $stmt_update->bind_param("sii", $new_status, $task_id, $team_member_id);
        
        if ($stmt_update->execute()) {
            $message = "Status tugas berhasil diperbarui menjadi " . htmlspecialchars($new_status) . ".";
        } else {
            $message = "Error: Gagal memperbarui status tugas.";
        }
        $stmt_update->close();
    } else {
        $message = "Error: Anda tidak memiliki hak akses untuk mengubah tugas ini.";
    }
}

// --- Logika Baca Tugas (READ) ---
$sql = "
    SELECT 
        t.id AS task_id, t.nama_tugas, t.deskripsi AS task_deskripsi, t.status, 
        p.nama_proyek, 
        u.username AS manager_username
    FROM tasks t
    JOIN projects p ON t.project_id = p.id
    JOIN users u ON p.manager_id = u.id
    WHERE t.assigned_to = ?
    ORDER BY p.id, t.id
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $team_member_id);
$stmt->execute();
$result = $stmt->get_result();
$tasks_by_project = [];

while ($row = $result->fetch_assoc()) {
    $project_name = $row['nama_proyek'];
    if (!isset($tasks_by_project[$project_name])) {
        $tasks_by_project[$project_name] = [
            'manager' => $row['manager_username'],
            'tasks' => []
        ];
    }
    $tasks_by_project[$project_name]['tasks'][] = $row;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Team Member</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div id="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-info">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><strong>Team Member Dashboard</strong></a>
            <span class="navbar-text me-3 ms-auto">
                Halo, <?= htmlspecialchars($_SESSION['username']) ?>
            </span>
            <a class="btn btn-light" href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Tugas Saya</h1>
        <?php include 'dashboard_ringkas.php'; ?>

        <?php if ($message): ?>
            <div class="alert alert-<?= strpos($message, 'Error') !== false ? 'danger' : 'success' ?>" role="alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($tasks_by_project)): ?>
            <?php foreach ($tasks_by_project as $project_name => $data): ?>
                <div class="card shadow-sm card-shadow-hover mb-4">
                    <div class="card-header card-header-custom">
                        <h3 class="mb-0 text-white">Proyek: <?= htmlspecialchars($project_name) ?></h3>
                        <small class="text-white-50">Manager: **<?= htmlspecialchars($data['manager']) ?>**</small>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Tugas</th>
                                        <th>Deskripsi</th>
                                        <th>Status Saat Ini</th>
                                        <th>Ubah Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['tasks'] as $task): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($task['nama_tugas']) ?></td>
                                        <td><p class="text-muted small"><?= htmlspecialchars($task['task_deskripsi']) ?></p></td>
                                        <td><span class="badge bg-<?= strtolower($task['status']) == 'selesai' ? 'success' : (strtolower($task['status']) == 'proses' ? 'warning text-dark' : 'danger') ?>"><?= htmlspecialchars($task['status']) ?></span></td>
                                        <td>
                                            <form method="POST" action="dashboard_team_member.php">
                                                <input type="hidden" name="task_id" value="<?= $task['task_id'] ?>">
                                                <input type="hidden" name="update_status" value="1">
                                                
                                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <?php 
                                                    $statuses = ['belum', 'proses', 'selesai'];
                                                    foreach ($statuses as $s): ?>
                                                        <option value="<?= $s ?>" <?= ($s == $task['status']) ? 'selected' : '' ?>>
                                                            <?= ucwords($s) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info">Anda belum ditugaskan pada proyek atau tugas apa pun.</div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
<?php $conn->close(); ?>