<?php
require 'config.php';

$message = "";
$token = $_GET['token'] ?? '';
$email = $_GET['email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $email = $_POST['email'];
    $password_input = $_POST['password'];

    // Validasi password minimal 8 karakter
    if (strlen($password_input) < 8) {
        $message = "<div class='alert alert-danger'>Password minimal 8 karakter.</div>";
    } else {
        $new_pass = password_hash($password_input, PASSWORD_DEFAULT);

        // verifikasi token
        $stmt = $conn->prepare("SELECT * FROM password_reset WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        if ($res && hash_equals($res['token_hash'], hash('sha256', $token)) && $res['expires_at'] > date('Y-m-d H:i:s')) {
            // update password admin
            $stmt2 = $conn->prepare("UPDATE admin SET password=? WHERE email=?");
            $stmt2->bind_param("ss", $new_pass, $email);
            $stmt2->execute();

            // hapus token dengan prepared statement
            $stmt3 = $conn->prepare("DELETE FROM password_reset WHERE email=?");
            $stmt3->bind_param("s", $email);
            $stmt3->execute();

            $message = "<div class='alert alert-success'>Password berhasil diubah! Silakan <a href='login.php'>login</a>.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Token tidak valid atau sudah kadaluarsa.</div>";
        }
    }
}
?>

<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Reset Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
  <div class="card p-4 shadow" style="width:360px;">
    <h4 class="text-center mb-3">🔑 Ubah Password</h4>
    <?= $message ?>
    <form method="post">
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
      <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
      <div class="mb-3">
        <label for="password" class="form-label">Password Baru</label>
        <input type="password" name="password" id="password" class="form-control" required placeholder="Minimal 8 karakter">
      </div>
      <button type="submit" class="btn btn-success w-100">Simpan Password Baru</button>
    </form>
  </div>
</body>
</html>
