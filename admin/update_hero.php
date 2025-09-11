<?php
include "config.php";

if (isset($_POST['id']) && !empty($_FILES['foto']['name'])) {
    $id = intval($_POST['id']);

    // Ambil data lama
    $sql = "SELECT foto FROM hero WHERE id = $id";
    $result = $conn->query($sql);
    $oldFoto = "";
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $oldFoto = $row['foto'];
    }

    $allowed = ['jpg','jpeg','png','gif'];
    $uploadDir = "uploads/hero/";

    $name = $_FILES['foto']['name'];
    $tmp  = $_FILES['foto']['tmp_name'];
    $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    if (in_array($ext, $allowed)) {
        $newName = time() . "_" . uniqid() . "." . $ext;
        $dest = $uploadDir . $newName;

        if (move_uploaded_file($tmp, $dest)) {
            // Update database
            $stmt = $conn->prepare("UPDATE hero SET foto=? WHERE id=?");
            $stmt->bind_param("si", $dest, $id);
            $stmt->execute();
            $stmt->close();

            // Hapus file lama
            if (!empty($oldFoto) && file_exists($oldFoto)) {
                unlink($oldFoto);
            }
        }
    }
}

// 🔥 Redirect langsung ke dashboard
header("Location: dashboard.php?status=success");
exit;
