<?php
// dashboard_ringkas.php - File ini di-include di dashboard utama
// Membutuhkan koneksi ($conn) dan user_id ($user_id atau $manager_id) dari file pemanggil.

// Tentukan Role dan ID yang relevan
$current_role = $_SESSION['role'];
$current_id = $_SESSION['user_id'];

$stats = [
    'total_users' => 0,
    'total_projects' => 0,
    'projects_done' => 0,
    'tasks_total' => 0,
    'tasks_done' => 0,
];

if ($current_role === 'Super Admin') {
    // STATS ADMIN: Total Pengguna dan Proyek Global
    $result_users = $conn->query("SELECT COUNT(id) AS total FROM users WHERE role IN ('Project Manager', 'Team Member')");
    $stats['total_users'] = $result_users->fetch_assoc()['total'] ?? 0;

    $result_pro = $conn->query("SELECT COUNT(id) AS total FROM projects");
    $stats['total_projects'] = $result_pro->fetch_assoc()['total'] ?? 0;
    
    $result_done = $conn->query("SELECT COUNT(t.id) AS done FROM tasks t JOIN projects p ON t.project_id = p.id WHERE t.status = 'selesai'");
    $stats['tasks_done'] = $result_done->fetch_assoc()['done'] ?? 0;
    
    $result_total_tasks = $conn->query("SELECT COUNT(id) AS total FROM tasks");
    $stats['tasks_total'] = $result_total_tasks->fetch_assoc()['total'] ?? 0;
    
    $stats['tasks_percentage'] = $stats['tasks_total'] > 0 ? round(($stats['tasks_done'] / $stats['tasks_total']) * 100) : 0;

} elseif ($current_role === 'Project Manager') {
    // STATS PM: Proyek & Tugas Milik Sendiri
    $result_pro = $conn->prepare("SELECT COUNT(id) AS total FROM projects WHERE manager_id = ?");
    $result_pro->bind_param("i", $current_id);
    $result_pro->execute();
    $stats['total_projects'] = $result_pro->get_result()->fetch_assoc()['total'] ?? 0;
    $result_pro->close();

    $result_tasks = $conn->prepare("
        SELECT 
            SUM(CASE WHEN t.status = 'selesai' THEN 1 ELSE 0 END) AS done,
            COUNT(t.id) AS total
        FROM tasks t
        JOIN projects p ON t.project_id = p.id
        WHERE p.manager_id = ?
    ");
    $result_tasks->bind_param("i", $current_id);
    $result_tasks->execute();
    $task_data = $result_tasks->get_result()->fetch_assoc();
    $stats['tasks_done'] = $task_data['done'] ?? 0;
    $stats['tasks_total'] = $task_data['total'] ?? 0;
    $result_tasks->close();
    
    $stats['tasks_percentage'] = $stats['tasks_total'] > 0 ? round(($stats['tasks_done'] / $stats['tasks_total']) * 100) : 0;
    
} elseif ($current_role === 'Team Member') {
    // STATS TM: Tugas yang Ditugaskan kepadanya
    $result_tasks = $conn->prepare("
        SELECT 
            SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) AS done,
            COUNT(id) AS total
        FROM tasks
        WHERE assigned_to = ?
    ");
    $result_tasks->bind_param("i", $current_id);
    $result_tasks->execute();
    $task_data = $result_tasks->get_result()->fetch_assoc();
    $stats['tasks_done'] = $task_data['done'] ?? 0;
    $stats['tasks_total'] = $task_data['total'] ?? 0;
    $result_tasks->close();

    $stats['tasks_percentage'] = $stats['tasks_total'] > 0 ? round(($stats['tasks_done'] / $stats['tasks_total']) * 100) : 0;
}
?>

<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card bg-primary text-white card-shadow-hover">
            <div class="card-body">
                <h5 class="card-title"><?= $current_role === 'Super Admin' ? 'Total Pengguna Aktif' : 'Proyek Dikelola' ?></h5>
                <p class="card-text fs-2 fw-bold"><?= $current_role === 'Super Admin' ? $stats['total_users'] : $stats['total_projects'] ?></p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card bg-info text-white card-shadow-hover">
            <div class="card-body">
                <h5 class="card-title">Total Tugas <?= $current_role === 'Team Member' ? 'Ditugaskan' : 'Proyek' ?></h5>
                <p class="card-text fs-2 fw-bold"><?= $stats['tasks_total'] ?></p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card bg-success text-white card-shadow-hover">
            <div class="card-body">
                <h5 class="card-title">Selesai / Progress (%)</h5>
                <p class="card-text fs-2 fw-bold"><?= $stats['tasks_percentage'] ?>%</p>
                <small><?= $stats['tasks_done'] ?> dari <?= $stats['tasks_total'] ?> tugas selesai</small>
            </div>
        </div>
    </div>
</div>