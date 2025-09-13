<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];

    // Hapus foto fisik
    $old = $conn->query("SELECT foto FROM hero WHERE id=$id")->fetch_assoc();
    if ($old && file_exists($old['foto'])) {
        unlink($old['foto']);
    }

    $conn->query("DELETE FROM hero WHERE id=$id");
}

header("Location: hero.php");
exit;
