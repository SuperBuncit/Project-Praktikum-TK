<?php
require_once 'config/config.php';

function check_columns($table)
{
    global $conn;
    $result = mysqli_query($conn, "SHOW COLUMNS FROM $table");
    $columns = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $columns[] = $row['Field'];
    }
    return $columns;
}

$siswa_cols = check_columns('data_siswa');
$guru_cols = check_columns('data_guru');

echo "<h3>Status Kolom Database</h3>";

echo "<strong>Tabel data_siswa:</strong> ";
if (in_array('foto', $siswa_cols)) {
    echo "<span style='color:green'>Kolom 'foto' SUDAH ADA.</span>";
} else {
    echo "<span style='color:red'>Kolom 'foto' BELUM ADA.</span>";
}
echo "<br>";

echo "<strong>Tabel data_guru:</strong> ";
if (in_array('foto', $guru_cols)) {
    echo "<span style='color:green'>Kolom 'foto' SUDAH ADA.</span>";
} else {
    echo "<span style='color:red'>Kolom 'foto' BELUM ADA.</span>";
}
?>