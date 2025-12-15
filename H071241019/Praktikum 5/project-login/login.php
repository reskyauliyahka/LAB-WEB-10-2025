<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}

$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #5B86E5, #36D1DC);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .box {
            background: white;
            padding: 25px;
            width: 350px;
            border-radius: 12px;
            box-shadow: 0 0 18px rgba(0,0,0,0.2);
            animation: fadeIn 0.6s;
        }

        h2 { text-align: center; margin-bottom: 20px; color:#333; }

        input {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #aaa;
            margin-top: 10px;
            transition: 0.3s;
        }

        input:focus {
            border-color:#5B86E5;
            box-shadow: 0 0 6px rgba(91,134,229,0.4);
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            background:#5B86E5;
            border: none;
            color:white;
            font-size: 16px;
            border-radius: 6px;
            cursor:pointer;
            transition:0.3s;
        }

        button:hover { background:#3D6CD2; }

        .error {
            background: #ffdddd;
            color: red;
            padding: 8px;
            border-left: 4px solid red;
            margin-bottom: 15px;
            border-radius:6px;
            font-size:14px;
        }

        @keyframes fadeIn {
            from { opacity:0; transform: scale(0.9); }
            to { opacity:1; transform: scale(1); }
        }
    </style>
</head>
<body>

<div class="box">
    <h2>LOGIN</h2>

    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" action="proses_login.php">
        <input type="text" name="username" placeholder="Masukkan username" required>
        <input type="password" name="password" placeholder="Masukkan password" required>
        <button type="submit">Masuk</button>
    </form>
</div>

</body>
</html>
