<?php
include 'templates/header.php';

$error_message = '';
$editing_project = null;
$nama_proyek = '';
$deskripsi = '';
$tanggal_mulai = '';
$tanggal_selesai = '';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $success = false; // Flag untuk mengontrol redirect

    if (isset($_POST['delete_project'])) {
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ? AND manager_id = ?");
        $stmt->execute([$_POST['project_id'], $manager_id]);
        $success = true;
    } 
    elseif (isset($_POST['add_project']) || isset($_POST['update_project'])) {
        
        $tgl_mulai = $_POST['tanggal_mulai'];
        $tgl_selesai = $_POST['tanggal_selesai'];
        $nama_proyek = $_POST['nama_proyek'];
        $deskripsi = $_POST['deskripsi'];

        if ($tgl_selesai < $tgl_mulai) {
            $error_message = "Error: Tanggal Selesai tidak boleh lebih awal dari Tanggal Mulai.";
            
            $tanggal_mulai = $tgl_mulai;
            $tanggal_selesai = $tgl_selesai;

            if (isset($_POST['update_project'])) {
                $editing_project = ['id' => $_POST['project_id']];
            }
        } else {
            
            if (isset($_POST['add_project'])) {
                $stmt = $pdo->prepare("INSERT INTO projects (nama_proyek, deskripsi, tanggal_mulai, tanggal_selesai, manager_id) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$nama_proyek, $deskripsi, $tgl_mulai, $tgl_selesai, $manager_id]);
                $success = true;
            }

            if (isset($_POST['update_project'])) {
                $stmt = $pdo->prepare("UPDATE projects SET nama_proyek = ?, deskripsi = ?, tanggal_mulai = ?, tanggal_selesai = ? WHERE id = ? AND manager_id = ?");
                $stmt->execute([$nama_proyek, $deskripsi, $tgl_mulai, $tgl_selesai, $_POST['project_id'], $manager_id]);
                $success = true;
            }
        }
    }

    if ($success) {
        header("Location: manage_projects.php");
        exit();
    }
}

if (isset($_GET['edit'])) {
    $project_id_to_edit = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ? AND manager_id = ?");
    $stmt->execute([$project_id_to_edit, $manager_id]);
    $editing_project = $stmt->fetch();

    if ($editing_project) {
        $nama_proyek = $editing_project['nama_proyek'];
        $deskripsi = $editing_project['deskripsi'];
        $tanggal_mulai = $editing_project['tanggal_mulai'];
        $tanggal_selesai = $editing_project['tanggal_selesai'];
    }
}

$projects_stmt = $pdo->prepare("SELECT * FROM projects WHERE manager_id = ? ORDER BY tanggal_mulai DESC");
$projects_stmt->execute([$manager_id]);
$projects = $projects_stmt->fetchAll();
?>
<?php if (!empty($error_message)): ?>
    <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 5px; margin-bottom: 20px;">
        <?php echo $error_message; ?>
    </div>
<?php endif; ?>

<div class="form-container">
    <h3><?php echo $editing_project ? 'Edit Proyek' : 'Tambah Proyek Baru'; ?></h3>


<div class="form-container">
    <h3><?php echo $editing_project ? 'Edit Proyek' : 'Tambah Proyek Baru'; ?></h3>
    <form action="manage_projects.php" method="POST">
        <?php if ($editing_project): ?>
            <input type="hidden" name="project_id" value="<?php echo $editing_project['id']; ?>">
        <?php endif; ?>

        <div class="form-grid">
            <div class="form-group">
                <label for="nama_proyek">Nama Proyek</label>
                <input type="text" id="nama_proyek" name="nama_proyek" value="<?php echo htmlspecialchars($nama_proyek); ?>" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="3" required><?php echo htmlspecialchars($deskripsi); ?></textarea>
            </div>
            <div class="form-group">
                <label for="tanggal_mulai">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="<?php echo $tanggal_mulai; ?>" required>
            </div>
            <div class="form-group">
                <label for="tanggal_selesai">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="<?php echo $tanggal_selesai; ?>" required>
            </div>
        </div>
        
        <?php if ($editing_project): ?>
            <button type="submit" name="update_project">Update Proyek</button>
            <a href="manage_projects.php" style="display:inline-block; margin-top:10px; text-decoration:none; padding:10px; background-color:#6c757d; color:white; border-radius:5px;">Batal Edit</a>
        <?php else: ?>
            <button type="submit" name="add_project">Tambah Proyek</button>
        <?php endif; ?>
    </form>
</div>

<h3>Daftar Proyek Anda</h3>
<table>
    <thead>
        <tr>
            <th>Nama Proyek</th>
            <th>Deskripsi</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($projects)): ?>
            <tr><td colspan="5" style="text-align:center;">Anda belum memiliki proyek.</td></tr>
        <?php else: ?>
            <?php foreach ($projects as $project): ?>
            <tr>
                <td><?php echo htmlspecialchars($project['nama_proyek']); ?></td>
                <td><?php echo htmlspecialchars($project['deskripsi']); ?></td>
                <td><?php echo $project['tanggal_mulai']; ?></td>
                <td><?php echo $project['tanggal_selesai']; ?></td>
                <td>
                    <a href="manage_projects.php?edit=<?php echo $project['id']; ?>" class="btn-edit">Edit</a>
                    
                    <form action="manage_projects.php" method="POST" style="display:inline;" onsubmit="return confirm('Anda yakin ingin menghapus proyek ini?');">
                        <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
                        <button type="submit" name="delete_project" class="btn-delete">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'templates/footer.php'; ?>