<?php
session_start();
include("config.php");

// Jika sudah login langsung masuk ke dashboard
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $query = $conn->prepare("SELECT * FROM admin WHERE username=? LIMIT 1");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // cek password pakai password_verify
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = $admin['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "❌ Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            height: 100vh;
            display: flex;
        }

        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #6a11cb, #2575fc); /* GANTI WARNA DI SINI */
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .left-panel h1 {
            font-size: 48px;
            font-weight: bold;
        }

        .right-panel {
            flex: 1;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.05);
        }

        .login-box {
            width: 100%;
            max-width: 360px;
        }

        h2 {
            margin-bottom: 10px;
            color: #333;
        }

        .login-box p {
            color: #666;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #6a11cb; /* WARNA TOMBOL */
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #5b0eb8;
        }

        .bottom-links {
            margin-top: 15px;
            font-size: 14px;
        }

        .bottom-links a {
            color: #6a11cb;
            text-decoration: none;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

    </style>
</head>
<body>
    <div class="left-panel">
        
        <h1>Hello, Welcome<br>Back!</h1>
    </div>

    <div class="right-panel">
        <div class="login-box">
            <h2>Login</h2>
            <p>Welcome back! Please login to your account.</p>
            <?php if(isset($error)): ?>
                <p class="error"><?= $error; ?></p>
            <?php endif; ?>
            <form method="POST" action="login.php">
                <input type="text" name="username" placeholder="Email atau Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <div class="bottom-links">
                <a href="reset.php">Lupa Password?</a> |
                <a href="register.php">Signup</a>
            </div>
        </div>
    </div>
</body>
</html>  