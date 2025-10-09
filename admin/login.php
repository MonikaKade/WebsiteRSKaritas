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
        * {margin:0; padding:0; box-sizing:border-box;}
        body { font-family:'Arial', sans-serif; height:100vh; display:flex; }
        .left-panel { flex:1; background: linear-gradient(135deg, #6a11cb, #2575fc); color:white; display:flex; flex-direction:column; justify-content:center; align-items:center; padding:40px; }
        .left-panel h1 { font-size:48px; font-weight:bold; text-align:center; }
        .right-panel { flex:1; background:#fff; display:flex; justify-content:center; align-items:center; padding:40px; box-shadow:-2px 0 10px rgba(0,0,0,0.05); }
        .login-box { width:100%; max-width:360px; }
        h2 { margin-bottom:10px; color:#333; }
        .login-box p { color:#666; margin-bottom:20px; }
        input[type="text"], input[type="password"] { width:100%; padding:12px; margin-bottom:15px; border:1px solid #ccc; border-radius:6px; }
        button { width:100%; padding:12px; background:#6a11cb; color:#fff; border:none; border-radius:6px; cursor:pointer; font-weight:bold; }
        button:hover { background:#5b0eb8; }
        .bottom-links { margin-top:15px; font-size:14px; text-align:center; }
        .bottom-links a { color:#6a11cb; text-decoration:none; }
        .error { color:red; margin-bottom:10px; padding:10px; background:#ffe6e6; border-radius:4px; border-left:4px solid red; }
    </style>
</head>
<body>
    <div class="left-panel">
        <h1>Hello,<br>Welcome Back!</h1>
    </div>
    <div class="right-panel">
        <div class="login-box">
            <h2>Login Admin</h2>
            <p>Masukkan akun Anda untuk masuk ke dashboard.</p>
            <?php if($error): ?><div class="error"><?= htmlspecialchars($error); ?></div><?php endif; ?>
            <form method="POST" action="">
                <input type="text" name="username_or_email" placeholder="Username atau Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <div class="bottom-links">
                <a href="lupapass.php">Lupa Password?</a> | <a href="register.php">Daftar</a>
            </div>
        </div>
    </div>
</body>
</html>
