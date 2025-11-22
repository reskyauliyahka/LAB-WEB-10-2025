<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'member') {
    header("Location: ../index.php");
    exit;
}
$member_id = $_SESSION['user']['id'];

// Update status tugas
if (isset($_POST['update_status'])) {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];
    $conn->query("UPDATE tasks SET status='$status' WHERE id=$task_id AND assigned_to=$member_id");
}

// Ambil semua tugas member ini
$tasks = $conn->query("SELECT t.*, p.nama_proyek 
                       FROM tasks t JOIN projects p ON t.project_id=p.id
                       WHERE assigned_to=$member_id");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Member - Tugas Saya</title>
  <?php include '../config/style.php'; ?>
</head>
<body>

<div class="container my-4">
  <div class="card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Tugas Saya</h2>
      <a href="../auth/logout.php" class="btn btn-outline-danger">Logout</a>
    </div>

    <p class="text-muted">Lihat dan update status tugas yang ditugaskan kepadamu oleh Project Manager.</p>

    <table class="table table-striped mt-3">
      <thead><tr><th>Proyek</th><th>Nama Tugas</th><th>Deskripsi</th><th>Status</th><th>Ubah Status</th></tr></thead>
      <tbody>
        <?php while($t = $tasks->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($t['nama_proyek']) ?></td>
          <td><?= htmlspecialchars($t['nama_tugas']) ?></td>
          <td><?= htmlspecialchars($t['deskripsi']) ?></td>
          <td><?= ucfirst($t['status']) ?></td>
          <td>
            <form method="POST" class="d-flex align-items-center gap-2">
              <input type="hidden" name="task_id" value="<?= $t['id'] ?>">
              <select name="status" class="form-select form-select-sm">
                <option value="belum" <?= $t['status']=='belum'?'selected':'' ?>>Belum</option>
                <option value="proses" <?= $t['status']=='proses'?'selected':'' ?>>Proses</option>
                <option value="selesai" <?= $t['status']=='selesai'?'selected':'' ?>>Selesai</option>
              </select>
              <button class="btn btn-pink btn-sm" name="update_status">✔</button>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
