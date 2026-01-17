<?php
require_once 'config/config.php';

// Check if admin
session_start();
// Optional: restrict to admin only if needed, but for setup we might allow it open or just warn
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     die("Akses ditolak. Harap login sebagai Admin.");
// }

$sqlFile = 'dummy_data.sql';

if (!file_exists($sqlFile)) {
    die("File $sqlFile tidak ditemukan.");
}

$sqlContent = file_get_contents($sqlFile);
$queries = explode(';', $sqlContent);

echo "<h3>Memproses Data Dummy...</h3>";
echo "<ul>";

foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query)) {
        // Skip USE command as we already connected
        if (strpos(strtoupper($query), 'USE ') === 0) {
            continue;
        }

        try {
            if (mysqli_query($conn, $query)) {
                echo "<li style='color: green;'>Berhasil: " . substr($query, 0, 50) . "...</li>";
            } else {
                // Ignore duplicate errors for INSERT IGNORE
                if (mysqli_errno($conn) == 1062) { // Duplicate entry
                    echo "<li style='color: orange;'>Skipped (Duplicate): " . substr($query, 0, 50) . "...</li>";
                } else {
                    echo "<li style='color: red;'>Gagal: " . mysqli_error($conn) . "</li>";
                }
            }
        } catch (Exception $e) {
            echo "<li style='color: red;'>Error: " . $e->getMessage() . "</li>";
        }
    }
}

echo "</ul>";
echo "<h4>Selesai! Silakan cek kembali fitur laporan.</h4>";
echo "<a href='modules/laporan/index.php'>Kembali ke Laporan</a>";
?>