<?php
session_start();
include("config.php");

// Cek login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Tambah dokter baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $izin = $_POST['izin'];
    $spesialis = $_POST['spesialis'];

    // upload foto
    $foto = null;
    if (!empty($_FILES['foto']['name'])) {
        $targetDir = "uploads/dokter/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $foto = $targetDir . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
    }

    $stmt = $conn->prepare("INSERT INTO dokter (nama, izin, spesialis, foto) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $izin, $spesialis, $foto);
    $stmt->execute();
    $stmt->close();

    header("Location: dokter.php");
    exit;
}

// Hapus dokter
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM dokter WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: dokter.php");
    exit;
}

// Ambil semua data dokter
$result = $conn->query("SELECT * FROM dokter");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Dokter</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 10px; text-align: left; }
        img { max-width: 80px; }
        form { margin-top: 20px; }
        input, select { padding: 8px; margin: 5px; width: 100%; }
        button { padding: 8px 15px; background: #2c3e50; color: #fff; border: none; cursor: pointer; }
        button:hover { background: #145A86; }
    </style>
</head>
<body>
    <h1>Kelola Dokter</h1>
    <p><a href="dashboard.php">⬅ Kembali ke Dashboard</a></p>

    <h2>Tambah Dokter Baru</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="nama" placeholder="Nama Dokter" required><br>
        <input type="text" name="izin" placeholder="Nomor Izin" required><br>
        <input type="text" name="spesialis" placeholder="Spesialis" required><br>
        <input type="file" name="foto" accept="image/*"><br>
        <button type="submit" name="tambah">Tambah</button>
    </form>

    <h2>Daftar Dokter</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Izin</th>
            <th>Spesialis</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= htmlspecialchars($row['nama']); ?></td>
            <td><?= htmlspecialchars($row['izin']); ?></td>
            <td><?= htmlspecialchars($row['spesialis']); ?></td>
            <td>
                <?php if ($row['foto']): ?>
                    <img src="<?= $row['foto']; ?>" alt="Foto Dokter">
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
            <td>
                <a href="dokter.php?hapus=<?= $row['id']; ?>" onclick="return confirm('Yakin hapus dokter ini?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
