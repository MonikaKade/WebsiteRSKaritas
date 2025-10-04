<?php
session_start();
require 'config.php'; // $conn = mysqli connection
require 'vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = "";

// Simple rate-limit: max 5 requests per username per hour
function too_many_requests($conn, $username) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM password_reset WHERE username=? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    return ($r['c'] >= 5);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);

    // Generic response message to avoid user enumeration
    $generic_msg = "<div class='alert alert-info'>Jika akun ada, instruksi reset sudah dikirim ke email terdaftar.</div>";

    if (empty($username)) {
        $message = "<div class='alert alert-danger'>Masukkan username atau email.</div>";
    } else {
        // Optional: check existence of username and get email
        $stmt = $conn->prepare("SELECT email FROM admin WHERE username=? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $email = $row['email']; // pastikan tabel admin punya kolom email!

            // Rate limiting
            if (too_many_requests($conn, $username)) {
                // Do not reveal exact reason; show generic message
                $message = $generic_msg;
            } else {
                // Generate secure token
                $token = bin2hex(random_bytes(32)); // 64 chars
                $token_hash = hash('sha256', $token);
                $expires_at = date('Y-m-d H:i:s', time() + 3600); // 1 hour

                // Store token hash
                $ip = $_SERVER['REMOTE_ADDR'];
                $ins = $conn->prepare("INSERT INTO password_reset (username, token_hash, expires_at, request_ip) VALUES (?, ?, ?, ?)");
                $ins->bind_param("ssss", $username, $token_hash, $expires_at, $ip);
                $ins->execute();

                // Send email with link
                $reset_link = "https://yourdomain.com/reset.php?token=" . $token . "&u=" . urlencode($username);

                // PHPMailer SMTP setup (ganti credential)
                $mail = new PHPMailer(true);
                try {
                    // SMTP config
                    $mail->isSMTP();
                    $mail->Host = 'smtp.example.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'no-reply@gmail.com';
                    $mail->Password = 'SMTP_PASSWORD';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('no-reply@example.com', 'My App');
                    $mail->addAddress($email, $username);
                    $mail->Subject = 'Instruksi Reset Password';
                    $mail->isHTML(true);
                    $mail->Body = "
                        <p>Kami menerima permintaan reset password. Klik tautan di bawah untuk mereset password (berlaku 1 jam):</p>
                        <p><a href='{$reset_link}'>Reset Password</a></p>
                        <p>Jika bukan Anda, abaikan email ini.</p>
                    ";
                    $mail->send();
                } catch (Exception $e) {
                    // Optional: log error $mail->ErrorInfo
                }

                $message = $generic_msg;
            }
        } else {
            // Username not found — still show generic message to prevent enumeration
            $message = $generic_msg;
        }
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Lupa Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center" style="height:100vh;background:#f4f6f7">
  <div class="card p-4" style="width:360px;">
    <h4 class="text-center mb-3">🔒 Lupa Password</h4>
    <?= $message ?>
    <form method="post">
      <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <button class="btn btn-primary w-100">Kirim Instruksi Reset</button>
      <a href="login.php" class="d-block text-center mt-3">Kembali ke Login</a>
    </form>
  </div>
</body>
</html>
