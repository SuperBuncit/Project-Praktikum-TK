<?php
require_once 'config/config.php';
require_once 'helpers/functions.php';

echo "<h2>Pembersihan Database Universal</h2>";
echo "<a href='dashboard.php'>Kembali ke Dashboard</a><hr>";

// --- 1. CLEANUP MATA PELAJARAN ---
echo "<h3>1. Membersihkan Mata Pelajaran Ganda...</h3>";
$query_mapel = "SELECT nama_mapel, GROUP_CONCAT(id ORDER BY id ASC) as ids, COUNT(*) as count 
                FROM mata_pelajaran 
                GROUP BY nama_mapel 
                HAVING count > 1";
$result_mapel = mysqli_query($conn, $query_mapel);

if (mysqli_num_rows($result_mapel) > 0) {
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result_mapel)) {
        $nama_mapel = $row['nama_mapel'];
        $ids_array = explode(',', $row['ids']);
        $keep_id = $ids_array[0];
        $delete_ids_array = array_slice($ids_array, 1);
        $delete_ids_str = implode(',', $delete_ids_array);

        echo "<li>Mapel '<strong>$nama_mapel</strong>' (Keep: $keep_id, Delete: $delete_ids_str)";

        // Update References in Jadwal
        mysqli_query($conn, "UPDATE jadwal_pelajaran SET mapel_id = $keep_id WHERE mapel_id IN ($delete_ids_str)");

        // Delete Duplicates
        if (mysqli_query($conn, "DELETE FROM mata_pelajaran WHERE id IN ($delete_ids_str)")) {
            echo " <span style='color:green'>[OK]</span>";
        } else {
            echo " <span style='color:red'>[Fail: " . mysqli_error($conn) . "]</span>";
        }
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color:blue'>Mapel bersih.</p>";
}

// --- 2. CLEANUP JADWAL PELAJARAN ---
echo "<h3>2. Membersihkan Jadwal Pelajaran Ganda...</h3>";
// Kriteria ganda: Kelas sama, Mapel sama, Guru sama, Hari sama, Jam Mulai sama
$query_jadwal = "SELECT kelas_id, mapel_id, guru_id, hari, jam_mulai, 
                 GROUP_CONCAT(id ORDER BY id ASC) as ids, COUNT(*) as count 
                 FROM jadwal_pelajaran 
                 GROUP BY kelas_id, mapel_id, guru_id, hari, jam_mulai
                 HAVING count > 1";

$result_jadwal = mysqli_query($conn, $query_jadwal);

if (mysqli_num_rows($result_jadwal) > 0) {
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result_jadwal)) {
        $ids_array = explode(',', $row['ids']);
        $keep_id = $ids_array[0];
        $delete_ids_array = array_slice($ids_array, 1);
        $delete_ids_str = implode(',', $delete_ids_array);

        echo "<li>Jadwal ID ($delete_ids_str) adalah duplikat dari ID ($keep_id)";

        // Delete Duplicates
        if (mysqli_query($conn, "DELETE FROM jadwal_pelajaran WHERE id IN ($delete_ids_str)")) {
            echo " <span style='color:green'>[Deleted]</span>";
        } else {
            echo " <span style='color:red'>[Fail]</span>";
        }
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color:blue'>Jadwal bersih.</p>";
}

echo "<hr><h4>Selesai! Silakan cek kembali fitur yang bermasalah.</h4>";
?>