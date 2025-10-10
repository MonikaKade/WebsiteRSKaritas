<?php
include "config.php";

// Ambil semua hero
$sql = "SELECT * FROM hero ORDER BY id ASC";
$result = $conn->query($sql);

$heroData = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $heroData[] = $row;
    }
}

// Ambil flash message dari session
$statusMessage = "";
if(isset($_SESSION['statusMessage'])){
    $statusMessage = $_SESSION['statusMessage'];
    unset($_SESSION['statusMessage']); // hapus setelah dibaca
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Hero (2 Foto + 1 Video)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .hero-card {
      border: none;
      border-radius: 15px;
      overflow: hidden;
      transition: all 0.3s ease;
    }
    .hero-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .card-body {
      background-color: #ffffff;
    }
    h3 {
      color: #006b6b;
      font-weight: 600;
    }
    .form-label {
      font-weight: 500;
      color: #333;
    }
    .upload-section {
      background: #ffffff;
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      margin-top: 30px;
    }
    .btn {
      border-radius: 8px;
    }
    .btn-primary, .btn-success {
      padding: 8px 16px;
    }
  </style>
</head>
<body>

<div class="container my-5">
  
  <?php if($statusMessage): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
      <?= $statusMessage ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="text-center mb-5">
    <h3>✨ Kelola Hero (2 Foto + 1 Video)</h3>
    <p class="text-muted">Kelola tampilan hero website Anda dengan dua gambar dan satu video utama.</p>
  </div>

  <div class="row justify-content-center">
    <?php if (count($heroData) > 0): ?>
      <?php foreach($heroData as $row): ?>
        <div class="col-md-4 mb-4">
          <div class="card hero-card h-100">
            <?php if (!empty($row['video'])): ?>
              <video controls class="card-img-top" style="height:200px; object-fit:cover;">
                <source src="<?= $row['video'] ?>" type="video/mp4">
              </video>
            <?php elseif (!empty($row['foto'])): ?>
              <img src="<?= $row['foto'] ?>" alt="Hero" class="card-img-top" style="height:200px; object-fit:cover;">
            <?php endif; ?>

            <div class="card-body d-flex justify-content-between align-items-center">
              <form action="hapus_hero.php" method="POST" class="m-0">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" class="btn btn-danger btn-sm">🗑 Hapus</button>
              </form>
              <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal<?= $row['id'] ?>">✏️ Update</button>
            </div>
          </div>
        </div>

        <!-- Modal Update -->
        <div class="modal fade" id="updateModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="updateModalLabel<?= $row['id'] ?>" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
              <form action="update_hero.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header bg-primary text-white">
                  <h5 class="modal-title" id="updateModalLabel<?= $row['id'] ?>">Update Hero</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">

                  <label class="form-label">Ganti Foto (opsional):</label>
                  <input type="file" name="foto" accept="image/*" class="form-control mb-3">

                  <label class="form-label">Ganti Video (opsional):</label>
                  <input type="file" name="video" accept="video/*" class="form-control mb-3">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-success">💾 Simpan Perubahan</button>
                </div>
              </form>
            </div>
          </div>
        </div>

      <?php endforeach; ?>
    <?php else: ?>
      <div class="text-center text-muted mb-4">
        <p>Belum ada hero yang ditambahkan.</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- Form Tambah -->
  <?php if (count($heroData) < 3): ?>
    <div class="upload-section">
      <h5 class="mb-3 text-center text-success">➕ Tambah Hero Baru</h5>
      <div class="row">
        <div class="col-md-4 mb-3">
          <form action="simpan_hero.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="type" value="foto1">
            <label class="form-label">Foto 1</label>
            <input type="file" name="foto" accept="image/*" class="form-control mb-2" required>
            <button type="submit" class="btn btn-primary w-100">Upload Foto 1</button>
          </form>
        </div>

        <div class="col-md-4 mb-3">
          <form action="simpan_hero.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="type" value="foto2">
            <label class="form-label">Foto 2</label>
            <input type="file" name="foto" accept="image/*" class="form-control mb-2" required>
            <button type="submit" class="btn btn-primary w-100">Upload Foto 2</button>
          </form>
        </div>

        <div class="col-md-4 mb-3">
          <form action="simpan_hero.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="type" value="video">
            <label class="form-label">Video</label>
            <input type="file" name="video" accept="video/*" class="form-control mb-2" required>
            <button type="submit" class="btn btn-success w-100">Upload Video</button>
          </form>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
