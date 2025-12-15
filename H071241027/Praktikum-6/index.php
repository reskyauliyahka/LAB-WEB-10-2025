<?php
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

if ($role == 'Super Admin') {
    header("Location: super_admin/index.php");
} elseif ($role == 'Project Manager') {
    header("Location: project_manager/index.php");
} elseif ($role == 'Team Member') {
    header("Location: team_member/index.php");
} else {
    session_destroy();
    header("Location: login.php");
}
exit();
?>