<?php
// Config Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'database');

// Base URL (Sesuaikan dengan folder project di htdocs)
$folderName = basename(dirname(__DIR__));
define('BASE_URL', 'http://localhost/' . $folderName . '/');
define('ASSETS_URL', BASE_URL . 'assets/');

// Set Timezone
date_default_timezone_set('Asia/Jakarta');

// Koneksi Database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>