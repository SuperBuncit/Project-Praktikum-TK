<?php
require_once 'config/config.php';
require_once 'helpers/functions.php';

echo "<h3>Membersihkan Data Kelas Ganda...</h3>";

// 1. Cari nama kelas yang duplikat
$query = "SELECT nama_kelas, GROUP_CONCAT(id ORDER BY id ASC) as ids, COUNT(*) as count 
          FROM kelas 
          GROUP BY nama_kelas 
          HAVING count > 1";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        $nama_kelas = $row['nama_kelas'];
        $ids_array = explode(',', $row['ids']);

        // ID yang akan DIPERTAHANKAN (ID terlama/terkecil)
        $keep_id = $ids_array[0];

        // ID yang akan DIHAPUS
        $delete_ids_array = array_slice($ids_array, 1);
        $delete_ids_str = implode(',', $delete_ids_array);

        echo "<li>Memproses Kelas '<strong>$nama_kelas</strong>' (IDs: {$row['ids']})";
        echo "<ul>";
        echo "<li>Menyimpan ID: $keep_id</li>";
        echo "<li>Menghapus ID: $delete_ids_str</li>";

        // 2. Update tabel relasi (Pindahkan siswa/jadwal ke ID yang disimpan)
        // Update Data Siswa
        mysqli_query($conn, "UPDATE data_siswa SET kelas_id = $keep_id WHERE kelas_id IN ($delete_ids_str)");
        // Update Jadwal
        mysqli_query($conn, "UPDATE jadwal_pelajaran SET kelas_id = $keep_id WHERE kelas_id IN ($delete_ids_str)");

        // 3. Hapus kelas ganda
        $delete_query = "DELETE FROM kelas WHERE id IN ($delete_ids_str)";
        if (mysqli_query($conn, $delete_query)) {
            echo "<li style='color: green;'>Sukses hapus duplikat.</li>";
        } else {
            echo "<li style='color: red;'>Gagal hapus: " . mysqli_error($conn) . "</li>";
        }
        echo "</ul></li><br>";
    }
    echo "</ul>";
    echo "<h4 style='color: green;'>Pembersihan Selesai! Duplikat telah dihapus dan data terkait telah disatukan.</h4>";
} else {
    echo "<h4 style='color: blue;'>Tidak ditemukan data kelas ganda. Database bersih.</h4>";
}

echo "<a href='modules/kelas/index.php'>Kembali ke Data Kelas</a>";
?>