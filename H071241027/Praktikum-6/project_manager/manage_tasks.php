<?php

include 'templates/header.php';


if (!isset($_GET['project_id']) || !is_numeric($_GET['project_id'])) {
    echo "<h1>Error: Project ID tidak valid.</h1>";
    include 'templates/footer.php';
    exit();
}
$project_id = $_GET['project_id'];

$project_stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ? AND manager_id = ?");
$project_stmt->execute([$project_id, $manager_id]);
$project = $project_stmt->fetch();

if (!$project) {
    echo "<h1>Error: Proyek tidak ditemukan atau Anda tidak memiliki akses.</h1>";
    include 'templates/footer.php';
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_task'])) {
        $stmt = $pdo->prepare("INSERT INTO tasks (nama_tugas, deskripsi, project_id, assigned_to) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_POST['nama_tugas'],
            $_POST['deskripsi'],
            $project_id,
            $_POST['assigned_to']
        ]);
    }

    if (isset($_POST['delete_task'])) {
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->execute([$_POST['task_id']]);
    }
    
    header("Location: manage_tasks.php?project_id=" . $project_id);
    exit();
}



$team_members_stmt = $pdo->prepare("SELECT id, username FROM users WHERE role = 'Team Member' AND project_manager_id = ?");
$team_members_stmt->execute([$manager_id]);
$team_members = $team_members_stmt->fetchAll();

$tasks_stmt = $pdo->prepare(
    "SELECT t.*, u.username AS assigned_user 
     FROM tasks t 
     LEFT JOIN users u ON t.assigned_to = u.id 
     WHERE t.project_id = ?"
);
$tasks_stmt->execute([$project_id]);
$tasks = $tasks_stmt->fetchAll();
?>

<h2>Kelola Tugas untuk Proyek: <?php echo htmlspecialchars($project['nama_proyek']); ?></h2>
<a href="manage_projects.php">&larr; Kembali ke Daftar Proyek</a>

<div class="form-container" style="margin-top: 20px;">
    <h3>Tambah Tugas Baru</h3>
    <form action="manage_tasks.php?project_id=<?php echo $project_id; ?>" method="POST">
        <div class="form-group">
            <label for="nama_tugas">Nama Tugas</label>
            <input type="text" name="nama_tugas" required>
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi Tugas</label>
            <textarea name="deskripsi" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="assigned_to">Tugaskan kepada</label>
            <select name="assigned_to" required>
                <option value="">-- Pilih Anggota Tim --</option>
                <?php foreach ($team_members as $member): ?>
                    <option value="<?php echo $member['id']; ?>">
                        <?php echo htmlspecialchars($member['username']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" name="add_task">Tambah Tugas</button>
    </form>
</div>

<h3>Daftar Tugas</h3>
<table>
    <thead>
        <tr>
            <th>Nama Tugas</th>
            <th>Deskripsi</th>
            <th>Ditugaskan Kepada</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($tasks)): ?>
            <tr><td colspan="5" style="text-align:center;">Belum ada tugas untuk proyek ini.</td></tr>
        <?php else: ?>
            <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?php echo htmlspecialchars($task['nama_tugas']); ?></td>
                <td><?php echo htmlspecialchars($task['deskripsi']); ?></td>
                <td><?php echo htmlspecialchars($task['assigned_user'] ?? 'N/A'); ?></td>
                <td><?php echo ucfirst(htmlspecialchars($task['status'])); ?></td>
                <td>
                    <form action="manage_tasks.php?project_id=<?php echo $project_id; ?>" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus tugas ini?');">
                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        <button type="submit" name="delete_task" class="btn-delete">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'templates/footer.php'; ?>