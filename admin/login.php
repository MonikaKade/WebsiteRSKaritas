<?php
session_start();
include_once("config.php");

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = trim($_POST["username_or_email"] ?? '');
    $password = trim($_POST["password"] ?? '');

    if (!empty($username_or_email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, nama, username, email, password FROM admin WHERE username=? OR email=? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("ss", $username_or_email, $username_or_email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['admin'] = $user['nama'];
                    $_SESSION['admin_username'] = $user['username'];
                    $_SESSION['last_activity'] = time();
                    $stmt->close();
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error = "❌ Password salah!";
                }
            } else {
                $error = "❌ Username atau email tidak ditemukan!";
            }
            $stmt->close();
        } else {
            $error = "❌ Error database: " . $conn->error;
        }
    } else {
        $error = "❌ Semua field harus diisi!";
    }
}

if (isset($conn)) $conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin RS Karitas</title>
    <style>
        :root {
            --primary: #0a74da;
            --primary-light: #3ba7ff;
            --primary-dark: #064a8c;
            --text: #333;
            --light-text: #777;
            --bg: #f8fbff;
            --white: #fff;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --radius: 14px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            background: var(--bg);
        }

        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            color: var(--white);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            text-align: center;
        }

        .left-panel h1 {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 10px;
        }

        .left-panel p {
            font-size: 1rem;
            color: rgba(255,255,255,0.8);
        }

        .right-panel {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .login-box {
            width: 100%;
            max-width: 380px;
            background: var(--white);
            padding: 40px 35px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        h2 {
            color: var(--primary-dark);
            margin-bottom: 10px;
            text-align: center;
            font-weight: 700;
        }

        .login-box p {
            text-align: center;
            color: var(--light-text);
            margin-bottom: 25px;
            font-size: 0.95rem;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: var(--radius);
            font-size: 0.95rem;
            transition: 0.3s;
        }

        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(10,116,218,0.2);
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: background 0.3s ease, transform 0.2s;
        }

        button:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .bottom-links {
            margin-top: 18px;
            font-size: 0.9rem;
            text-align: center;
        }

        .bottom-links a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .bottom-links a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            padding: 12px;
            background: #ffe6e6;
            border-radius: var(--radius);
            border-left: 4px solid red;
            font-size: 0.9rem;
        }

        @media (max-width: 900px) {
            body {
                flex-direction: column;
            }
            .left-panel {
                flex: none;
                height: 200px;
                border-bottom-left-radius: 40px;
                border-bottom-right-radius: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="left-panel">
        <h1>Selamat Datang<br>Admin RS Karitas</h1>
        <p>Kelola data dan pantau aktivitas website dengan mudah.</p>
    </div>

    <div class="right-panel">
        <div class="login-box">
            <h2>Login Admin</h2>
            <p>Masukkan akun Anda untuk mengakses dashboard.</p>

            <?php if($error): ?>
                <div class="error"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="text" name="username_or_email" placeholder="Username atau Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Masuk</button>
            </form>

            <div class="bottom-links">
                <a href="lupapass.php">Lupa Password?</a> • 
                <a href="register.php">Daftar Akun</a>
            </div>
        </div>
    </div>
</body>
</html>
