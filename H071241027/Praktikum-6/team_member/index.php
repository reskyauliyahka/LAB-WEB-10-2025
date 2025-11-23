<?php
include 'templates/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $task_id_to_update = $_POST['task_id'];
    $new_status = $_POST['status'];
    
    
    
    $stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ? AND assigned_to = ?");
    $stmt->execute([$new_status, $task_id_to_update, $member_id]);
    
    header("Location: index.php");
    exit();
}



$tasks_stmt = $pdo->prepare(
    "SELECT 
        t.id, t.nama_tugas, t.deskripsi, t.status,
        p.nama_proyek 
     FROM tasks t
     JOIN projects p ON t.project_id = p.id
     WHERE t.assigned_to = ?
     ORDER BY p.nama_proyek, t.id DESC"
);
$tasks_stmt->execute([$member_id]);
$tasks = $tasks_stmt->fetchAll();

?>

<h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
<p>Berikut adalah daftar semua tugas yang ditugaskan kepada Anda.</p>

<div class="tasks-list">
    <?php if (empty($tasks)): ?>
        <p>Anda belum memiliki tugas saat ini. Kerja bagus!</p>
    <?php else: ?>
        <?php foreach ($tasks as $task): ?>
            <div class="task-card">
                <div class="project-title">
                    <strong>Proyek:</strong> <?php echo htmlspecialchars($task['nama_proyek']); ?>
                </div>
                <h3><?php echo htmlspecialchars($task['nama_tugas']); ?></h3>
                <p><?php echo htmlspecialchars($task['deskripsi']); ?></p>
                
                <form action="index.php" method="POST">
                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                    
                    <label for="status-<?php echo $task['id']; ?>"><strong>Status Saat Ini:</strong></label>
                    <select name="status" id="status-<?php echo $task['id']; ?>">
                        <option value="belum" <?php echo ($task['status'] == 'belum') ? 'selected' : ''; ?>>Belum Dikerjakan</option>
                        <option value="proses" <?php echo ($task['status'] == 'proses') ? 'selected' : ''; ?>>Sedang Dikerjakan</option>
                        <option value="selesai" <?php echo ($task['status'] == 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                    </select>
                    
                    <button type="submit" name="update_status">Update Status</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>


<?php
include 'templates/footer.php';
?>