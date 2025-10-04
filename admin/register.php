<?php
session_start();
include("config.php");

// Jika sudah login, redirect
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirm = trim($_POST["confirm"]);

    if ($password !== $confirm) {
        $error = "❌ Password dan Konfirmasi tidak sama!";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $query = $conn->prepare("SELECT * FROM admin WHERE username=? LIMIT 1");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $error = "❌ Username sudah digunakan!";
        } else {
            $insert = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
            $insert->bind_param("ss", $username, $hashed);

            if ($insert->execute()) {
                $success = "✅ Registrasi berhasil! Silakan <a href='login.php'>login</a>.";
            } else {
                $error = "❌ Gagal registrasi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Signup Admin</title>
    <style>
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            height: 100vh;
        }

        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #6a11cb, #2575fc); /* GANTI WARNA BRANDMU */
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 40px;
        }

        .left-panel h1 {
            font-size: 42px;
            font-weight: bold;
            text-align: center;
        }

        .right-panel {
            flex: 1;
            background: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.05);
        }

        .form-box {
            width: 100%;
            max-width: 380px;
        }

        h2 {
            margin-bottom: 10px;
            color: #333;
        }

        .form-box p {
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
            background: #6a11cb;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #5a0fb8;
        }

        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
        }

        .error {
            background-color: #ffdddd;
            color: #d8000c;
        }

        .success {
            background-color: #ddffdd;
            color: #4F8A10;
        }

        .bottom-links {
            margin-top: 10px;
            text-align: center;
        }

        .bottom-links a {
            text-decoration: none;
            color: #6a11cb;
        }
    </style>
</head>
<body>

    <div class="left-panel">
        <h1>Daftar<br>Admin Baru</h1>
    </div>

    <div class="right-panel">
        <div class="form-box">
            <h2>Signup</h2>
            <p>Silakan buat akun admin baru.</p>

            <?php if (isset($error)): ?>
                <div class="message error"><?= $error; ?></div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="message success"><?= $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="text" name="username" placeholder="Username atau Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm" placeholder="Konfirmasi Password" required>
                <button type="submit">Daftar</button>
            </form>

            <div class="bottom-links">
                Sudah punya akun? <a href="login.php">Login</a>
            </div>
        </div>
    </div>

</body>
</html>
