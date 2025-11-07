<?php
// dashboard_super_admin.php - Menggunakan Bootstrap 5

require_once 'check_role.php';
check_role_access(['Super Admin']); 

require_once 'koneksi.php';

$message = '';

// Logika DELETE Project
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_project_id'])) {
    $project_id = $_POST['delete_project_id'];

    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->bind_param("i", $project_id);
        
        if ($stmt->execute()) {
            $conn->commit();
            $message = "Proyek berhasil dihapus.";
        } else {
            throw new Exception("Gagal menghapus proyek.");
        }
        $stmt->close();
    } catch (Exception $e) {
        $conn->rollback();
        $message = "Error: Gagal menghapus proyek.";
    }
}

// Logika READ (Ambil SEMUA proyek)
$sql = "SELECT p.id, p.nama_proyek, p.deskripsi, p.tanggal_mulai, p.tanggal_selesai, u.username AS manager_username 
        FROM projects p
        JOIN users u ON p.manager_id = u.id
        ORDER BY p.tanggal_mulai DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Super Admin</title>
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
            <a class="navbar-brand" href="#"><strong>Super Admin Dashboard</strong></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard_super_admin.php">Semua Proyek</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_users.php">Kelola Pengguna</a>
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
        <h1 class="mb-4">Daftar Semua Proyek</h1>
        <?php include 'dashboard_ringkas.php'; ?>

        <?php if ($message): ?>
            <div class="alert alert-<?= strpos($message, 'Error') !== false ? 'danger' : 'success' ?>" role="alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm card-shadow-hover">
            <div class="card-header card-header-custom">
                Informasi Proyek
            </div>
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Proyek</th>
                                    <th>Manager</th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['nama_proyek']) ?></td>
                                    <td><span class="badge bg-primary"><?= htmlspecialchars($row['manager_username']) ?></span></td>
                                    <td><?= $row['tanggal_mulai'] ?></td>
                                    <td><?= $row['tanggal_selesai'] ?></td>
                                    <td>
                                        <form method="POST" action="dashboard_super_admin.php" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus proyek ini? (Aksi Admin) Semua tugas akan hilang.')">
                                            <input type="hidden" name="delete_project_id" value="<?= $row['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">Tidak ada proyek ditemukan.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>

<?php $conn->close(); ?>