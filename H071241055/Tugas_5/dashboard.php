<?php
session_start();
if (!isset($_SESSION['user_data'])) {
    header('Location:login.php');
    exit;
}

$loggedInUser = $_SESSION['user_data'];

// Asumsikan admin adalah user dengan username 'adminxxx'
$isAdmin = ($loggedInUser['username'] === 'adminxxx');

if ($isAdmin) {
    require 'data.php'; // Muat semua data pengguna hanya jika admin
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 p-4 sm:p-8">
    <section class="max-w-4xl mx-auto bg-white p-6 sm:p-8 rounded-xl shadow-md">
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Selamat Datang, <?php echo htmlspecialchars($loggedInUser['name']); ?>!</h1>
        <a href="logout.php" class="text-sm text-red-500 hover:underline">Logout</a>
        <hr class="my-6">

        <?php if ($isAdmin): ?>
            <h2 class="text-xl font-semibold text-slate-700">Data Semua Pengguna</h2>
            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-500">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-100">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama</th>
                            <th scope="col" class="px-6 py-3">Username</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr class="bg-white border-b hover:bg-slate-50">
                                <td class="px-6 py-4 font-medium text-slate-900"><?php echo htmlspecialchars($user['name']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($user['username']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($user['email']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <h2 class="text-xl font-semibold text-slate-700">Data Anda</h2>
            <div class="mt-4 border border-slate-200 rounded-lg overflow-hidden">
                <table class="w-full text-sm">
                    <tbody>
                        <tr class="border-b border-slate-200">
                            <td class="px-6 py-3 bg-slate-50 font-medium text-slate-600 w-1/3">Nama</td>
                            <td class="px-6 py-3 text-slate-800"><?php echo htmlspecialchars($loggedInUser['name']); ?></td>
                        </tr>
                        <tr class="border-b border-slate-200">
                            <td class="px-6 py-3 bg-slate-50 font-medium text-slate-600">Username</td>
                            <td class="px-6 py-3 text-slate-800"><?php echo htmlspecialchars($loggedInUser['username']); ?></td>
                        </tr>
                        <tr class="border-b border-slate-200">
                            <td class="px-6 py-3 bg-slate-50 font-medium text-slate-600">Email</td>
                            <td class="px-6 py-3 text-slate-800"><?php echo htmlspecialchars($loggedInUser['email']); ?></td>
                        </tr>
                        <tr class="border-b border-slate-200">
                            <td class="px-6 py-3 bg-slate-50 font-medium text-slate-600">Gender</td>
                            <td class="px-6 py-3 text-slate-800"><?php echo htmlspecialchars($loggedInUser['gender'] ?? 'N/A'); ?></td>
                        </tr>
                        <tr class="border-b border-slate-200">
                            <td class="px-6 py-3 bg-slate-50 font-medium text-slate-600">Fakultas</td>
                            <td class="px-6 py-3 text-slate-800"><?php echo htmlspecialchars($loggedInUser['faculty'] ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td class="px-6 py-3 bg-slate-50 font-medium text-slate-600">Angkatan</td>
                            <td class="px-6 py-3 text-slate-800"><?php echo htmlspecialchars($loggedInUser['batch'] ?? 'N/A'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</body>
</html>