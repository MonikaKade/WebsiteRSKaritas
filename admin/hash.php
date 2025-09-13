<?php
// hash.php
// Gunakan file ini untuk menghasilkan hash password satu kali.
// Cara pakai:
// 1. Ubah $password_default jika mau (opsional).
// 2. Buka di browser: http://localhost/NAMA_FOLDER_PROJECT/hash.php
// 3. Atau kirim password lewat GET: hash.php?pw=admin123
// 4. Copy string hash yang muncul (dimulai dengan $2y$...) dan paste ke query UPDATE di database.
// 5. HAPUS file ini setelah selesai!

// default password (ganti jika mau)
$password_default = 'admin123';

// ambil dari query string jika ada (lebih fleksibel)
$password = isset($_GET['pw']) && $_GET['pw'] !== '' ? $_GET['pw'] : $password_default;

// generate hash
$hash = password_hash($password, PASSWORD_DEFAULT);

// tampilkan hasil rapi
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <title>Generate Password Hash</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 30px; background: #f7f9fb; color: #222; }
    .container { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 6px 18px rgba(0,0,0,0.06); max-width: 720px; margin: auto; }
    input[type="text"] { width: 60%; padding: 8px; border: 1px solid #ccc; border-radius: 6px; }
    button { padding: 8px 12px; border: none; background: #2c3e50; color: #fff; border-radius: 6px; cursor: pointer; }
    .hash { background: #f0f3f5; padding: 12px; border-radius: 6px; word-break: break-all; margin-top: 12px; font-family: monospace; }
    .note { color: #666; margin-top: 10px; font-size: 14px; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Generate Password Hash ( sekali pakai )</h2>
    <form method="get" action="">
      <label>Masukkan password yang ingin di-hash (opsional):</label><br>
      <input type="text" name="pw" placeholder="admin123" value="<?= htmlspecialchars($password) ?>" />
      <button type="submit">Buat Hash</button>
    </form>

    <div class="note">
      <strong>Hasil hash untuk password:</strong> <code><?= htmlspecialchars($password) ?></code>
    </div>

    <div class="hash"><?= htmlspecialchars($hash) ?></div>

    <p class="note">
      Cara pakai: copy string hash di atas (seluruhnya, termasuk awalan <code>$2y$</code>) lalu jalankan query:
    </p>
    <pre class="note">UPDATE admin SET password = 'PASTE_HASH_DISINI' WHERE username = 'admin';</pre>

    <p class="note" style="color:#b33;">
      Ingat: HAPUS file <code>hash.php</code> dari server setelah selesai untuk keamanan.
    </p>
  </div>
</body>
</html>
