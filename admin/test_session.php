<?php
session_start();

if (!isset($_SESSION['test'])) {
    $_SESSION['test'] = "Hello, session bekerja!";
    echo "Session baru dibuat.";
} else {
    echo "Session sudah ada: " . $_SESSION['test'];
}
?>
