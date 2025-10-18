<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}

$error_message = '';
if (isset($_SESSION['login_error'])) {
    $error_message = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem</title>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url(assets/unnamed.jpg);
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            overflow: hidden; 
        }
        .login-container {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 30px;
            width: 300px;
            text-align: center;
        }
        h2 {
            margin-bottom: 25px;
            color: #f6f6f6ff;
            font-size: 1.5em;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #fff9f9ff;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            background: rgba(62, 111, 189, 0.66);
            border-radius: 16px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(62, 111, 189, 0.3);
            color: #ffffff; /* Tambahkan warna teks agar terlihat */
        }
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-text-fill-color: #ffffff !important;
            -webkit-box-shadow: 0 0 0 30px rgba(0, 0, 0, 0.2) inset !important;
            background-clip: content-box !important;
        }
        button {
            width: 100%;
            padding: 10px;
            color: white;
            cursor: pointer;
            font-size: 1em;
            margin-top: 10px;
            background: rgba(45, 112, 218, 0.77);
            border-radius: 16px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(45, 112, 218, 0.3);
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: #dc3545;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 4px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>

<div class="login-container" data-aos="zoom-in" data-aos-duration="800">
    <h2>Silakan Login</h2>

    <?php if ($error_message): ?>
        <p class="error" data-aos="zoom-in-up" data-aos-delay="200"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form action="proses_login.php" method="POST">
        <div class="form-group" data-aos="fade-right" data-aos-delay="300">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group" data-aos="fade-left" data-aos-delay="500">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" data-aos="fade-up" data-aos-delay="700">Login</button>
    </form>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
</script>

</body>
</html>