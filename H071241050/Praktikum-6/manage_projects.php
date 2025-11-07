<?php
// manage_projects.php - Menggunakan Bootstrap 5

require_once 'check_role.php';
check_role_access(['Project Manager']); 

require_once 'koneksi.php';

$manager_id = $_SESSION['user_id'];
$message = '';
$action = $_GET['action'] ?? 'list';
$edit_project = null;

// --- Logika CRUD Proyek (POST) ---
// (Logika CRUD POST sama seperti sebelumnya)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ... (Logika CRUD POST di sini) ...
    // Sederhanakan: Jika berhasil atau gagal, buat pesan dan redirect ke list.
    if (isset($_POST['create_project'])) {
        $nama_proyek = $_POST['nama_proyek'];
        $deskripsi = $_POST['deskripsi'];
        $tanggal_mulai = $_POST['tanggal_mulai'];
        $tanggal_selesai = $_POST['tanggal_selesai'];

        $stmt = $conn->prepare("INSERT INTO projects (nama_proyek, deskripsi, tanggal_mulai, tanggal_selesai, manager_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $nama_proyek, $deskripsi, $tanggal_mulai, $tanggal_selesai, $manager_id);
        
        if ($stmt->execute()) {
            $message = "Proyek **" . htmlspecialchars($nama_proyek) . "** berhasil ditambahkan.";
            header('Location: manage_projects.php?message=' . urlencode($message));
            exit();
        } else {
            $message = "Error: Gagal menambahkan proyek.";
        }
        $stmt->close();
    }
    // ... (Logika UPDATE dan DELETE POST di sini) ...
}

// Logika untuk menampilkan form edit
if ($action === 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT id, nama_proyek, deskripsi, tanggal_mulai, tanggal_selesai FROM projects WHERE id = ? AND manager_id = ?");
    $stmt->bind_param("ii", $id, $manager_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_project = $result->fetch_assoc();
    $stmt->close();

    if (!$edit_project) {
        $message = "Proyek tidak ditemukan atau bukan milik Anda. Akses ditolak.";
        $action = 'list';
    }
}



// Logika Baca (READ) List Proyek
$projects = [];
$sql_list = "SELECT id, nama_proyek, tanggal_mulai, tanggal_selesai FROM projects WHERE manager_id = ? ORDER BY tanggal_mulai DESC";
$stmt_list = $conn->prepare($sql_list);
$stmt_list->bind_param("i", $manager_id);
$stmt_list->execute();
$result_list = $stmt_list->get_result();
while ($row = $result_list->fetch_assoc()) {
    $projects[] = $row;
}
$stmt_list->close();

if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Proyek - Project Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div id="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">**PM** Dashboard</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard_project_manager.php">Kelola Tugas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manage_projects.php">Kelola Proyek</a>
                    </li>
                </ul>
                <span class="navbar-text me-3">Halo, **<?= htmlspecialchars($_SESSION['username']) ?>**</span>
                <a class="btn btn-danger" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Kelola Proyek Saya</h1>

        <?php if ($message): ?>
            <div class="alert alert-<?= strpos($message, 'Error') !== false ? 'danger' : 'success' ?>" role="alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="d-flex mb-3">
            <a href="manage_projects.php?action=list" class="btn btn-<?= $action === 'list' ? 'primary' : 'outline-primary' ?> me-2">Daftar Proyek</a>
            <a href="manage_projects.php?action=add" class="btn btn-<?= $action === 'add' ? 'success' : 'outline-success' ?>">Tambah Proyek Baru</a>
            <?php if ($action === 'edit'): ?>
                <span class="btn btn-warning ms-2">Edit Proyek</span>
            <?php endif; ?>
        </div>

        <div class="card shadow-sm card-shadow-hover">
            <div class="card-body">
                <?php if ($action === 'add' || $action === 'edit'): ?>
                    <h3 class="card-title"><?= $action === 'add' ? 'Tambah' : 'Edit' ?> Proyek</h3>
                    <form method="POST" action="manage_projects.php">
                        <?php if ($action === 'edit'): ?>
                            <input type="hidden" name="project_id" value="<?= $edit_project['id'] ?>">
                            <input type="hidden" name="update_project" value="1">
                        <?php else: ?>
                            <input type="hidden" name="create_project" value="1">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="nama_proyek" class="form-label">Nama Proyek:</label>
                            <input type="text" id="nama_proyek" name="nama_proyek" class="form-control" value="<?= $edit_project['nama_proyek'] ?? '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi:</label>
                            <textarea id="deskripsi" name="deskripsi" class="form-control"><?= $edit_project['deskripsi'] ?? '' ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai:</label>
                            <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" value="<?= $edit_project['tanggal_mulai'] ?? '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai:</label>
                            <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control" value="<?= $edit_project['tanggal_selesai'] ?? '' ?>" required>
                        </div>

                        <button type="submit" class="btn btn-primary"><?= $action === 'add' ? 'Tambah' : 'Simpan Perubahan' ?></button>
                        <a href="manage_projects.php" class="btn btn-secondary">Batal</a>
                    </form>
                <?php else: // Aksi 'list' ?>
                    <h3 class="card-title">Daftar Proyek Saya</h3>
                    <?php if (!empty($projects)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Proyek</th>
                                        <th>Mulai</th>
                                        <th>Selesai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($projects as $project): ?>
                                    <tr>
                                        <td><?= $project['id'] ?></td>
                                        <td><?= htmlspecialchars($project['nama_proyek']) ?></td>
                                        <td><?= $project['tanggal_mulai'] ?></td>
                                        <td><?= $project['tanggal_selesai'] ?></td>
                                        <td>
                                            <a href="dashboard_project_manager.php?project_id=<?= $project['id'] ?>" class="btn btn-sm btn-info me-1">Lihat Tugas</a>
                                            <a href="manage_projects.php?action=edit&id=<?= $project['id'] ?>" class="btn btn-sm btn-outline-warning me-1">Edit</a>
                                            <form method="POST" action="manage_projects.php" style="display:inline;" onsubmit="return confirm('Yakin menghapus proyek ini? SEMUA TUGAS TERKAIT AKAN HILANG!')">
                                                <input type="hidden" name="delete_project" value="1">
                                                <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">Anda belum memiliki proyek. Silakan tambahkan proyek baru.</div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
<?php $conn->close(); ?>