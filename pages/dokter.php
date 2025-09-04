<?php
include("../admin/config.php");

// ambil parameter poli
$poli_id = isset($_GET['poli_id']) ? intval($_GET['poli_id']) : 0;

// ambil data poli
$poli = mysqli_query($conn, "SELECT * FROM poli WHERE id=$poli_id");
$poli_data = mysqli_fetch_assoc($poli);

if (!$poli_data) {
    die("Poli tidak ditemukan!");
}

// ambil data dokter berdasarkan poli
$sql = "
    SELECT d.*, p.nama_poli 
    FROM dokter d
    JOIN poli p ON d.poli_id = p.id
    WHERE d.poli_id = $poli_id
";
$dokter = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Dokter</title>
  <link rel="stylesheet" href="../css/dokter.css"/>
</head>
<body>
  <!-- Navbar -->
  <header class="navbar">
    <div class="navbar-container">
      <div class="navbar-left">
        <img src="../asset/logo.jpeg" alt="logo" class="logo"/>
        <span class="site-name">Rumah Sakit Karitas Weetabula</span>
      </div>
      <nav class="nav-menu">
        <a href="#home">Home</a>
        <a href="#tentangkami">Tentang Kami</a>
        <a href="#layanan">Layanan</a>
        <a href="#fasilitas">Fasilitas</a>
        <a href="#kontak">Kontak</a>
      </nav>
      <div class="navbar-search">
        <input type="text" placeholder="Pencarian Cepat..."/>
        <button class="search-btn">Cari</button>
      </div>
    </div>
  </header>

  <!-- Hero -->
  <section class="hero">
    <div class="hero-content">
      <h1>Periksakan Diri Anda<br>Dengan Dokter Terakreditasi</h1>
      <p>
        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris 
        nisi ut aliquip ex ea commodo consequat.
      </p>
    </div>
  </section>

  <!-- Judul Poli dinamis -->
  <h2 style="text-align:center; margin:30px 0;">
    Daftar Dokter Poli <?= htmlspecialchars($poli_data['nama_poli']); ?>
  </h2>

  <!-- Container dokter -->
  <div id="dokter-container" style="max-width:900px; margin:auto;">
    <?php while($d = mysqli_fetch_assoc($dokter)) { ?>
      <div class="dokter-card">
      <img src="uploads/dokter/<?= $d['foto'] ?>" 
        alt="Foto Dokter" 
        style="width:120px;height:120px;object-fit:cover;border-radius:10px;">
      <strong><?= $d['nama'] ?></strong><br>
      <?= $d['spesialis'] ?><br>
      SIP: <?= $d['izin'] ?><br>

        <h4>Jadwal:</h4>
        <ul>
          <?php
          $jadwal = mysqli_query($conn, "SELECT * FROM jadwal_dokter WHERE dokter_id=".$d['id']);
          while($j = mysqli_fetch_assoc($jadwal)) {
            echo "<li>".$j['hari']." : ".substr($j['jam_mulai'],0,5)." - ".substr($j['jam_selesai'],0,5)."</li>";
          }
          ?>
        </ul>
      </div>
      <hr>
    <?php } ?>
  </div>
</body>
</html>
