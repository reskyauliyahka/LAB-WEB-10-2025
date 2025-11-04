<?php include '../config/style.php'; ?>

<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'superadmin') {
    header("Location: ../index.php");
    exit;
}
if (isset($_POST['add_user'])) {
    $username = trim($_POST['username']);
    $password = md5(trim($_POST['password']));
    $role = $_POST['role'];
    $pm_id = !empty($_POST['project_manager_id']) ? $_POST['project_manager_id'] : 'NULL';
    if ($role == 'member') {
        $sql = "INSERT INTO users (username, password, role, project_manager_id)
                VALUES ('$username', '$password', '$role', $pm_id)";
    } else {
        $sql = "INSERT INTO users (username, password, role)
                VALUES ('$username', '$password', '$role')";
    }
    $conn->query($sql);
}
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id");
}

$managers = $conn->query("SELECT * FROM users WHERE role='manager'");
$users = $conn->query("SELECT u.*, pm.username AS manager_name 
                       FROM users u 
                       LEFT JOIN users pm ON u.project_manager_id = pm.id
                       ORDER BY u.role");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Super Admin - Manage Users</title>
  <?php include '../config/style.php'; ?>
</head>
<body>

<div class="container my-4">
  <div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Super Admin - Manage Users</h2>
      <a href="../auth/logout.php" class="btn btn-outline-danger">Logout</a>
    </div>

    <h4>Tambah User Baru</h4>
    <form method="POST" class="row g-3">
      <div class="col-md-4"><input type="text" name="username" class="form-control" placeholder="Username" required></div>
      <div class="col-md-4"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
      <div class="col-md-4">
        <select name="role" id="role" class="form-select" required onchange="togglePM(this.value)">
          <option value="">-- Pilih Role --</option>
          <option value="manager">Project Manager</option>
          <option value="member">Team Member</option>
        </select>
      </div>
      <div class="col-md-6" id="pm_select" style="display:none;">
        <select name="project_manager_id" class="form-select">
          <option value="">-- Pilih Manager --</option>
          <?php while($m = $managers->fetch_assoc()): ?>
            <option value="<?= $m['id'] ?>"><?= $m['username'] ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-12">
        <button type="submit" name="add_user" class="btn btn-pink">Tambah User</button>
      </div>
    </form>
  </div>

  <div class="card mt-4 p-4">
    <h4>Daftar Semua User</h4>
    <table class="table table-striped mt-3">
      <thead><tr><th>ID</th><th>Username</th><th>Role</th><th>Manager</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php while($u = $users->fetch_assoc()): ?>
        <tr>
          <td><?= $u['id'] ?></td>
          <td><?= $u['username'] ?></td>
          <td><?= $u['role'] ?></td>
          <td><?= $u['manager_name'] ?? '-' ?></td>
          <td>
            <?php if ($u['role'] != 'superadmin'): ?>
              <a href="?delete=<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus user ini?')">Hapus</a>
            <?php else: ?>
              <em>-</em>
            <?php endif; ?>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function togglePM(role){
  document.getElementById('pm_select').style.display = (role === 'member') ? 'block' : 'none';
}
</script>
</body>
</html>
