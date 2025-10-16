    <?php
        session_start();

        // Syarat: Jika sudah ada session, langsung arahkan ke dashboard
        if (isset($_SESSION['user_data'])) {
            header('Location: dashboard.php');
            exit;
        }

        $error_message = '';
        // Mengecek pesan error dari proses_login.php via URL
        if (isset($_GET['error'])) {
            $error_message = 'Username atau password salah!';
        }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tugas 5 - Login</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="flex w-full h-screen justify-center items-center bg-gray-100">
        <section id="login-form" class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h1 class="font-bold text-2xl text-center mb-4">Silakan Login</h1>
            
            <?php
            if (!empty($error_message)) {
                echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">' . $error_message . '</div>';
            }
            ?>

            <form action="proses_login.php" class="flex flex-col space-y-4" method="post">
                <div>
                    <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
                    <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" name="username" id="username" required>
                </div>
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                    <input type="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" name="password" id="password" required>
                </div>
                <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Login</button>
            </form>
        </section>
    </body>
    </html>