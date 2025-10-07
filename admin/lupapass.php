<?php
session_start();
require 'config.php'; // koneksi ke database
require 'vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = isset($_POST['input']) ? trim($_POST['input']) : '';

    // cari admin berdasarkan email/nohp
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email=? OR nohp=? LIMIT 1");
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $admin = $res->fetch_assoc();
        $email = $admin['email']; // email tujuan dari database

        // buat token unik
        $token = bin2hex(random_bytes(32));
        $token_hash = hash('sha256', $token);
        $expires = date('Y-m-d H:i:s', time() + 3600); // 1 jam

        // simpan token di database
        $stmt2 = $conn->prepare("INSERT INTO password_reset (email, token_hash, expires_at, request_ip) VALUES (?, ?, ?, ?)");
        $ip = $_SERVER['REMOTE_ADDR'];
        $stmt2->bind_param("ssss", $email, $token_hash, $expires, $ip);
        $stmt2->execute();

        // buat link reset
        $reset_link = "http://localhost/WebsiteRSKaritas/admin/reset.php?token=$token&email=" . urlencode($email);

        // PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'vavakade0805@gmail.com'; // email pengirim
            $mail->Password = 'nrhvfvpmvewwguol'; // app password Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Pengirim
            $mail->setFrom('vavakade0805@gmail.com', 'Reset Password System');

            // Penerima dari input user/database
            $mail->addAddress($email, $admin['username'] ?? 'Admin');

            // Isi email
            $mail->isHTML(true);
            $mail->Subject = 'Reset Password Admin';
            $mail->Body = "
                <p>Halo,</p>
                <p>Kami menerima permintaan untuk mereset password akun admin Anda.</p>
                <p>Silakan klik link berikut untuk mengatur ulang password:</p>
                <p><a href='$reset_link'>$reset_link</a></p>
                <p>Link ini berlaku selama 1 jam.</p>
                <hr>
                <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
            ";

            $mail->send();

            $message = "<div class='alert alert-success'>Link reset password telah dikirim ke email: <b>{$email}</b></div>";
        } catch (Exception $e) {
            $message = "<div class='alert alert-danger'>Gagal mengirim email: {$mail->ErrorInfo}</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Email atau nomor tidak ditemukan.</div>";
    }
}
?>

<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Lupa Password Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
  <div class="card p-4 shadow" style="width:360px;">
    <h4 class="text-center mb-3">🔒 Lupa Password Admin</h4>
    <?= $message ?>
    <form method="post">
      <div class="mb-3">
        <label for="input" class="form-label">Email atau Nomor HP</label>
        <input type="text" name="input" id="input" class="form-control" required placeholder="Masukkan email atau nomor HP">
      </div>
      <button type="submit" class="btn btn-primary w-100">Kirim Link Reset</button>
      <a href="login.php" class="d-block text-center mt-3">⬅️ Kembali ke Login</a>
    </form>
  </div>
</body>
</html>
