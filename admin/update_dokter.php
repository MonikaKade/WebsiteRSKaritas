<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $izin = $_POST['izin'];
    $spesialis = $_POST['spesialis'];
    $poli_id = $_POST['poli_id'];

    // Foto
    $foto = $_POST['foto_lama'];
    if (!empty($_FILES['foto']['name'])) {
        $targetDir = "uploads/dokter/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $fileName  = basename($_FILES['foto']['name']);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
            $foto = $targetFile;
        }
    }

    // Update dokter
    $sql = "UPDATE dokter SET 
                nama='$nama',
                izin='$izin',
                spesialis='$spesialis',
                poli_id='$poli_id',
                foto='$foto'
            WHERE id=$id";
    mysqli_query($conn, $sql);

    // Hapus jadwal lama
    mysqli_query($conn, "DELETE FROM jadwal_dokter WHERE dokter_id=$id");

    // Insert jadwal baru
    if (!empty($_POST['hari'])) {
        foreach ($_POST['hari'] as $i => $hari) {
            $jam_mulai   = $_POST['jam_mulai'][$i];
            $jam_selesai = $_POST['jam_selesai'][$i];
            if ($hari && $jam_mulai && $jam_selesai) {
                mysqli_query($conn, 
                    "INSERT INTO jadwal_dokter (dokter_id, hari, jam_mulai, jam_selesai) 
                     VALUES ('$id','$hari','$jam_mulai','$jam_selesai')");
            }
        }
    }

    header("Location: dokter.php?updated=1");
    exit;
}
?>
