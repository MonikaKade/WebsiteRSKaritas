<?php
include "config.php";

// Ambil data admin (misalnya ID=1)
$adminId = 1;
$result = $conn->query("SELECT * FROM admin WHERE id=$adminId");
$admin = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $nohp = trim($_POST['nohp']);
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    $error = "";
    $success = "";

    if (!empty($password_lama) || !empty($password_baru) || !empty($konfirmasi_password)) {
        if (password_verify($password_lama, $admin['password'])) {
            if ($password_baru === $konfirmasi_password) {
                $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
                $sql = "UPDATE admin SET nama=?, username=?, email=?, nohp=?, password=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssi", $nama, $username, $email, $nohp, $password_hash, $adminId);

                if ($stmt->execute()) {
                    $success = "Akun berhasil diperbarui (termasuk password)";
                } else {
                    $error = "Gagal update data!";
                }
            } else {
                $error = "Konfirmasi password baru tidak cocok!";
            }
        } else {
            $error = "Password lama salah!";
        }
    } else {
        $sql = "UPDATE admin SET nama=?, username=?, email=?, nohp=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nama, $username, $email, $nohp, $adminId);

        if ($stmt->execute()) {
            $success = "Akun berhasil diperbarui!";
        } else {
            $error = "Gagal update data!";
        }
    }

    // Refresh data admin setelah update
    $result = $conn->query("SELECT * FROM admin WHERE id=$adminId");
    $admin = $result->fetch_assoc();
}
?>

<!-- Mulai konten dalam dashboard -->
<div class="card p-4 shadow-sm">
  <h3 class="mb-4">⚙️ Pengaturan Akun</h3>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>
  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Nama Admin</label>
        <input type="text" name="nama" class="form-control" value="<?= $admin['nama'] ?? '' ?>" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" value="<?= $admin['username'] ?>" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= $admin['email'] ?? '' ?>">
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Nomor HP</label>
        <input type="text" name="nohp" class="form-control" value="<?= $admin['nohp'] ?? '' ?>">
      </div>
    </div>

    <hr>
    <h5 class="mb-3">🔐 Ubah Password</h5>
    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label">Password Lama</label>
        <input type="password" name="password_lama" class="form-control" placeholder="Isi jika ingin ubah password">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Password Baru</label>
        <input type="password" name="password_baru" class="form-control">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Konfirmasi Password Baru</label>
        <input type="password" name="konfirmasi_password" class="form-control">
      </div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">💾 Simpan Perubahan</button>
  </form>
</div>
