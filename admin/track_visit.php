<?php
require 'config.php'; // koneksi ke database

// ==== Cegah bot agar tidak tercatat ====
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$bot_keywords = ['bot', 'spider', 'crawl', 'curl', 'slurp', 'mediapartners'];
foreach ($bot_keywords as $bot) {
    if (stripos($user_agent, $bot) !== false) exit;
}

// ==== Data kunjungan ====
$ip         = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$page_url   = $_SERVER['REQUEST_URI'] ?? '';
$referrer   = $_SERVER['HTTP_REFERER'] ?? 'Direct';
$time       = date('Y-m-d H:i:s');

// ==== Simpan ke database ====
$stmt = $conn->prepare("INSERT INTO visitor_log (ip_address, user_agent, page, visit_time) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $ip, $user_agent, $page_url, $time);
$stmt->execute();
$stmt->close();
?>
