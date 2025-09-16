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
  <link rel="stylesheet" href="../css/dokter.css" />
</head>

<body>
  <!-- Navbar -->
  <header class="navbar">
    <div class="navbar-container">
      <div class="navbar-left">
        <img src="../asset/logo.jpeg" alt="logo" class="logo" />
        <span class="site-name">Rumah Sakit Karitas Weetabula</span>
      </div>
      <nav class="nav-menu">
        <a href="../index.php">Home</a>
        <a href="../index.php">Tentang Kami</a>
        <a href="../index.php">Layanan</a>
        <a href="../index.php">Fasilitas</a>
        <a href="../index.php">Kontak</a>
      </nav>
      <div class="navbar-search">
  <input type="text" id="searchDokter" placeholder="Cari Dokter / Spesialis..." />
  <button type="button" class="search-btn" onclick="filterDokter()">Cari</button>
</div>

<script>
function filterDokter() {
  let keyword = document.getElementById("searchDokter").value.toLowerCase();
  let cards = document.querySelectorAll(".dokter-card");

  let firstMatch = null; // untuk simpan hasil pertama

  cards.forEach(card => {
    let text = card.innerText.toLowerCase();
    if (text.includes(keyword)) {
      card.style.display = "block";
      if (!firstMatch) firstMatch = card; // simpan yg pertama ketemu
    } else {
      card.style.display = "none";
    }
  });

  if (firstMatch) {
    firstMatch.scrollIntoView({ behavior: "smooth", block: "center" });
    firstMatch.style.backgroundColor = "rgba(255,255,0,0.2)"; // highlight
    setTimeout(() => firstMatch.style.backgroundColor = "", 2000); // hilangkan highlight setelah 2 detik
  } else {
    alert("Dokter tidak ditemukan!");
  }
}
</script>

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
  <div id="dokter-container">
    <?php while ($d = mysqli_fetch_assoc($dokter)) { ?>
      <div class="dokter-card">
  <img src="../admin/<?= $d['foto'] ?>" alt="Foto Dokter">

  <div class="dokter-info">
    <h5><?= $d['nama'] ?></h5>
    <p><?= $d['spesialis'] ?></p>
    <p><small>SIP: <?= $d['izin'] ?></small></p>
  </div>

  <div class="dokter-jadwal">
    <h6>Jadwal Praktek</h6>
    <table>
      <thead>
        <tr>
          <th>Hari</th>
          <th>Jam Mulai</th>
          <th>Jam Selesai</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $jadwal = mysqli_query($conn, "SELECT * FROM jadwal_dokter WHERE dokter_id=".$d['id']." ORDER BY 
          FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')");
        while($j = mysqli_fetch_assoc($jadwal)) {
          echo "<tr>
                  <td>".$j['hari']."</td>
                  <td>".substr($j['jam_mulai'],0,5)."</td>
                  <td>".substr($j['jam_selesai'],0,5)."</td>
                </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
    <?php } ?>
  </div>
</body>

</html>