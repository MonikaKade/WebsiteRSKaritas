<?php
session_start();
include "admin/config.php"; // koneksi DB

// Ambil data dari tabel hero
$heroData = [];
$sqlHero = "SELECT * FROM hero";
$resultHero = $conn->query($sqlHero);

if ($resultHero && $resultHero->num_rows > 0) {
    while ($row = $resultHero->fetch_assoc()) {
        $heroData[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rumah Sakit Karitas Weetabula</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css" />

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body>
    <!-- NAVBAR -->
    <header class="navbar">
        <div class="navbar-container">
            <div class="navbar-left">
                <img src="asset/logo.jpeg" alt="logo" class="logo" />
                <span class="site-name">Rumah Sakit Karitas Weetabula</span>
            </div>

            <nav class="nav-menu">
                <a href="#home">Home</a>
                <a href="#tentangkami">Tentang Kami</a>
                <a href="#layanan">Layanan</a>
                <a href="#kontak">Kontak</a>
            </nav>

            <div class="navbar-search">
                <input type="text" id="searchInput" placeholder="Pencarian Cepat..." />
                <button class="search-btn" onclick="cari()">Cari</button>
            </div>
        </div>
    </header>

    <script>
        function cari() {
            let keyword = document.getElementById("searchInput").value.toLowerCase();
            let sections = document.querySelectorAll("section");

            let found = false;
            sections.forEach(sec => {
                if (sec.innerText.toLowerCase().includes(keyword)) {
                    sec.scrollIntoView({ behavior: "smooth" });
                    sec.style.backgroundColor = "rgba(255,255,0,0.2)"; // highlight
                    found = true;
                }
            });

            if (!found) {
                alert("Hasil tidak ditemukan!");
            }
        }
    </script>

  <section id="home" class="slider">
  <?php foreach ($heroData as $row): ?>
    <div class="slides">
      <?php if (!empty($row['video'])): ?>
        <video autoplay muted loop class="hero-media">
          <source src="admin/<?= $row['video'] ?>" type="video/mp4">
        </video>
      <?php elseif (!empty($row['foto'])): ?>
        <img src="admin/<?= $row['foto'] ?>" alt="Hero Image" class="hero-media">
      <?php else: ?>
        <img src="asset/default.jpg" alt="Default" class="hero-media">
      <?php endif; ?>
    </div>
  <?php endforeach; ?>

  <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
  <a class="next" onclick="plusSlides(1)">&#10095;</a>

  <div class="dots">
    <?php for ($i = 1; $i <= count($heroData); $i++): ?>
      <span class="dot" onclick="currentSlide(<?= $i ?>)"></span>
    <?php endfor; ?>
  </div>
</section>



    <!-- TENTANG KAMI -->
    <section id="tentangkami" class="section section-tentang-kami fade-section">
        <div class="wrapper">
            <div class="sidebar">
                <ul>
                    <li class="active" data-content="profil">Profil</li>
                    <li data-content="visi">Visi & Misi</li>
                    <li data-content="sejarah">Sejarah</li>
                    <li data-content="salam">Salam Direktur</li>
                    <li data-content="penghargaan">Penghargaan & Akreditasi</li>
                </ul>
            </div>
            <div class="content" id="contentTentangKami"></div>
        </div>
    </section>

    <!-- LAYANAN -->
    <section id="layanan" class="section section-layanan">
        <div class="wrapper">
            <div class="sidebar">
                <ul>
                    <li class="active" data-content="rawatjalan">Rawat Inap</li>
                    <li data-content="igd">IGD 24 Jam</li>
                    <li data-content="rawatinap">Rawat Jalan</li>
                    <li data-content="penunjangmedis">Penunjang Medis</li>
                </ul>
            </div>
            <div class="content" id="contentLayanan"></div>
        </div>
    </section>



    <!-- KONTAK -->
    <section id="kontak" class="section section-kontak fade-section">
        <div class="wrapper">
            <div class="contact-info" style="flex: 1;">
                <h2>Hubungi Kami</h2>
                <p>Kami siap membantu dan menjawab pertanyaan Anda. Silakan hubungi kami melalui informasi di bawah ini.</p>

                <div class="info-box">
                    <i class="fas fa-home"></i>
                    <div>
                        <h4>Alamat</h4>
                        <p>Jl. Sehat No. 123, Weetabula</p>
                    </div>
                </div>

                <div class="info-box">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h4>Telepon</h4>
                        <p>+62 812 3456 7890</p>
                    </div>
                </div>

                <div class="info-box">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Email</h4>
                        <p>info@rsweetabula.com</p>
                    </div>
                </div>
            </div>

            <div class="contact-form" style="flex: 1;">
                <h3>Kirim Pesan</h3>
                <form id="formKontak">
                    <input type="text" placeholder="Nama Lengkap" required>
                    <input type="email" placeholder="Email" required>
                    <textarea placeholder="Tulis Pesan Anda..." required></textarea>
                    <button type="submit">Kirim</button>
                </form>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-col">
                <img src="asset/logo.jpeg" alt="Logo Rumah Sakit" class="logo" />
                <p>Memberikan pelayanan kesehatan terbaik dengan tenaga profesional dan fasilitas modern untuk masyarakat.</p>
                <div class="footer-social">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#">Beranda</a></li>
                    <li><a href="#">Fasilitas & Layanan</a></li>
                    <li><a href="#">Berita</a></li>
                    <li><a href="#">Kontak</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Layanan</h4>
                <ul>
                    <li><a href="#">Dokter</a></li>
                    <li><a href="#">Poliklinik</a></li>
                    <li><a href="#">Rawat Inap</a></li>
                    <li><a href="#">Rawat Jalan</a></li>
                    <li><a href="#">IGD 24 Jam</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Informasi</h4>
                <p><i class="fas fa-phone"></i> +62 812 3456 7890</p>
                <p><i class="fas fa-envelope"></i> info@rsweetabula.com</p>
                <p><i class="fas fa-map-marker-alt"></i> Jl. Sehat No. 123, Weetabula</p>
                <h4>Jam Operasional</h4>
                <p>Senin - Sabtu (08:00 - 21:00)</p>
                <p>Minggu (Tutup)</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© 2025 Rumah Sakit Karitas Weetabula. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- JS -->
    <script src="js/script.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>
