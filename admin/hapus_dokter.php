<?php
include("config.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Ambil data dokter untuk hapus file foto
    $res = mysqli_query($conn, "SELECT foto FROM dokter WHERE id=$id");
    $data = mysqli_fetch_assoc($res);

    if ($data && !empty($data['foto']) && file_exists("../" . $data['foto'])) {
        unlink("../" . $data['foto']); // hapus file foto
    }

    // Hapus jadwal dulu (FK constraint)
    mysqli_query($conn, "DELETE FROM jadwal_dokter WHERE dokter_id=$id");

    // Hapus dokter
    mysqli_query($conn, "DELETE FROM dokter WHERE id=$id");
}

header("Location: dokter.php");
exit;
?>
