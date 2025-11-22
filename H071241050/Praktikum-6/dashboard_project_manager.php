<?php
// dashboard_project_manager.php - Menggunakan Bootstrap 5

require_once 'check_role.php';
check_role_access(['Project Manager']); 

require_once 'koneksi.php';

$manager_id = $_SESSION['user_id'];
$message = '';
$current_project_id = $_GET['project_id'] ?? null;
$projects = [];
$tasks = [];
$team_members = [];

// --- Logika Penanganan CRUD Tasks (CREATE, UPDATE, DELETE) ---
// (Logika POST disederhanakan, diasumsikan proses sama seperti sebelumnya)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $project_to_act_on = $_POST['project_id'] ?? $current_project_id;
    
    // Periksa kepemilikan proyek (RBAC Check)
    $stmt_check = $conn->prepare("SELECT id FROM projects WHERE id = ? AND manager_id = ?");
    $stmt_check->bind_param("ii", $project_to_act_on, $manager_id);
    $stmt_check->execute();
    
    if ($stmt_check->get_result()->num_rows === 0) {
        $message = "Error: Akses ditolak. Proyek tidak ditemukan atau bukan milik Anda.";
    } else {
        // Logika CREATE
        if ($action === 'create_task') {
            // ... (Pengambilan input dari formulir) ...
            $nama_tugas = $_POST['nama_tugas'];
            $deskripsi = $_POST['deskripsi'];
            $assigned_to = $_POST['assigned_to'];
            
            // Lakukan validasi Team Member di sini sebelum INSERT

            $stmt = $conn->prepare("INSERT INTO tasks (nama_tugas, deskripsi, project_id, assigned_to) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssii", $nama_tugas, $deskripsi, $project_to_act_on, $assigned_to);
            if ($stmt->execute()) {
                $message = "Tugas berhasil ditambahkan.";
            } else {
                $message = "Error: Gagal menambahkan tugas.";
            }
            $stmt->close();
        } 
        // Logika UPDATE dan DELETE task (seperti yang ada di kode sebelumnya)
        elseif ($action === 'delete_task') {
            $task_id = $_POST['task_id'];
            $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
            $stmt->bind_param("i", $task_id);
            if ($stmt->execute()) {
                $message = "Tugas berhasil dihapus.";
            } else {
                $message = "Error: Gagal menghapus tugas.";
            }
            $stmt->close();
        }
    }
    $stmt_check->close();
}




// --- Logika READ Data ---

// 1. Ambil SEMUA proyek milik Manager ini untuk ditampilkan di dashboard
$sql_projects = "SELECT id, nama_proyek FROM projects WHERE manager_id = ?";
$stmt_projects = $conn->prepare($sql_projects);
$stmt_projects->bind_param("i", $manager_id);
$stmt_projects->execute();
$result_projects = $stmt_projects->get_result();
while ($row = $result_projects->fetch_assoc()) {
    $projects[] = $row;
}
// Membersihkan prepared statement untuk membebaskan sumber daya server.
$stmt_projects->close();

if (!$current_project_id && !empty($projects)) {
    $current_project_id = $projects[0]['id']; 
}

// 2. Ambil SEMUA Team Member
$sql_tm = "SELECT id, username FROM users WHERE role = 'Team Member' AND project_manager_id = ?";
$stmt_tm = $conn->prepare($sql_tm);
$stmt_tm->bind_param("i", $manager_id);
$stmt_tm->execute();
$result_tm = $stmt_tm->get_result();
while ($row = $result_tm->fetch_assoc()) {
    $team_members[] = $row;
}
$stmt_tm->close();

// 3. Ambil SEMUA tasks pada proyek yang sedang dipilih
if ($current_project_id) {
    $sql_tasks = "SELECT t.id, t.nama_tugas, t.deskripsi, t.status, t.assigned_to, u.username AS assigned_username 
                  FROM tasks t
                  JOIN users u ON t.assigned_to = u.id
                  WHERE t.project_id = ?";
    $stmt_tasks = $conn->prepare($sql_tasks);
    $stmt_tasks->bind_param("i", $current_project_id);
    $stmt_tasks->execute();
    $result_tasks = $stmt_tasks->get_result();
    while ($row = $result_tasks->fetch_assoc()) {
        $tasks[] = $row;
    }
    $stmt_tasks->close();
}
// untuk mengambil nama proyek
$current_project_name = '';
if ($current_project_id) {
    foreach ($projects as $p) {
        if ($p['id'] == $current_project_id) {
            $current_project_name = $p['nama_proyek'];
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Project Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div id="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><strong>PM Dashboard</strong></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard_project_manager.php">Kelola Tugas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_projects.php">Kelola Proyek</a>
                    </li>
                </ul>
                <span class="navbar-text me-3">
                    Halo, <?= htmlspecialchars($_SESSION['username']) ?>
                </span>
                <a class="btn btn-danger" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Kelola Tugas Proyek</h1>
        <?php include 'dashboard_ringkas.php'; ?>

        <?php if ($message): ?>
            <div class="alert alert-<?= strpos($message, 'Error') !== false ? 'danger' : 'success' ?>" role="alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm card-shadow-hover">
                    <div class="card-header card-header-custom">Pilih Proyek</div>
                    <div class="card-body">
                        <form method="GET" action="dashboard_project_manager.php">
                            <label for="project_select" class="form-label">Proyek Saya:</label>
                            <select name="project_id" id="project_select" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Pilih Proyek --</option>
                                <?php foreach ($projects as $project): ?>
                                    <option value="<?= $project['id'] ?>" <?= ($project['id'] == $current_project_id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($project['nama_proyek']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <?php if ($current_project_id): ?>
                <div class="card shadow-sm card-shadow-hover h-100">
                    <div class="card-header card-header-custom">Proyek Saat Ini</div>
                    <div class="card-body">
                        <h5><?= htmlspecialchars($current_project_name) ?></h5>
                        <p class="text-muted">Gunakan form di bawah untuk menambah atau mengelola tugas pada proyek ini.</p>
                        <a href="manage_projects.php?action=edit&id=<?= $current_project_id ?>" class="btn btn-sm btn-outline-primary">Edit Detail Proyek</a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($current_project_id): ?>
        
            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm card-shadow-hover mb-4">
                        <div class="card-header bg-success text-white">Tambah Tugas Baru</div>
                        <div class="card-body">
                            <form method="POST" action="dashboard_project_manager.php?project_id=<?= $current_project_id ?>">
                                <input type="hidden" name="action" value="create_task">
                                <input type="hidden" name="project_id" value="<?= $current_project_id ?>">

                                <div class="mb-3">
                                    <label for="nama_tugas" class="form-label">Nama Tugas:</label>
                                    <input type="text" id="nama_tugas" name="nama_tugas" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi:</label>
                                    <textarea id="deskripsi" name="deskripsi" class="form-control"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="assigned_to" class="form-label">Ditugaskan Kepada:</label>
                                    <select name="assigned_to" class="form-select" required>
                                        <option value="">-- Pilih Team Member --</option>
                                        <?php if (empty($team_members)): ?>
                                            <option value="" disabled>-- Tidak Ada Team Member Anda --</option>
                                        <?php endif; ?>
                                        <?php foreach ($team_members as $tm): ?>
                                            <option value="<?= $tm['id'] ?>">
                                                <?= htmlspecialchars($tm['username']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success">Tambah Tugas</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="card shadow-sm card-shadow-hover">
                        <div class="card-header bg-primary text-white">Daftar Tugas</div>
                        <div class="card-body">
                            <?php if (!empty($tasks)): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Tugas</th>
                                                <th>Ditugaskan Ke</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($tasks as $task): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($task['nama_tugas']) ?></td>
                                                <td><span class="badge bg-secondary"><?= htmlspecialchars($task['assigned_username']) ?></span></td>
                                                <td><span class="badge bg-<?= strtolower($task['status']) == 'selesai' ? 'success' : (strtolower($task['status']) == 'proses' ? 'warning' : 'danger') ?>"><?= htmlspecialchars($task['status']) ?></span></td>
                                                <td>
                                                    <a href="edit_task.php?task_id=<?= $task['id'] ?>" class="btn btn-sm btn-outline-warning me-1">Edit</a>
                                                    <form method="POST" action="dashboard_project_manager.php?project_id=<?= $current_project_id ?>" style="display:inline;" onsubmit="return confirm('Yakin hapus tugas ini?')">
                                                        <input type="hidden" name="action" value="delete_task">
                                                        <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                                        <input type="hidden" name="project_id" value="<?= $current_project_id ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info mb-0">Tidak ada tugas di proyek ini.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">Silakan pilih proyek di atas atau buat proyek baru di menu **Kelola Proyek**.</div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>

<?php $conn->close(); ?>