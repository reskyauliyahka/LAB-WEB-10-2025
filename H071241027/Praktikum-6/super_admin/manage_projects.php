<?php
require_once '../config/database.php';
include 'templates/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_project'])) {
    $project_id = $_POST['project_id'];
    
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$project_id]);

    header("Location: manage_projects.php");
    exit();
}

$projects_stmt = $pdo->prepare(
    "SELECT p.*, u.username as manager_name 
     FROM projects p 
     JOIN users u ON p.manager_id = u.id 
     ORDER BY p.tanggal_mulai DESC"
);
$projects_stmt->execute();
$projects = $projects_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Daftar Seluruh Proyek</h3>
<p>Sebagai Super Admin, Anda dapat melihat dan menghapus proyek dari semua Project Manager.</p>

<table>
    <thead>
        <tr>
            <th>ID Proyek</th>
            <th>Nama Proyek</th>
            <th>Deskripsi</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>Manajer Proyek</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($projects)): ?>
            <tr>
                <td colspan="7" style="text-align:center;">Belum ada proyek yang dibuat.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($projects as $project): ?>
            <tr>
                <td><?php echo $project['id']; ?></td>
                <td><?php echo htmlspecialchars($project['nama_proyek']); ?></td>
                <td><?php echo htmlspecialchars($project['deskripsi']); ?></td>
                <td><?php echo $project['tanggal_mulai']; ?></td>
                <td><?php echo $project['tanggal_selesai']; ?></td>
                <td><?php echo htmlspecialchars($project['manager_name']); ?></td>
                <td>
                    <form action="manage_projects.php" method="POST" style="display:inline;">
                        <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
                        <button type="submit" name="delete_project" class="btn-delete" onclick="return confirm('Anda yakin ingin menghapus proyek ini? Ini akan menghapus semua tugas terkait juga.');">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php
include 'templates/footer.php';
?>