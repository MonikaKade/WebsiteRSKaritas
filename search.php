<?php
include "admin/config.php";

$keyword = $_GET['q'] ?? ''; 

if ($keyword != '') {
    $sql = "SELECT * FROM layanan WHERE nama_layanan LIKE '%$keyword%' OR deskripsi LIKE '%$keyword%'";
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Hasil Pencarian</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <h2>Hasil Pencarian: <?= htmlspecialchars($keyword) ?></h2>
  <?php if (isset($result) && $result->num_rows > 0): ?>
    <ul>
      <?php while($row = $result->fetch_assoc()): ?>
        <li><b><?= $row['nama_layanan'] ?></b> - <?= $row['deskripsi'] ?></li>
      <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <p>Tidak ada hasil ditemukan.</p>
  <?php endif; ?>
</body>
</html>
