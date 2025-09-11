<?php
include "config.php";

if (!empty($_FILES['foto']['name'][0])) {
    // Hitung jumlah foto hero yang sudah ada
    $sqlCount = "SELECT COUNT(*) AS total FROM hero";
    $result = $conn->query($sqlCount);
    $row = $result->fetch_assoc();
    $currentCount = $row['total'];

    $allowed = ['jpg','jpeg','png','gif'];
    $uploadDir = "uploads/hero/";

    foreach ($_FILES['foto']['name'] as $key => $name) {
        if ($currentCount >= 3) break; // stop kalau sudah 3 foto

        $tmp = $_FILES['foto']['tmp_name'][$key];
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $newName = time() . "_" . uniqid() . "." . $ext;
            $dest = $uploadDir . $newName;

            if (move_uploaded_file($tmp, $dest)) {
                $stmt = $conn->prepare("INSERT INTO hero (foto) VALUES (?)");
                $stmt->bind_param("s", $dest);
                $stmt->execute();
                $stmt->close();

                $currentCount++;
            }
        }
    }
}

header("Location: hero.php");
exit;
