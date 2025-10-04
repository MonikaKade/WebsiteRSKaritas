<?php
session_start();
require 'config.php';

$message = "";

// CSRF simple token
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}

function validate_password($pw) {
    // Contoh aturan: minimal 8 char, ada huruf, angka
    if (strlen($pw) < 8) return "Password minimal 8 karakter.";
    if (!preg_match('/[A-Z]/', $pw)) return "Masukkan minimal 1 huruf besar.";
    if (!preg_match('/[a-z]/', $pw)) return "Masukkan minimal 1 huruf kecil.";
    if (!preg_match('/[0-9]/', $pw)) return "Masukkan minimal 1 angka.";
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // show form only if token present
    $token = $_GET['token'] ?? '';
    $username = $_GET['u'] ?? '';
    // do not reveal anything here; form will submit to POST where token is validated
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $username = $_POST['username'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $csrf = $_POST['csrf'] ?? '';

    if (!hash_equals($_SESSION['csrf'], $csrf)) {
        $message = "<div class='alert alert-danger'>Token CSRF tidak valid.</div>";
    } elseif ($new_password !== $confirm_password) {
        $message = "<div class='alert alert-danger'>Konfirmasi password tidak cocok.</div>";
    } else {
        $v = validate_password($new_password);
        if ($v !== true) {
            $message = "<div class='alert alert-danger'>{$v}</div>";
        } else {
            // Validate token: compare hash
            $token_hash = hash('sha256', $token);

            $stmt = $conn->prepare("SELECT id, expires_at, used FROM password_resets WHERE username=? AND token_hash=? LIMIT 1");
            $stmt->bind_param("ss", $username, $token_hash);
            $stmt->execute();
            $res = $stmt->get_result();

            if (!$res || $res->num_rows === 0) {
                $message = "<div class='alert alert-danger'>Token tidak valid atau sudah digunakan.</div>";
            } else {
                $row = $res->fetch_assoc();
                if ($row['used']) {
                    $message = "<div class='alert alert-danger'>Token sudah dipakai.</div>";
                } elseif (strtotime($row['expires_at']) < time()) {
                    $message = "<div class='alert alert-danger'>Token telah kedaluwarsa.</div>";
                } else {
                    // All good: update password
                    $hash = password_hash($new_password, PASSWORD_DEFAULT);
                    $up = $conn->prepare("UPDATE admin SET password=? WHERE username=?");
                    $up->bind_param("ss", $hash, $username);
                    $up->execute();

                    // Mark token used (or delete rows)
                    $upd2 = $conn->prepare("UPDATE password_resets SET used=1 WHERE id=?");
                    $upd2->bind_param("i", $row['id']);
                    $upd2->execute();

                    // Optional: invalidate all other tokens for this user
                    $del = $conn->prepare("DELETE FROM password_resets WHERE username=?");
                    $del->bind_param("s", $username);
                    $del->execute();

                    // Optional: log the reset event (ip, time)
                    // logout other sessions if you have session tracking

                    $message = "<div class='alert alert-success'>✅ Password berhasil diubah. Silakan login.</div>";
                }
            }
        }
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Reset Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center" style="height:100vh;background:#f4f6f7">
  <div class="card p-4" style="width:420px;">
    <h4 class="text-center mb-3">🔑 Buat Password Baru</h4>
    <?= $message ?>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['token']) && !empty($_GET['u'])): ?>
      <form method="post">
        <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">
        <input type="hidden" name="username" value="<?= htmlspecialchars($_GET['u']) ?>">
        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
        <div class="mb-3">
          <label>Password Baru</label>
          <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Konfirmasi Password</label>
          <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <button class="btn btn-primary w-100">Simpan Password</button>
      </form>
    <?php else: ?>
      <div class="alert alert-info">Tautan reset harus digunakan dari email yang dikirim. Jika belum punya tautan, minta ulang reset.</div>
      <a href="lupapass.php" class="btn btn-outline-primary w-100">Minta Ulang Reset</a>
    <?php endif; ?>

  </div>
</body>
</html>
