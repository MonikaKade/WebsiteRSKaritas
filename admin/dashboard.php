<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

require 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Statistik dasar
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
    :root {
        --primary: #0a74da;
        --primary-light: #3ba7ff;
        --primary-dark: #064a8c;
        --text: #333;
        --light-text: #777;
        --bg: #f8fbff;
        --white: #fff;
        --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        --radius: 14px;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: var(--bg);
        color: var(--text);
        margin: 0;
    }

    /* Sidebar */
    .sidebar {
        height: 100vh;
        background: var(--primary);
        color: var(--white);
        padding: 25px 20px;
        position: fixed;
        width: 250px;
        box-shadow: var(--shadow);
        border-top-right-radius: var(--radius);
        border-bottom-right-radius: var(--radius);
    }

    .sidebar .brand {
        display: flex;
        align-items: center;
        margin-bottom: 40px;
    }

    .sidebar .brand img {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        margin-right: 10px;
    }

    .sidebar .brand h4 {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
    }

    .sidebar a {
        display: block;
        padding: 12px 16px;
        color: #eaf2ff;
        text-decoration: none;
        border-radius: 8px;
        margin: 8px 0;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .sidebar a:hover,
    .sidebar a.active {
        background: var(--primary-light);
        color: var(--white);
        transform: translateX(5px);
    }

    /* Main Content */
    .main-content {
        margin-left: 250px;
        padding: 35px;
    }

    /* Header */
    .dashboard-header h2 {
        font-weight: 600;
        color: var(--primary-dark);
    }

    .dashboard-header p {
        color: var(--light-text);
    }

    /* Statistik */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-top: 25px;
    }

    .stat-card {
        background: var(--white);
        border-radius: var(--radius);
        padding: 25px;
        text-align: center;
        box-shadow: var(--shadow);
        transition: 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        font-size: 32px;
        color: var(--primary-light);
        margin-bottom: 10px;
    }

    .stat-title {
        font-size: 0.9rem;
        color: var(--light-text);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-dark);
    }

    /* Chart */
    .chart-card {
        background: var(--white);
        border-radius: var(--radius);
        padding: 25px;
        margin-top: 30px;
        box-shadow: var(--shadow);
    }

    .chart-card h5 {
        color: var(--primary-dark);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            position: relative;
            width: 100%;
            height: auto;
            border-radius: 0;
        }

        .main-content {
            margin-left: 0;
            padding: 20px;
        }
    }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <img src="../asset/logo.jpeg" alt="Logo RS Karitas">
            <h4>RS Karitas</h4>
        </div>
        <a href="dashboard.php" class="<?= $page === 'home' ? 'active' : '' ?>">🏠 Dashboard</a>
        <a href="dashboard.php?page=dokter" class="<?= $page === 'dokter' ? 'active' : '' ?>">👨‍⚕️ Kelola Dokter</a>
        <a href="dashboard.php?page=hero" class="<?= $page === 'hero' ? 'active' : '' ?>">🖼️ Hero Section</a>
        <a href="dashboard.php?page=akun" class="<?= $page === 'akun' ? 'active' : '' ?>">⚙️ Pengaturan Akun</a>
        <hr style="border-color: rgba(255,255,255,0.2);">
        <a href="logout.php">🚪 Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <?php
    if ($page === 'dokter') {
        include file_exists('dokter.php') ? 'dokter.php' : '';
    } elseif ($page === 'hero') {
        include file_exists('hero.php') ? 'hero.php' : '';
    } elseif ($page === 'akun') {
        include file_exists('setting.php') ? 'setting.php' : '';
    } else {
    ?>
            <div class="dashboard-header mb-4">
                <h2>Halo, <?= htmlspecialchars($_SESSION['admin']); ?> 👋</h2>
                <p>Selamat datang di <strong>Dashboard Analitik RS Karitas</strong>.</p>
            </div>

            <!-- Statistik -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-title">Total Pengunjung</div>
                    <div class="stat-value"><?= $total ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-title">Pengunjung Unik</div>
                    <div class="stat-value"><?= $unique ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-title">Total Share</div>
                    <div class="stat-value"><?= $shareCount ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-title">Total Like</div>
                    <div class="stat-value"><?= $likeCount ?></div>
                </div>
            </div>

            <!-- Grafik -->
            <div class="chart-card">
                <h5 class="mb-3">📊 Grafik Kunjungan per Halaman</h5>
                <canvas id="visitorChart" height="100"></canvas>
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
                        backgroundColor: 'rgba(10, 116, 218, 0.7)',
                        borderColor: 'var(--primary-dark)',
                        borderWidth: 1,
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#eee'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            </script>
            <?php } ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>