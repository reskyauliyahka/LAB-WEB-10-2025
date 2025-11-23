<?php
require_once '../config/database.php';
include 'templates/header.php';
?>

<h2>Selamat Datang, <?php echo $_SESSION['username']; ?>!</h2>
<p>Anda login sebagai Super Admin. Dari sini Anda dapat mengelola seluruh pengguna dan proyek dalam sistem.</p>

<?php
    $stmt_pm = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'Project Manager'");
    $stmt_pm->execute();
    $count_pm = $stmt_pm->fetchColumn();

    $stmt_tm = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'Team Member'");
    $stmt_tm->execute();
    $count_tm = $stmt_tm->fetchColumn();

    $stmt_proj = $pdo->prepare("SELECT COUNT(*) FROM projects");
    $stmt_proj->execute();
    $count_proj = $stmt_proj->fetchColumn();
?>
<h3>Ringkasan Sistem:</h3>
<ul>
    <li>Jumlah Project Manager: <?php echo $count_pm; ?></li>
    <li>Jumlah Team Member: <?php echo $count_tm; ?></li>
    <li>Jumlah Total Proyek: <?php echo $count_proj; ?></li>
</ul>


<?php
include 'templates/footer.php';
?>