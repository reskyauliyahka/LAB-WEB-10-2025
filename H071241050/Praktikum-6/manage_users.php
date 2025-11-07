<?php
// manage_users.php - Menggunakan Bootstrap 5 (REVISI LENGKAP)

require_once 'check_role.php';
// Hanya Super Admin yang boleh mengakses
check_role_access(['Super Admin']); 

require_once 'koneksi.php';

$message = '';
$action = $_GET['action'] ?? 'list';
$edit_user = null;

// --- Helper: Ambil Project Manager untuk dropdown ---
function get_project_managers($conn) {
    $pms = [];
    // Memastikan hanya PM yang diambil untuk daftar Manager
    $result = $conn->query("SELECT id, username FROM users WHERE role = 'Project Manager' ORDER BY username");
    while ($row = $result->fetch_assoc()) {
        $pms[] = $row;
    }
    return $pms;
}

// --- Logika CRUD Pengguna (POST) ---

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. LOGIKA CREATE (Tambah Pengguna Baru)
    if (isset($_POST['create_user'])) {
        $username = trim($_POST['username']);
        $input_password = $_POST['password']; 

        // HASHING AMAN
        $password_hashed = password_hash($input_password, PASSWORD_DEFAULT); 

        $role = $_POST['role'];
        // Jika Team Member, ambil ID Manager, jika tidak, set NULL
        $pm_id = ($role === 'Team Member') ? ($_POST['project_manager_id'] ?: null) : null;
        
        if ($role === 'Team Member' && empty($pm_id)) {
            $message = "Error: Team Member wajib memiliki Project Manager.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, password, role, project_manager_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $username, $password_hashed, $role, $pm_id); 
            
            if ($stmt->execute()) {
                $message = "Pengguna **" . htmlspecialchars($username) . "** berhasil ditambahkan.";
                // Redirect setelah INSERT berhasil
                header('Location: manage_users.php?message=' . urlencode($message) . '&action=list');
                exit(); 
            } else {
                $message = "Error: Gagal menambahkan pengguna. Mungkin username sudah ada. SQL Error: " . $stmt->error;
            }
            $stmt->close();
        }
    } 
    
    // 2. LOGIKA UPDATE (Edit Pengguna)
    elseif (isset($_POST['update_user'])) {
        $user_id = $_POST['user_id'];
        $username = trim($_POST['username']);
        $role = $_POST['role'];
        $pm_id = ($role === 'Team Member') ? ($_POST['project_manager_id'] ?: null) : null;
        $password_field = $_POST['password'];

        $sql = "UPDATE users SET username = ?, role = ?, project_manager_id = ?";
        $params = ['ssi', $username, $role, $pm_id];
        
        // Jika password diisi, hash dan update password juga
        if (!empty($password_field)) {
            $password_hashed = password_hash($password_field, PASSWORD_DEFAULT);
            $sql .= ", password = ?";
            $params[0] .= 's'; 
            $params[] = $password_hashed;
        }

        $sql .= " WHERE id = ?";
        $params[0] .= 'i';
        $params[] = $user_id;

        $stmt = $conn->prepare($sql);
        // Memastikan parameter di-bind dengan urutan yang benar
        $stmt->bind_param(...$params); 

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                 $message = "Pengguna **" . htmlspecialchars($username) . "** berhasil diperbarui.";
            } else {
                 $message = "Tidak ada perubahan disimpan.";
            }
           
            // Redirect ke list setelah update berhasil
            header('Location: manage_users.php?message=' . urlencode($message));
            exit();
        } else {
            $message = "Error: Gagal memperbarui pengguna. SQL Error: " . $stmt->error;
        }
        $stmt->close();

    } 
    
    // 3. LOGIKA DELETE (Hapus Pengguna) - DENGAN TRANSAKSI
    elseif (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];
        
        // Cek Admin tidak menghapus dirinya sendiri
        if ($user_id == $_SESSION['user_id']) {
            $message = "Error: Anda tidak dapat menghapus akun Anda sendiri.";
        } else {
            
            $conn->begin_transaction(); // Mulai Transaksi
            try {
                // DELETE users. Karena ada ON DELETE CASCADE/SET NULL di FK, MySQL harusnya membersihkan:
                // 1. Projects (dan Tasks di dalamnya) jika user adalah Project Manager (CASCADE)
                // 2. project_manager_id di Team Members jika user adalah Project Manager (SET NULL)
                
                $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
                $stmt->bind_param("i", $user_id);
                
                if (!$stmt->execute()) {
                    throw new Exception("Gagal menjalankan DELETE query.");
                }

                if ($stmt->affected_rows === 0) {
                    $conn->rollback();
                    $message = "Error: Pengguna tidak ditemukan.";
                } else {
                    $conn->commit(); // Komit jika berhasil
                    $message = "Pengguna berhasil dihapus (beserta data terkait).";
                }
                $stmt->close();
                
                // Redirect setelah POST berhasil
                header('Location: manage_users.php?message=' . urlencode($message) . '&action=list');
                exit();
                
            } catch (Exception $e) {
                $conn->rollback(); // Rollback jika ada error
                // Tampilkan error SQL yang ditangkap (biasanya karena FK tidak terduga)
                $message = "Error Penghapusan! Relasi data gagal diatasi. Pesan Sistem: " . $e->getMessage();
            }
        }
    }
}


// --- Logika GET & READ ---

// Logika untuk menampilkan form edit
if ($action === 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT id, username, role, project_manager_id FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_user = $result->fetch_assoc();
    $stmt->close();

    if (!$edit_user) {
        $message = "Pengguna tidak ditemukan.";
        $action = 'list';
    }
}

// Logika Baca (READ) List Pengguna
$users = [];
$sql_list = "
    SELECT 
        u.id, u.username, u.role, u.project_manager_id, 
        pm.username AS manager_username 
    FROM users u
    LEFT JOIN users pm ON u.project_manager_id = pm.id
    WHERE u.role IN ('Project Manager', 'Team Member') 
    ORDER BY u.role, u.username
";
$result_list = $conn->query($sql_list);
while ($row = $result_list->fetch_assoc()) {
    $users[] = $row;
}
$project_managers = get_project_managers($conn);

if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}

// Tutup koneksi sebelum output HTML
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - Admin</title>
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
            <a class="navbar-brand" href="#">**Super Admin** Dashboard</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard_super_admin.php">Semua Proyek</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manage_users.php">Kelola Pengguna</a>
                    </li>
                </ul>
                <span class="navbar-text me-3">Halo, **<?= htmlspecialchars($_SESSION['username']) ?>**</span>
                <a class="btn btn-danger" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Kelola Project Manager & Team Member</h1>

        <?php if ($message): ?>
            <div class="alert alert-<?= strpos($message, 'Error') !== false ? 'danger' : 'success' ?>" role="alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="d-flex mb-3">
            <a href="manage_users.php?action=list" class="btn btn-<?= $action === 'list' ? 'primary' : 'outline-primary' ?> me-2">Daftar Pengguna</a>
            <a href="manage_users.php?action=add" class="btn btn-<?= $action === 'add' ? 'success' : 'outline-success' ?>">Tambah Pengguna Baru</a>
            <?php if ($action === 'edit'): ?>
                <span class="btn btn-warning ms-2">Edit Pengguna</span>
            <?php endif; ?>
        </div>
        
        <div class="card shadow-sm card-shadow-hover">
            <div class="card-body">
                <?php if ($action === 'add' || $action === 'edit'): ?>
                    <h3 class="card-title"><?= $action === 'add' ? 'Tambah' : 'Edit' ?> Pengguna</h3>
                    <form method="POST" action="manage_users.php">
                        <?php if ($action === 'edit'): ?>
                            <input type="hidden" name="user_id" value="<?= $edit_user['id'] ?>">
                            <input type="hidden" name="update_user" value="1">
                        <?php else: ?>
                            <input type="hidden" name="create_user" value="1">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" id="username" name="username" class="form-control" value="<?= $edit_user['username'] ?? '' ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password: <small class="text-muted">(<?= $action === 'edit' ? 'Kosongkan jika tidak ingin diubah' : 'Wajib diisi' ?>)</small></label>
                            <input type="password" id="password" name="password" class="form-control" <?= $action === 'add' ? 'required' : '' ?>>
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Role:</label>
                            <select id="role" name="role" class="form-select" onchange="togglePMDropdown()" required>
                                <option value="Project Manager" <?= ($edit_user['role'] ?? '') === 'Project Manager' ? 'selected' : '' ?>>Project Manager</option>
                                <option value="Team Member" <?= ($edit_user['role'] ?? '') === 'Team Member' ? 'selected' : '' ?>>Team Member</option>
                            </select>
                        </div>

                        <div class="mb-3" id="pm-group" style="display: <?= ($edit_user['role'] ?? '') === 'Team Member' ? 'block' : 'none' ?>;">
                            <label for="project_manager_id" class="form-label">Project Manager (untuk Team Member):</label>
                            <select name="project_manager_id" class="form-select">
                                <option value="">-- Pilih Manager --</option>
                                <?php foreach ($project_managers as $pm): ?>
                                    <option value="<?= $pm['id'] ?>" <?= ($edit_user['project_manager_id'] ?? '') == $pm['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($pm['username']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary"><?= $action === 'add' ? 'Tambah' : 'Simpan Perubahan' ?></button>
                        <a href="manage_users.php" class="btn btn-secondary">Batal</a>
                    </form>
                    <script>
                        // Fungsi JS untuk menampilkan/menyembunyikan dropdown PM
                        function togglePMDropdown() {
                            var role = document.getElementById('role').value;
                            var pmGroup = document.getElementById('pm-group');
                            if (role === 'Team Member') {
                                pmGroup.style.display = 'block';
                            } else {
                                pmGroup.style.display = 'none';
                            }
                        }
                        togglePMDropdown();
                    </script>
                <?php else: // Aksi 'list' ?>
                    <h3 class="card-title">Daftar Pengguna</h3>
                    <?php if (!empty($users)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Project Manager</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($users as $user): ?>
                                    <tr>
                                        <td><?= $user['id'] ?></td>
                                        <td><?= htmlspecialchars($user['username']) ?></td>
                                        <td><span class="badge bg-<?= $user['role'] === 'Project Manager' ? 'info' : 'secondary' ?>"><?= htmlspecialchars($user['role']) ?></span></td>
                                        <td><?= $user['manager_username'] ? htmlspecialchars($user['manager_username']) : '-' ?></td>
                                        <td>
                                            <a href="manage_users.php?action=edit&id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-warning me-1">Edit</a>
                                            <form method="POST" action="manage_users.php" style="display:inline;" onsubmit="return confirm('Yakin menghapus pengguna ini? Semua data terkait (proyek/tugas) mungkin ikut terhapus atau berubah menjadi NULL.')">
                                                <input type="hidden" name="delete_user" value="1">
                                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">Tidak ada Project Manager atau Team Member yang terdaftar.</div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>