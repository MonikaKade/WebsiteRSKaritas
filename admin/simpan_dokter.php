<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $izin = $_POST['izin'];
    $spesialis = $_POST['spesialis'];
    $poli_id = $_POST['poli_id'];

   // Upload foto
$foto = null;
if (!empty($_FILES['foto']['name'])) {
    $foto = "uploads/dokter/" . basename($_FILES['foto']['name']);
    $targetFile = $targetDir . basename($_FILES['foto']['name']);

    // pindahkan file ke folder uploads/dokter/
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
        // simpan path sesuai lokasi sebenarnya
        $foto = $targetFile; 
    }
}


    // Simpan dokter
    $sql = "INSERT INTO dokter (nama, izin, spesialis, foto, poli_id) 
            VALUES ('$nama','$izin','$spesialis','$foto','$poli_id')";
    mysqli_query($conn, $sql);

    // Ambil ID dokter terakhir
    $dokter_id = mysqli_insert_id($conn);

    // Simpan jadwal (kalau diisi)
    if (!empty($_POST['hari']) && !empty($_POST['jam_mulai']) && !empty($_POST['jam_selesai'])) {
        $hari = $_POST['hari'];
        $jam_mulai = $_POST['jam_mulai'];
        $jam_selesai = $_POST['jam_selesai'];

        $sql_jadwal = "INSERT INTO jadwal_dokter (dokter_id, hari, jam_mulai, jam_selesai)
                       VALUES ('$dokter_id','$hari','$jam_mulai','$jam_selesai')";
        mysqli_query($conn, $sql_jadwal);
    }

    // Redirect balik ke form dokter
    header("Location: dokter.php");
    exit;
}
?>
