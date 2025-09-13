<?php
include "config.php";

$type = $_POST['type'] ?? "";
$fotoPath = "";
$videoPath = "";

// Simpan Foto
if (($type === "foto1" || $type === "foto2") && !empty($_FILES['foto']['name'])) {
    $fotoName = time() . "_" . $_FILES['foto']['name'];
    $targetFoto = "uploads/" . $fotoName;
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFoto)) {
        $fotoPath = $targetFoto;
    }
}

// Simpan Video
if ($type === "video" && !empty($_FILES['video']['name'])) {
    $videoName = time() . "_" . $_FILES['video']['name'];
    $targetVideo = "uploads/" . $videoName;
    if (move_uploaded_file($_FILES['video']['tmp_name'], $targetVideo)) {
        $videoPath = $targetVideo;
    }
}

if ($fotoPath || $videoPath) {
    $stmt = $conn->prepare("INSERT INTO hero (foto, video) VALUES (?, ?)");
    $stmt->bind_param("ss", $fotoPath, $videoPath);
    $stmt->execute();
}

header("Location: hero.php");
exit;
