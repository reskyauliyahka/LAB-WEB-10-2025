<?php
require_once '../config/database.php';
include 'templates/header.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $success = false; 

    // Aksi TAMBAH PENGGUNA
    if (isset($_POST['add_user'])) {
        $username = $_POST['username'];
        
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt_check->execute([$username]);
        $user_exists = $stmt_check->fetchColumn();

        if ($user_exists > 0) {
            // Jika user ada, set pesan error
            $error_message = "Error: Username '" . htmlspecialchars($username) . "' sudah terdaftar. Silakan gunakan username lain.";
        } else {
            // Jika user unik, lanjutkan proses insert
            $password = md5($_POST['password']); 
            $role = $_POST['role'];
            $pm_id = ($role == 'Team Member') ? $_POST['project_manager_id'] : null;

            $stmt = $pdo->prepare("INSERT INTO users (username, password, role, project_manager_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $password, $role, $pm_id]);
            $success = true; 
        }
    }
    
    if (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $success = true; 
    }

    if ($success) {
        header("Location: manage_users.php");
        exit();
    }
}


$users_stmt = $pdo->prepare("SELECT u.*, pm.username as pm_username FROM users u LEFT JOIN users pm ON u.project_manager_id = pm.id WHERE u.role != 'Super Admin' ORDER BY u.role");
$users_stmt->execute();
$users = $users_stmt->fetchAll();


$pm_stmt = $pdo->prepare("SELECT id, username FROM users WHERE role = 'Project Manager'");
$pm_stmt->execute();
$project_managers = $pm_stmt->fetchAll();
?>



<?php if (!empty($error_message)): ?>
    <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 5px; margin-bottom: 20px;">
        <?php echo $error_message; ?>
    </div>
<?php endif; ?>

<div class="form-container">
    <h3>Tambah Pengguna Baru</h3>
    <form action="manage_users.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role" id="role-select" required onchange="togglePmSelect()">
            <option value="">Pilih Peran</option>
            <option value="Project Manager">Project Manager</option>
            <option value="Team Member">Team Member</option>
        </select>
        <div id="pm-select-div" style="display:none;">
            <select name="project_manager_id">
                <option value="">-- Pilih Project Manager --</option>
                <?php foreach ($project_managers as $pm): ?>
                    <option value="<?php echo $pm['id']; ?>"><?php echo htmlspecialchars($pm['username']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" name="add_user" style="color: #721c24;">Tambah Pengguna</button>
    </form>
</div>

<h3>Daftar Pengguna</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Peran</th>
            <th>Manajer Proyek</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['role']); ?></td>
            <td><?php echo htmlspecialchars($user['pm_username'] ?? 'N/A'); ?></td>
            <td>
                <form action="manage_users.php" method="POST" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <button type="submit" name="delete_user" class="btn-delete" onclick="return confirm('Anda yakin ingin menghapus pengguna ini?');">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>

function togglePmSelect() {
    var roleSelect = document.getElementById('role-select');
    var pmSelectDiv = document.getElementById('pm-select-div');
    if (roleSelect.value === 'Team Member') {
        pmSelectDiv.style.display = 'block';
    } else {
        pmSelectDiv.style.display = 'none';
    }
}
</script>

<?php include 'templates/footer.php'; ?>