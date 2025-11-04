<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'manager') {
    header("Location: ../index.php");
    exit;
}
$manager_id = $_SESSION['user']['id'];

// Tambah proyek
if (isset($_POST['add_project'])) {
    $nama = $_POST['nama_proyek'];
    $desc = $_POST['deskripsi'];
    $mulai = $_POST['tanggal_mulai'];
    $selesai = $_POST['tanggal_selesai'];
    $conn->query("INSERT INTO projects (nama_proyek, deskripsi, tanggal_mulai, tanggal_selesai, manager_id)
                  VALUES ('$nama', '$desc', '$mulai', '$selesai', $manager_id)");
}

// Hapus proyek
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM projects WHERE id=$id AND manager_id=$manager_id");
}

// Ambil proyek milik manager
$projects = $conn->query("SELECT * FROM projects WHERE manager_id=$manager_id");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manager - Projects</title>
  <?php include '../config/style.php'; ?>
</head>
<body>

<div class="container my-4">
  <div class="card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Project Manager Dashboard</h2>
      <a href="../auth/logout.php" class="btn btn-outline-danger">Logout</a>
    </div>

    <h4>Buat Proyek Baru</h4>
    <form method="POST" class="row g-3">
      <div class="col-md-6">
        <input type="text" name="nama_proyek" class="form-control" placeholder="Nama proyek" required>
      </div>
      <div class="col-md-6">
        <input type="date" name="tanggal_mulai" class="form-control" required>
      </div>
      <div class="col-md-6">
        <input type="date" name="tanggal_selesai" class="form-control" required>
      </div>
      <div class="col-md-12">
        <textarea name="deskripsi" class="form-control" placeholder="Deskripsi proyek"></textarea>
      </div>
      <div class="col-12">
        <button name="add_project" class="btn btn-pink">Tambah Proyek</button>
      </div>
    </form>
  </div>

  <div class="card p-4">
    <h4>Proyek Saya</h4>
    <table class="table table-striped mt-3">
      <thead><tr><th>ID</th><th>Nama</th><th>Deskripsi</th><th>Periode</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php while($p = $projects->fetch_assoc()): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td><?= htmlspecialchars($p['nama_proyek']) ?></td>
          <td><?= htmlspecialchars($p['deskripsi']) ?></td>
          <td><?= $p['tanggal_mulai'] ?> - <?= $p['tanggal_selesai'] ?></td>
          <td>
            <a href="tasks.php?project_id=<?= $p['id'] ?>" class="btn btn-sm btn-pink">Kelola Tugas</a>
            <a href="?delete=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus proyek ini?')">Hapus</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
