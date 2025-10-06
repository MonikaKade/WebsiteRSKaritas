<?php
session_start();
require 'config.php'; // pastikan koneksi $conn sudah dibuat

$message = "";

// Fungsi untuk membatasi jumlah request reset dalam 1 jam
function too_many_requests($conn, $email) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM password_reset WHERE email=? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    return ($r['c'] >= 5);
}

// Jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Pastikan key 'email' ada sebelum digunakan
    if (!isset($_POST['email']) || empty(trim($_POST['email']))) {
        $message = "<div class='alert alert-danger'>Masukkan email Anda terlebih dahulu.</div>";
    } else {
        $email = trim($_POST['email']);
        $generic_msg = "<div class='alert alert-info'>Jika email terdaftar, instruksi reset telah dikirim (atau tampil di layar untuk tes).</div>";

        // Cek apakah email terdaftar di tabel admin
        $stmt = $conn->prepare("SELECT username FROM admin WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $username = $row['username'];

            // Cek batas request reset password
            if (too_many_requests($conn, $email)) {
                $message = $generic_msg;
            } else {
                // Buat token unik
                $token = bin2hex(random_bytes(32)); // contoh: 64 karakter acak
                $token_hash = hash('sha256', $token);
                $expires_at = date('Y-m-d H:i:s', time() + 3600); // berlaku 1 jam

                // Simpan token ke database
                $ip = $_SERVER['REMOTE_ADDR'];
                $ins = $conn->prepare("INSERT INTO password_reset (email, token_hash, expires_at, request_ip) VALUES (?, ?, ?, ?)");
                $ins->bind_param("ssss", $email, $token_hash, $expires_at, $ip);
                $ins->execute();

                // Buat link reset (ganti domain sesuai punya kamu)
                $reset_link = "http://localhost/WebsiteRSKaritas/admin/reset.php?token=" . $token . "&email=" . urlencode($email);

                // ❗Versi ini hanya menampilkan link di layar (bukan kirim email)
                $message = "
                    <div class='alert alert-success'>
                        Halo <b>{$username}</b>,<br>
                        Berikut tautan reset password Anda (berlaku 1 jam):<br>
                        <a href='{$reset_link}'>{$reset_link}</a>
                    </div>
                ";
            }
        } else {
            // Email tidak ditemukan → tetap tampilkan pesan generik
            $message = $generic_msg;
        }
    }
}
?>

<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Lupa Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center" style="height:100vh;background:#f4f6f7">
  <div class="card p-4 shadow" style="width:360px;">
    <h4 class="text-center mb-3">🔒 Lupa Password</h4>
    <?= $message ?>
    <form method="post" action="">
      <div class="mb-3">
        <label for="email" class="form-label">Masukkan Email</label>
        <input type="email" name="email" id="email" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Kirim Instruksi Reset</button>
      <a href="login.php" class="d-block text-center mt-3">⬅️ Kembali ke Login</a>
    </form>
  </div>
</body>
</html>
