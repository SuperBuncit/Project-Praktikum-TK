<?php
require_once 'config/config.php';
require_once 'helpers/functions.php';

echo "<h3>Data Kelas Saat Ini:</h3>";
$kelas = query("SELECT * FROM kelas");
echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>Nama Kelas</th></tr>";
foreach ($kelas as $k) {
    echo "<tr><td>{$k['id']}</td><td>{$k['nama_kelas']}</td></tr>";
}
echo "</table>";

echo "<h3>Data Mata Pelajaran Saat Ini:</h3>";
$mapel = query("SELECT * FROM mata_pelajaran");
echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>Kode</th><th>Nama Mapel</th></tr>";
foreach ($mapel as $m) {
    echo "<tr><td>{$m['id']}</td><td>{$m['kode_mapel']}</td><td>{$m['nama_mapel']}</td></tr>";
}
echo "</table>";

echo "<h3>Data Jadwal Pelajaran Saat Ini:</h3>";
$jadwal = query("SELECT j.*, k.nama_kelas, m.nama_mapel, g.nama_lengkap 
                 FROM jadwal_pelajaran j
                 JOIN kelas k ON j.kelas_id = k.id
                 JOIN mata_pelajaran m ON j.mapel_id = m.id
                 JOIN data_guru g ON j.guru_id = g.id
                 ORDER BY j.hari, j.jam_mulai");

echo "<table border='1' cellpadding='5'>
      <tr>
        <th>ID</th>
        <th>Kelas</th>
        <th>Mapel</th>
        <th>Guru</th>
        <th>Hari</th>
        <th>Jam</th>
      </tr>";

foreach ($jadwal as $j) {
    echo "<tr>
            <td>{$j['id']}</td>
            <td>{$j['nama_kelas']}</td>
            <td>{$j['nama_mapel']}</td>
            <td>{$j['nama_lengkap']}</td>
            <td>{$j['hari']}</td>
            <td>{$j['jam_mulai']} - {$j['jam_selesai']}</td>
          </tr>";
}
echo "</table>";
?>