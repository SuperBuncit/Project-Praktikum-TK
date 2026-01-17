<?php
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

session_start();
if (!isset($_SESSION['login']))
    exit("Akses Ditolak");

$kelas_id = $_GET['kelas_id'];
$kelas = query("SELECT * FROM kelas WHERE id = $kelas_id")[0];

// Get Students in Class
$siswa = query("SELECT * FROM data_siswa WHERE kelas_id = $kelas_id ORDER BY nama_lengkap ASC");

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Laporan Poin -
        <?= $kelas['nama_kelas']; ?>
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="container mt-4">
        <div class="text-center mb-4">
            <h3>LAPORAN KEDISIPLINAN & POIN SISWA</h3>
            <h4>KELAS
                <?= strtoupper($kelas['nama_kelas']); ?>
            </h4>
            <hr>
        </div>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th width="15%">Total Poin</th>
                    <th>Catatan Terakhir</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($siswa as $s):
                    // Get latest log
                    $last_log = query("SELECT * FROM log_poin WHERE siswa_id = {$s['id']} ORDER BY tanggal DESC LIMIT 1");
                    $catatan = (count($last_log) > 0) ? $last_log[0]['keterangan'] . " (" . date('d/m', strtotime($last_log[0]['tanggal'])) . ")" : "-";
                    ?>
                    <tr>
                        <td class="text-center">
                            <?= $no++; ?>
                        </td>
                        <td>
                            <?= $s['nis']; ?>
                        </td>
                        <td>
                            <?= $s['nama_lengkap']; ?>
                        </td>
                        <td class="text-center fw-bold <?= ($s['poin'] < 50) ? 'text-danger' : ''; ?>">
                            <?= $s['poin']; ?>
                        </td>
                        <td>
                            <?= $catatan; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-5 float-end text-center">
            <p>Jakarta,
                <?= date('d F Y'); ?>
            </p>
            <br><br><br>
            <p>_______________________</p>
            <p>Wali Kelas</p>
        </div>
    </div>
</body>

</html>