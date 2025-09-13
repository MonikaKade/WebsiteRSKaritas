<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];

    // Update foto jika ada
    if(isset($_FILES['foto']) && $_FILES['foto']['name'] != ""){
        $fileName = time() . "_" . basename($_FILES["foto"]["name"]);
        $targetFile = "uploads/" . $fileName;

        if(move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFile)){
            // Hapus foto lama
            $old = $conn->query("SELECT foto FROM hero WHERE id=$id")->fetch_assoc();
            if($old && file_exists($old['foto'])){
                unlink($old['foto']);
            }
            $conn->query("UPDATE hero SET foto='$targetFile' WHERE id=$id");
        }
    }

    // Update video jika ada
    if(isset($_FILES['video']) && $_FILES['video']['name'] != ""){
        $fileName = time() . "_" . basename($_FILES["video"]["name"]);
        $targetFile = "uploads/" . $fileName;

        if(move_uploaded_file($_FILES["video"]["tmp_name"], $targetFile)){
            // Hapus video lama
            $old = $conn->query("SELECT video FROM hero WHERE id=$id")->fetch_assoc();
            if($old && file_exists($old['video'])){
                unlink($old['video']);
            }
            $conn->query("UPDATE hero SET video='$targetFile' WHERE id=$id");
        }
    }

    $_SESSION['statusMessage'] = "✅ Hero berhasil diperbarui!";
    header("Location: hero.php");
    exit;
}
?>
