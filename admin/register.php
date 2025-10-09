<?php
session_start();
include_once("config.php");

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $password = trim($_POST["password"] ?? '');
    $confirm = trim($_POST["confirm"] ?? '');

    if (empty($username) || empty($password) || empty($confirm)) {
        $error = "❌ Semua field wajib diisi!";
    } elseif (strlen($username) < 3) {
        $error = "❌ Username minimal 3 karakter!";
    } elseif (strlen($password) < 6) {
        $error = "❌ Password minimal 6 karakter!";
    } elseif ($password !== $confirm) {
        $error = "❌ Password dan konfirmasi tidak sama!";
    } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "❌ Format email tidak valid!";
    } else {
        $check_stmt = $conn->prepare("SELECT id FROM admin WHERE username=? OR email=? LIMIT 1");
        if ($check_stmt) {
            $check_stmt->bind_param("ss", $username, $email);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                $error = "❌ Username atau email sudah digunakan!";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $nama = ucfirst($username);
                $nohp = '';

                $insert_stmt = $conn->prepare("INSERT INTO admin (username, nama, email, nohp, password) VALUES (?, ?, ?, ?, ?)");
                if ($insert_stmt) {
                    $insert_stmt->bind_param("sssss", $username, $nama, $email, $nohp, $hashed);
                    if ($insert_stmt->execute()) {
                        $success = "✅ Registrasi berhasil! Silakan <a href='login.php'>login</a> dengan akun baru.";
                    } else {
                        $error = "❌ Gagal registrasi: " . $insert_stmt->error;
                    }
                    $insert_stmt->close();
                } else {
                    $error = "❌ Error database: " . $conn->error;
                }
            }
            $check_stmt->close();
        } else {
            $error = "❌ Error database: " . $conn->error;
        }
    }
}

if (isset($conn)) $conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Admin RS Karitas</title>
    <style>
        * { box-sizing: border-box; padding: 0; margin: 0; }
        body { font-family: 'Arial', sans-serif; display: flex; height: 100vh; }
        .left-panel { flex: 1; background: linear-gradient(135deg, #6a11cb, #2575fc); display: flex; justify-content: center; align-items: center; color: white; padding: 40px; }
        .left-panel h1 { font-size: 42px; font-weight: bold; text-align: center; }
        .right-panel { flex: 1; background: #ffffff; display: flex; justify-content: center; align-items: center; padding: 40px; box-shadow: -2px 0 10px rgba(0, 0, 0, 0.05); }
        .form-box { width: 100%; max-width: 380px; }
        h2 { margin-bottom: 10px; color: #333; }
        .form-box p { color: #666; margin-bottom: 20px; }
        input[type="text"], input[type="email"], input[type="password"] { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 6px; }
        button { width: 100%; padding: 12px; background: #6a11cb; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; }
        button:hover { background: #5a0fb8; }
        .message { margin-bottom: 15px; padding: 10px; border-radius: 5px; font-size: 14px; }
        .error { background-color: #ffdddd; color: #d8000c; border-left: 4px solid #d8000c; }
        .success { background-color: #ddffdd; color: #4F8A10; border-left: 4px solid #4F8A10; }
        .bottom-links { margin-top: 15px; text-align: center; }
        .bottom-links a { text-decoration: none; color: #6a11cb; }
    </style>
</head>
<body>
    <div class="left-panel">
        <h1>Daftar<br>Admin Baru</h1>
    </div>
    <div class="right-panel">
        <div class="form-box">
            <h2>Signup Admin</h2>
            <p>Silakan buat akun admin baru untuk RS Karitas.</p>
            <?php if ($error): ?><div class="message error"><?= htmlspecialchars($error); ?></div><?php endif; ?>
            <?php if ($success): ?><div class="message success"><?= $success; ?></div><?php endif; ?>
            <?php if (empty($success)): ?>
            <form method="POST" action="">
                <input type="text" name="username" placeholder="Username" required maxlength="50">
                <input type="email" name="email" placeholder="Email (opsional)" maxlength="100">
                <input type="password" name="password" placeholder="Password (min 6 char)" required minlength="6">
                <input type="password" name="confirm" placeholder="Konfirmasi Password" required>
                <button type="submit">Daftar</button>
            </form>
            <?php endif; ?>
            <div class="bottom-links">
                Sudah punya akun? <a href="login.php">Login</a>
            </div>
        </div>
    </div>
</body>
</html>
