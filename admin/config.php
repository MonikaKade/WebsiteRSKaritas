<?php
$host = "127.0.0.1";
$port = 3307;
$user = "root";
$pass = "";
$db   = "rsadmin";

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

?>
