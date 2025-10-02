<?php
include "config.php";

$type = $_POST['type'] ?? "";
$fotoPath = "";
$videoPath = "";

// Cek koneksi database
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Simpan Foto
if (($type === "foto1" || $type === "foto2") && isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $fotoName = time() . "_" . basename($_FILES['foto']['name']);
    $targetFoto = "uploads/hero/" . $fotoName;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFoto)) {
        $fotoPath = $targetFoto;
    } else {
        die("Gagal upload foto.");
    }
}

// Simpan Video
if ($type === "video" && isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
    $videoName = time() . "_" . basename($_FILES['video']['name']);
    $targetVideo = "uploads/hero/" . $videoName;

    if (move_uploaded_file($_FILES['video']['tmp_name'], $targetVideo)) {
        $videoPath = $targetVideo;
    } else {
        die("Gagal upload video.");
    }
}

// Masukkan ke database jika ada file yang diupload
if ($fotoPath !== null || $videoPath !== null) {
    $stmt = $conn->prepare("INSERT INTO hero (foto, video) VALUES (?, ?)");
    if (!$stmt) {
        die("Prepare gagal: " . $conn->error);
    }

    $stmt->bind_param("ss", $fotoPath, $videoPath);

    if ($stmt->execute()) {
        // sukses
        header("Location: hero.php?status=sukses");
        exit;
    } else {
        die("Eksekusi gagal: " . $stmt->error);
    }
} else {
    die("Tidak ada file yang diupload.");
}
