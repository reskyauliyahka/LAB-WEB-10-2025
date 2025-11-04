<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'manager') {
    header("Location: ../index.php");
    exit;
}

$manager_id = $_SESSION['user']['id'];
$project_id = $_GET['project_id'] ?? 0;

// Validasi proyek milik manager
$project = $conn->query("SELECT * FROM projects WHERE id=$project_id AND manager_id=$manager_id")->fetch_assoc();
if (!$project) die("<h3 style='color:#f78fb3;'>Proyek tidak ditemukan atau bukan milik Anda</h3>");

// Tambah tugas
if (isset($_POST['add_task'])) {
    $nama = $_POST['nama_tugas'];
    $desc = $_POST['deskripsi'];
    $assigned = $_POST['assigned_to'];
    $conn->query("INSERT INTO tasks (nama_tugas, deskripsi, project_id, assigned_to)
                  VALUES ('$nama', '$desc', $project_id, $assigned)");
}

// Data member untuk dropdown
$members = $conn->query("SELECT * FROM users WHERE project_manager_id=$manager_id");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Kelola Tugas</title>
  <?php include '../config/style.php'; ?>
</head>
<body>

<div class="container my-4">
  <div class="card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Tambah Tugas untuk: <?= htmlspecialchars($project['nama_proyek']) ?></h2>
      <div>
        <a href="projects.php" class="btn btn-outline-secondary">Kembali</a>
        <a href="../auth/logout.php" class="btn btn-outline-danger">Logout</a>
      </div>
    </div>

    <form method="POST" class="row g-3">
      <div class="col-md-6">
        <input type="text" name="nama_tugas" class="form-control" placeholder="Nama tugas" required>
      </div>
      <div class="col-md-6">
        <select name="assigned_to" class="form-select" required>
          <option value="">-- Pilih Member --</option>
          <?php while($m = $members->fetch_assoc()): ?>
            <option value="<?= $m['id'] ?>"><?= $m['username'] ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-12">
        <textarea name="deskripsi" class="form-control" placeholder="Deskripsi tugas"></textarea>
      </div>
      <div class="col-12">
        <button name="add_task" class="btn btn-pink">Tambah Tugas</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
