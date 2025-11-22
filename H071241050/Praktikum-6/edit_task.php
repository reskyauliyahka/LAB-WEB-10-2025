<?php
// edit_task.php - Menggunakan Bootstrap 5

require_once 'check_role.php';
check_role_access(['Project Manager']); 

require_once 'koneksi.php';

$manager_id = $_SESSION['user_id'];
$message = '';
$task_id = $_GET['task_id'] ?? null;
$task = null;
$team_members = [];
$statuses = ['belum', 'proses', 'selesai'];

if (!$task_id) {
    header('Location: dashboard_project_manager.php');
    exit();
}

// --- Logika UPDATE Task ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_task'])) {
    $nama_tugas = $_POST['nama_tugas'];
    $deskripsi = $_POST['deskripsi'];
    $status = $_POST['status'];
    $assigned_to = $_POST['assigned_to'];
    $project_id = $_POST['project_id'];

    // RBAC Check (Penting): Pastikan tugas berada di proyek milik Manager ini
    $stmt_check = $conn->prepare("SELECT t.id FROM tasks t JOIN projects p ON t.project_id = p.id WHERE t.id = ? AND p.manager_id = ?");
    $stmt_check->bind_param("ii", $task_id, $manager_id);
    $stmt_check->execute();
    
    if ($stmt_check->get_result()->num_rows > 0) {
        $stmt_check->close();

        // Lakukan UPDATE
        $stmt = $conn->prepare("UPDATE tasks SET nama_tugas = ?, deskripsi = ?, status = ?, assigned_to = ? WHERE id = ?");
        $stmt->bind_param("sssii", $nama_tugas, $deskripsi, $status, $assigned_to, $task_id);
        
        if ($stmt->execute()) {
            $message = "Tugas **" . htmlspecialchars($nama_tugas) . "** berhasil diperbarui.";
            header('Location: dashboard_project_manager.php?project_id=' . $project_id . '&message=' . urlencode($message));
            exit();
        } else {
            $message = "Error: Gagal memperbarui tugas.";
        }
        $stmt->close();
    } else {
        $message = "Error: Tugas tidak ditemukan pada proyek Anda atau akses ditolak.";
    }
}

// --- Logika Baca Tugas Saat Ini ---
$sql_task = "
    SELECT 
        t.id, t.nama_tugas, t.deskripsi, t.status, t.assigned_to, t.project_id,
        p.nama_proyek
    FROM tasks t
    JOIN projects p ON t.project_id = p.id
    WHERE t.id = ? AND p.manager_id = ?
";
$stmt_task = $conn->prepare($sql_task);
$stmt_task->bind_param("ii", $task_id, $manager_id);
$stmt_task->execute();
$result_task = $stmt_task->get_result();
$task = $result_task->fetch_assoc();
$stmt_task->close();

if (!$task) {
    $conn->close();
    header('Location: dashboard_project_manager.php?message=' . urlencode('Akses ditolak atau Tugas tidak ditemukan.'));
    exit();
}

// Ambil Team Member
$sql_tm = "SELECT id, username FROM users WHERE role = 'Team Member' AND project_manager_id = ?";
$stmt_tm = $conn->prepare($sql_tm);
$stmt_tm->bind_param("i", $manager_id);
$stmt_tm->execute();
$result_tm = $stmt_tm->get_result();
while ($row = $result_tm->fetch_assoc()) {
    $team_members[] = $row;
}
$stmt_tm->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas - Project Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-3">Edit Tugas: **<?= htmlspecialchars($task['nama_tugas']) ?>**</h1>
        <p class="text-muted">Proyek: *<?= htmlspecialchars($task['nama_proyek']) ?>*</p>

        <?php if ($message): ?>
            <div class="alert alert-<?= strpos($message, 'Error') !== false ? 'danger' : 'success' ?>" role="alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="edit_task.php?task_id=<?= $task_id ?>">
                    <input type="hidden" name="update_task" value="1">
                    <input type="hidden" name="project_id" value="<?= $task['project_id'] ?>">
                    
                    <div class="mb-3">
                        <label for="nama_tugas" class="form-label">Nama Tugas:</label>
                        <input type="text" id="nama_tugas" name="nama_tugas" class="form-control" value="<?= htmlspecialchars($task['nama_tugas']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi:</label>
                        <textarea id="deskripsi" name="deskripsi" class="form-control" required><?= htmlspecialchars($task['deskripsi']) ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Ditugaskan Kepada:</label>
                        <select name="assigned_to" class="form-select" required>
                            <?php foreach ($team_members as $tm): ?>
                                <option value="<?= $tm['id'] ?>" <?= ($tm['id'] == $task['assigned_to']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($tm['username']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status:</label>
                        <select name="status" class="form-select" required>
                            <?php foreach ($statuses as $s): ?>
                                <option value="<?= $s ?>" <?= ($s == $task['status']) ? 'selected' : '' ?>>
                                    <?= ucwords($s) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="dashboard_project_manager.php?project_id=<?= $task['project_id'] ?>" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>