<?php
session_start();
include 'admin/config.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // enkripsi sederhana

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
    } else { 
        $error = "Username atau password salah!";
    }
}
?>





<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Rumah Sakit Karitas Weetabula</title>
  <link rel="stylesheet" href="css/style.css"/>
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
      AOS.init();
    </script>
</head>
<body>
  <!-- Navbar -->
  <header class="navbar">
    <div class="navbar-container">
      <div class="navbar-left">
        <img src="asset/logo.jpeg" alt="logo" class="logo"/>
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

  <!-- Hero Slider -->
  <section id="home" class="slider">
    <div class="slides fade"><img src="asset/bg.jpeg" alt="slide1"></div>
    <div class="slides fade"><img src="asset/nav2.jpeg" alt="slide2"></div>
    <div class="slides fade"><img src="asset/nav3.jpeg" alt="slide3"></div>

    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>

    <div class="dots">
      <span class="dot" onclick="currentSlide(1)"></span>
      <span class="dot" onclick="currentSlide(2)"></span>
      <span class="dot" onclick="currentSlide(3)"></span>
    </div>
  </section>

  <!-- Tentang Kami -->
  <section id="tentangkami" class="section fade-section">
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar">
        <ul>
          <li class="active" onclick="showContentTentangKami('profil', event)">Profil</li>
          <li onclick="showContentTentangKami('visi', event)">Visi & Misi</li>
          <li onclick="showContentTentangKami('sejarah', event)">Sejarah</li>
          <li onclick="showContentTentangKami('salam', event)">Salam Direktur</li>
          <li onclick="showContentTentangKami('penghargaan', event)">Penghargaan & Akreditasi</li>
        </ul>
      </div>

      <!-- Konten -->
      <div class="content" id="contentTentangKami">
        <h2>Profil</h2>
        <p>
          Rumah Sakit Umum Karitas di Weetabula merupakan salah satu unit pelayanan kesehatan 
          yang dikelola oleh Kongregasi Suster Amalkasih Darah Mulia (ADM) melalui Yayasan Karitas Katolik Sumba
        </p>
      </div>
    </div>
  </section>

  <!-- Layanan -->
  <section id="layanan" class="section">
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar">
        <ul>
          <li onclick="showContentLayanan('rawatjalan', event)">Rawat Jalan</li>
          <li onclick="showContentLayanan('igd', event)">IGD 24 Jam</li>
          <li onclick="showContentLayanan('rawatinap', event)">Rawat Inap</li>
        </ul>
      </div>

    

      <!-- Konten dinamis -->
      <div class="content">
        <div id="submenu"></div>
        <div id="dokterDetail"> 
          <h2>
            KAMI SIAP MELAYANI ANDA DENGAN HATI DAN LAYANAN KAMI 
          </h2>
        </div>
      </div>
    </div>
  </section>

  <!-- Fasilitas -->
  <section id="fasilitas" class="section fade-section">
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar">
        <ul>
          <li onclick="showContentFasilitas('lab', event)">Lab</li>
          <li onclick="showContentFasilitas('radiologi', event)">Radiologi</li>
          <li onclick="showContentFasilitas('fisioterapi', event)">Fisioterapi</li>
          <li onclick="showContentFasilitas('apotik', event)">Apotik</li>
        </ul>
      </div>

      <!-- Konten -->
      <div id="contentFasilitas" class="content">
        <h2>Melayani Anda Dengan Fasilitas Rumah Sakit</h2>
        <p>Silakan pilih salah satu fasilitas di sidebar.</p>
      </div>
    </div>
  </section>

    <section id="kontak" class="contact-section">
  <div class="container">
    <!-- Judul -->
    <div class="contact-header">
      <h2>Contact Us</h2>
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
    </div>

    <!-- Isi Kontak + Form -->
    <div class="contact-content">
      <!-- Info Kontak (Kiri) -->
      <div class="contact-info">
        <div class="info-box">
          <i class="fas fa-home"></i>
          <div>
            <h4>Address</h4>
            <p>Jl. Sehat No. 123, Weetabula</p>
          </div>
        </div>
        <div class="info-box">
          <i class="fas fa-phone"></i>
          <div>
            <h4>Phone</h4>
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

      <!-- Form (Kanan) -->
      <div class="contact-form">
        <h3>Send Message</h3>
        <form>
          <input type="text" placeholder="Full Name" required>
          <input type="email" placeholder="Email" required>
          <textarea placeholder="Type your Message..." required></textarea>
          <button type="submit">Send</button>
        </form>
      </div>
    </div>
  </div>
</section>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-container">
      <!-- Logo & Deskripsi -->
      <div class="footer-col">
        <img src="asset/logo.jpeg" alt="Logo Rumah Sakit" class="logo" />
        <p>
          Memberikan pelayanan kesehatan terbaik dengan tenaga profesional 
          dan fasilitas modern untuk masyarakat.
        </p>
        <div class="footer-social">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-youtube"></i></a>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="footer-col">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="#">Beranda</a></li>
          <li><a href="#">Fasilitas & Layanan</a></li>
          <li><a href="#">Berita</a></li>
          <li><a href="#">Kontak</a></li>
        </ul>
      </div>

      <!-- Layanan -->
      <div class="footer-col">
        <h4>Layanan</h4>
        <ul>
          <li><a href="#">Dokter</a></li>
          <li><a href="#">Poliklinik</a></li>
          <li><a href="#">Rawat Inap</a></li>
          <li><a href="#">Rawat Jalan</a></li>
          <li><a href="#">IGD 24 Jam</a></li>
          <li><a href="#">Laboratorium</a></li>
          <li><a href="#">Radiologi</a></li>
          <li><a href="#">Fisioterapi</a></li>
          <li><a href="#">Apotik</a></li>
        </ul>
      </div>

      <!-- Informasi -->
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

  <script src="js/script.js"></script>
</body>
</html>
