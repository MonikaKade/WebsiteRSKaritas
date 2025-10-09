<?php
// Guard untuk hindari multiple execution
if (defined('CONFIG_LOADED')) {
    return;
}
define('CONFIG_LOADED', true);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "127.0.0.1";
$port = 3307;
$user = "root";
$pass = "";
$db   = "rsadmin";

try {
    $conn = new mysqli($host, $user, $pass, $db, $port);
    if ($conn->connect_error) {
        throw new mysqli_sql_exception("Koneksi gagal: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log("Config Error: " . $e->getMessage());
    die("Koneksi database gagal. Periksa XAMPP MySQL (port 3307).");
}

// Fungsi escape (hanya jika belum ada)
if (!function_exists('escape')) {
    function escape($string) {
        global $conn;
        return $conn ? $conn->real_escape_string($string) : htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}
?>
