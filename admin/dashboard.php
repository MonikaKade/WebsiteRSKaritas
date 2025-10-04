<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f5f6fa;
    }
    .sidebar {
      height: 100vh;
      background: #2C3E50; /* Warna baru */
      color: white;
      padding: 20px;
      position: fixed;
      width: 250px;
    }
    .sidebar h4 {
      text-align: center;
      margin-bottom: 30px;
    }
    .sidebar a {
      display: block;
      padding: 10px 15px;
      margin: 8px 0;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      transition: 0.3s;
    }
    .sidebar a:hover {
      background: rgba(255,255,255,0.2);
    }
    .main-content {
      margin-left: 250px;
      padding: 20px;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h4>👨‍⚕️ RS Karitas</h4>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="dashboard.php?page=dokter">👨‍⚕️ Kelola Dokter</a>
    <a href="dashboard.php?page=hero">🖼️ Hero Section</a>
    <a href="dashboard.php?page=akun">⚙️ Pengaturan Akun</a>
    <a href="logout.php">🚪 Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container">
      <?php
      if (isset($_GET['page'])) {
          $page = $_GET['page'];
          if ($page == "dokter") {
              include "dokter.php";
          } elseif ($page == "hero") {
              include "hero.php";
          } elseif ($page == "akun") {
              include "setting.php";
          } else {
              echo "<h2>Halaman tidak ditemukan</h2>";
          }
      } else {
          echo "<h2>Halo, ".$_SESSION['admin']." 👋</h2>";
          echo "<p>Selamat datang di dashboard admin RS Karitas.</p>";
      }
      ?>
    </div>
  </div>
</body>
</html>
