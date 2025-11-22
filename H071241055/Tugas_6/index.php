<?php
// Sertakan file config.php
include 'config.php';

// Cek jika pengguna sudah login, alihkan ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Manajemen Proyek</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
            Login Sistem Manajemen Proyek
        </h2>

        <?php
        // Tampilkan pesan error jika ada
        if (isset($_GET['error'])) {
            // Style error menggunakan Tailwind
            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4' role='alert'>";
            echo "<span class='block sm:inline'>" . htmlspecialchars($_GET['error']) . "</span>";
            echo "</div>";
        }
        ?>

        <form action="login_process.php" method="POST">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username:</label>
                <input type="text" id="username" name="username" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password:</label>
                <input type="password" id="password" name="password" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <button type="submit" 
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 font-medium">
                Login
            </button>
        </form>
    </div>

</body>
</html>