<?php
include 'templates/header.php';


$stmt_proyek = $pdo->prepare("SELECT COUNT(*) FROM projects WHERE manager_id = ?");
$stmt_proyek->execute([$manager_id]);
$jumlah_proyek = $stmt_proyek->fetchColumn();

$stmt_tugas = $pdo->prepare(
    "SELECT COUNT(t.id) 
     FROM tasks t
     JOIN projects p ON t.project_id = p.id
     WHERE p.manager_id = ?"
);
$stmt_tugas->execute([$manager_id]);
$jumlah_tugas = $stmt_tugas->fetchColumn();

$stmt_tim = $pdo->prepare("SELECT COUNT(*) FROM users WHERE project_manager_id = ?");
$stmt_tim->execute([$manager_id]);
$jumlah_tim = $stmt_tim->fetchColumn();

?>

<h2>Selamat Datang, Manajer Proyek <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
<p>Ini adalah dashboard ringkas Anda.</p>

<h3>Ringkasan Anda:</h3>
<ul>
    <li>Jumlah Proyek yang Anda Kelola: <strong><?php echo $jumlah_proyek; ?></strong></li>
    <li>Jumlah Total Tugas dalam Proyek Anda: <strong><?php echo $jumlah_tugas; ?></strong></li>
    <li>Jumlah Anggota Tim Anda: <strong><?php echo $jumlah_tim; ?></strong></li>
</ul>

<h3>Proyek Terbaru Anda:</h3>
<table>
    <thead>
        <tr>
            <th>Nama Proyek</th>
            <th>Tanggal Selesai</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $latest_projects_stmt = $pdo->prepare("SELECT * FROM projects WHERE manager_id = ? ORDER BY tanggal_mulai DESC LIMIT 5");
        $latest_projects_stmt->execute([$manager_id]);
        $latest_projects = $latest_projects_stmt->fetchAll();

        if (empty($latest_projects)) {
            echo '<tr><td colspan="3" style="text-align:center;">Anda belum memiliki proyek.</td></tr>';
        } else {
            foreach ($latest_projects as $project) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($project['nama_proyek']) . '</td>';
                echo '<td>' . $project['tanggal_selesai'] . '</td>';
                echo '<td><a href="manage_tasks.php?project_id=' . $project['id'] . '">Lihat Tugas</a></td>';
                echo '</tr>';
            }
        }
        ?>
    </tbody>
</table>


<?php
include 'templates/footer.php';
?>