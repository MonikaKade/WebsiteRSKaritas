<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif; 
            margin: 0;
            background: #f8f9fa;
        }
        header {
            background: #2c3e50;
            color: white;
            padding: 15px;
            text-align: center;
        }
        main {
            padding: 20px;
        }
        h1 { color: #2c3e50; }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            margin: 10px 0;
        }
        a {
            display: inline-block;
            padding: 10px 15px;
            background: #2980b9;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s;
        }
        a:hover {
            background: #1f618d;
        }
        .logout {
            background: #c0392b;
        }
        .logout:hover {
            background: #922b21;
        }
    </style>
</head>
<body>
    <header>
        <h2>Dashboard Admin RS Karitas</h2>
    </header>
    <main>
        <h1>Halo, <?= $_SESSION['admin']; ?> 👋</h1>
        <p>Selamat datang di panel admin. Silakan pilih menu di bawah:</p>
        <ul>
            <li><a href="dokter.php">👨‍⚕️ Kelola Dokter</a></li>
            <li><a href="hero.php">🖼️ Kelola Hero Section</a></li>
            <li><a href="logout.php" class="logout">🚪 Logout</a></li>
        </ul>
    </main>
</body>
</html>
