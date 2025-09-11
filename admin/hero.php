<?php
include "config.php";

// Ambil semua foto hero
$sql = "SELECT * FROM hero ORDER BY id ASC";
$result = $conn->query($sql);

$heroData = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $heroData[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Foto Hero</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-sm">
    <div class="card-body text-center">
      <h3 class="mb-4">✨ Kelola Foto Hero</h3>

      <div class="row justify-content-center">
        <?php if (count($heroData) > 0): ?>
          <?php foreach($heroData as $row): ?>
            <div class="col-md-4 mb-4">
              <div class="card">
                <img src="<?= $row['foto'] ?>" alt="Hero" class="card-img-top" style="height:200px; object-fit:cover;">
                <div class="card-body text-center">
                  <!-- Form Update Foto -->
                  <form action="update_hero.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="file" name="foto" accept="image/*" class="form-control mb-2" required>
                    <button type="submit" class="btn btn-warning btn-sm">🔄 Ganti</button>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-muted">Belum ada foto hero</p>
        <?php endif; ?>
      </div>

      <!-- Form Tambah Foto (maks 3) -->
      <?php if (count($heroData) < 3): ?>
        <form action="simpan_hero.php" method="POST" enctype="multipart/form-data" class="mt-4">
          <input type="file" name="foto[]" accept="image/*" multiple class="form-control mb-3" required>
          <button type="submit" class="btn btn-primary">➕ Tambah Foto</button>
          <p class="text-muted mt-2">* Maksimal 3 foto. Anda sudah upload <?= count($heroData) ?> foto.</p>
        </form>
      <?php else: ?>
        <p class="text-danger mt-4">⚠ Maksimal 3 foto hero sudah tercapai.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

</body>
</html>
