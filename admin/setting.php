<?php
if (session_status() === PHP_SESSION_NONE) session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "config.php";

// Pastikan admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$adminId = intval($_SESSION['admin_id']);
$result = $conn->query("SELECT * FROM admin WHERE id = $adminId");
if (!$result || $result->num_rows === 0) {
    die("<div class='alert alert-danger'>Akun tidak ditemukan di database.</div>");
}

$admin = $result->fetch_assoc();
$error = "";
$success = "";

// === HANDLE UPDATE / DELETE ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // UPDATE AKUN
    if ($action === 'update') {
        $nama = trim($_POST['nama']);
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $nohp = trim($_POST['nohp']);
        $password_lama = $_POST['password_lama'] ?? '';
        $password_baru = $_POST['password_baru'] ?? '';
        $konfirmasi_password = $_POST['konfirmasi_password'] ?? '';

        if (empty($nama) || empty($username)) {
            $error = "⚠️ Nama dan username wajib diisi!";
        } else {
            if (!empty($password_lama) || !empty($password_baru) || !empty($konfirmasi_password)) {
                if (!password_verify($password_lama, $admin['password'])) {
                    $error = "❌ Password lama salah!";
                } elseif ($password_baru !== $konfirmasi_password) {
                    $error = "⚠️ Konfirmasi password baru tidak cocok!";
                } else {
                    $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE admin SET nama=?, username=?, email=?, nohp=?, password=? WHERE id=?");
                    $stmt->bind_param("sssssi", $nama, $username, $email, $nohp, $password_hash, $adminId);
                    if ($stmt->execute()) {
                        $success = "✅ Akun dan password berhasil diperbarui!";
                        $_SESSION['admin'] = $nama;
                    } else {
                        $error = "❌ Gagal memperbarui akun.";
                    }
                }
            } else {
                $stmt = $conn->prepare("UPDATE admin SET nama=?, username=?, email=?, nohp=? WHERE id=?");
                $stmt->bind_param("ssssi", $nama, $username, $email, $nohp, $adminId);
                if ($stmt->execute()) {
                    $success = "✅ Akun berhasil diperbarui!";
                    $_SESSION['admin'] = $nama;
                } else {
                    $error = "❌ Gagal memperbarui data akun.";
                }
            }
        }

        $result = $conn->query("SELECT * FROM admin WHERE id = $adminId");
        $admin = $result->fetch_assoc();
    }

    // HAPUS AKUN
    if ($action === 'delete') {
        $confirm = $_POST['confirm_delete'] ?? '';
        if ($confirm === 'HAPUS') {
            $del = $conn->prepare("DELETE FROM admin WHERE id = ?");
            $del->bind_param("i", $adminId);
            if ($del->execute()) {
                session_destroy();
                header("Location: login.php?deleted=1");
                exit;
            } else {
                $error = "❌ Gagal menghapus akun!";
            }
        } else {
            $error = "⚠️ Konfirmasi salah! Ketik 'HAPUS' untuk melanjutkan.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>⚙️ Pengaturan Akun Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f4f7f8;
      font-family: 'Segoe UI', sans-serif;
    }

    .settings-container {
      max-width: 850px;
      margin: 50px auto;
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.05);
      padding: 40px;
      transition: 0.3s ease;
    }

    .settings-container:hover {
      box-shadow: 0 12px 25px rgba(0,0,0,0.08);
    }

    .settings-header h3 {
      font-weight: 700;
      color: #006b6b;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .form-label {
      font-weight: 600;
      color: #333;
    }

    .form-control {
      border-radius: 12px;
      border: 1px solid #ddd;
      padding: 10px 14px;
      transition: all 0.2s ease;
    }

    .form-control:focus {
      border-color: #009999;
      box-shadow: 0 0 0 3px rgba(0,153,153,0.15);
    }

    .btn-primary {
      background-color: #009999;
      border: none;
      border-radius: 10px;
      padding: 10px 20px;
      font-weight: 600;
    }

    .btn-primary:hover {
      background-color: #007a7a;
    }

    .btn-outline-danger {
      border-radius: 10px;
      font-weight: 600;
      padding: 8px 18px;
    }

    .alert {
      border-radius: 10px;
    }

    .divider {
      height: 1px;
      background-color: #e5e5e5;
      margin: 30px 0;
    }

    .delete-section h5 {
      font-weight: 700;
      color: #d9534f;
    }

    .delete-section p {
      font-size: 0.9rem;
      color: #666;
    }

    .modal-content {
      border-radius: 15px;
    }

    .modal-header {
      border-top-left-radius: 15px;
      border-top-right-radius: 15px;
    }

    @media (max-width: 768px) {
      .settings-container {
        padding: 25px;
      }
    }
  </style>
</head>
<body>

<div class="settings-container">
  <div class="settings-header mb-4">
    <h3>⚙️ Pengaturan Akun</h3>
  </div>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>
  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <form method="POST">
    <input type="hidden" name="action" value="update">

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Nama Admin</label>
        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($admin['nama']) ?>" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($admin['username']) ?>" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($admin['email']) ?>">
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Nomor HP</label>
        <input type="text" name="nohp" class="form-control" value="<?= htmlspecialchars($admin['nohp']) ?>">
      </div>
    </div>

    <div class="divider"></div>

    <h5 class="mb-3 text-secondary">🔐 Keamanan</h5>
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

  <div class="divider"></div>

  <div class="delete-section">
    <h5>🗑️ Hapus Akun</h5>
    <p>Tindakan ini tidak dapat dibatalkan. Semua data akun akan dihapus permanen.</p>
    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Hapus Akun</button>
  </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <input type="hidden" name="action" value="delete">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Akun</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Apakah kamu yakin ingin menghapus akun <strong><?= htmlspecialchars($admin['username']) ?></strong> secara permanen?</p>
          <p>Ketik <strong>HAPUS</strong> untuk konfirmasi.</p>
          <input type="text" name="confirm_delete" class="form-control" placeholder="Ketik HAPUS" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">🗑️ Hapus Sekarang</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
