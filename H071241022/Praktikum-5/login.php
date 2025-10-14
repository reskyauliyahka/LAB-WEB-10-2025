<?php
session_start();

if (isset($_SESSION['user'])){
    header("Location: dashboard.php");
    exit;
}

$error_message = '';
if (isset($_SESSION['error'])){
    $error_message = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #ffe6eb;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 320px;
            text-align: center;
        }
        h2 {
            color: #ff4d6d;
            margin-bottom: 20px;
        }

        label{
            display: block;
            text-align: left;
            font-weight: 500;
            margin-top: 10px;
            color: #444;
        }

        input{
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            transition: border 0.2s ease;

        }

        input:focus{
            border-color: #ff4d6d;
        }

        .btn{
            background: #ff4d64;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 15px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .btn:hover{
            background: #ff758f;
        }

        .error{
            background: #ffe0e0;
            border-left: 4px solid #ff4d6d;
            padding: 10px;
            color: #a33;
            margin-bottom: 15px;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        <?php if ($error_message):?>
            <div class='error'>
                <?php echo htmlspecialchars($error_message);
                ?>
            </div>

            <?php endif; ?>

            <form action="proses_login.php" method="POST">
                <label>Username</label>
                <input type="text" name="username" required>

                <label>Password</label>
                <input type="password" name="password" required>

                <button type="submit" class="btn">Login</button>
            </form>
    </div>
</body>
</html>
