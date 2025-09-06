<?php
include("config.php");

// Aktifkan error reporting biar kalau ada error tidak blank
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $izin = $_POST['izin'];
    $spesialis = $_POST['spesialis'];
    $poli_id = $_POST['poli_id'];

    // Upload foto
    $foto = null;
    if (!empty($_FILES['foto']['name'])) {
        $targetDir = "uploads/dokter/"; // definisikan folder
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // bikin folder kalau belum ada
        }

        $fileName  = basename($_FILES['foto']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
            $foto = $targetFile;
        }
    }

    // Simpan dokter
    $sql = "INSERT INTO dokter (nama, izin, spesialis, foto, poli_id) 
            VALUES ('$nama','$izin','$spesialis','$foto','$poli_id')";
    if (!mysqli_query($conn, $sql)) {
        die("Error insert dokter: " . mysqli_error($conn));
    }

    // Ambil ID dokter terakhir
    $dokter_id = mysqli_insert_id($conn);

    // Simpan jadwal (kalau ada)
    if (!empty($_POST['hari'])) {
        foreach ($_POST['hari'] as $i => $hari) {
            $jam_mulai   = $_POST['jam_mulai'][$i];
            $jam_selesai = $_POST['jam_selesai'][$i];

            if ($hari && $jam_mulai && $jam_selesai) {
                $sqlJadwal = "INSERT INTO jadwal_dokter (dokter_id, hari, jam_mulai, jam_selesai) 
                              VALUES ('$dokter_id', '$hari', '$jam_mulai', '$jam_selesai')";
                if (!mysqli_query($conn, $sqlJadwal)) {
                    die("Error insert jadwal: " . mysqli_error($conn));
                }
            }
        }
    }

    // Redirect biar tidak blank
    header("Location: dokter.php?success=1");
    exit;
}
?>
