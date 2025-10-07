<?php
session_start();
require 'config.php';

// 🔐 Cek login admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// === Statistik dasar (buat dashboard utama saja) ===
$total_res = $conn->query("SELECT COUNT(*) AS total FROM visitor_log");
$total = $total_res->fetch_assoc()['total'] ?? 0;

$unique_res = $conn->query("SELECT COUNT(DISTINCT ip_address) AS unik FROM visitor_log");
$unique = $unique_res->fetch_assoc()['unik'] ?? 0;

$pages_res = $conn->query("SELECT page, COUNT(*) AS hits FROM visitor_log GROUP BY page ORDER BY hits DESC");
$pages = [];
$hits = [];
while ($row = $pages_res->fetch_assoc()) {
    $pages[] = $row['page'];
    $hits[] = $row['hits'];
}

$shareCount = 120;
$likeCount  = 340;

// Ambil parameter page dengan default 'home' untuk dashboard utama
$page = $_GET['page'] ?? 'home';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin RS Karitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f6fa;
            margin: 0;
        }
        .sidebar {
            height: 100vh;
            background: #2C3E50;
            color: white;
            padding: 20px;
            position: fixed;
            width: 250px;
            overflow-y: auto;
        }
        .sidebar .brand {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        .sidebar .brand img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }
        .sidebar .brand h4 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
            margin: 0;
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
        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255, 255, 255, 0.2);
        }
        .main-content {
            margin-left: 250px;
            padding: 25px;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 20px;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="brand">
        <img src="../asset/logo.jpeg" alt="Logo RS Karitas">
        <h4>RS Karitas Weetabula</h4>
    </div>

    <a href="dashboard.php" class="<?= $page === 'home' ? 'active' : '' ?>">🏠 Dashboard</a>
    <a href="dashboard.php?page=dokter" class="<?= $page === 'dokter' ? 'active' : '' ?>">👨‍⚕️ Kelola Dokter</a>
    <a href="dashboard.php?page=hero" class="<?= $page === 'hero' ? 'active' : '' ?>">🖼️ Hero Section</a>
    <a href="dashboard.php?page=akun" class="<?= $page === 'akun' ? 'active' : '' ?>">⚙️ Pengaturan Akun</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">

        <?php
        // Logika routing halaman
        if ($page === 'akun') {
            include 'setting.php';
        } elseif ($page === 'dokter') {
            include 'dokter.php';
        } elseif ($page === 'hero') {
            include 'hero.php';
        } else {
            // Default: Dashboard utama
        ?>
            <h2>Halo, <?= htmlspecialchars($_SESSION['admin']) ?> 👋</h2>
            <p>Selamat datang di dashboard analitik RS Karitas.</p>

            <div class="stats-grid mb-4">
                <div class="card p-3 text-center">
                    <h6>Total Pengunjung</h6>
                    <h2><?= $total ?></h2>
                </div>
                <div class="card p-3 text-center">
                    <h6>Pengunjung Unik</h6>
                    <h2><?= $unique ?></h2>
                </div>
                <div class="card p-3 text-center">
                    <h6>Total Share</h6>
                    <h2><?= $shareCount ?></h2>
                </div>
                <div class="card p-3 text-center">
                    <h6>Total Like</h6>
                    <h2><?= $likeCount ?></h2>
                </div>
            </div>

            <div class="card p-3 mb-4">
                <h5>Grafik Kunjungan per Halaman</h5>
                <canvas id="visitorChart"></canvas>
            </div>

            <script>
                const ctx = document.getElementById('visitorChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode($pages) ?>,
                        datasets: [{
                            label: 'Jumlah Kunjungan',
                            data: <?= json_encode($hits) ?>,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            </script>
        <?php
        }
        ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
