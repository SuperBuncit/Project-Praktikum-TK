<?php
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

session_start();
if (!isset($_SESSION['login']))
    exit("Akses Ditolak");

$kelas_id = $_GET['kelas_id'];
$kelas = query("SELECT * FROM kelas WHERE id = $kelas_id")[0];
$siswa = query("SELECT * FROM data_siswa WHERE kelas_id = $kelas_id ORDER BY nama_lengkap ASC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Data Siswa -
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
            <h3>LAPORAN DATA SISWA</h3>
            <h4>TK MODERN TAHUN AJARAN
                <?= date('Y'); ?>
            </h4>
            <hr>
        </div>

        <p><strong>Kelas:</strong>
            <?= $kelas['nama_kelas']; ?>
        </p>
        <p><strong>Wali Kelas:</strong> - </p>

        <table class="table table-bordered table-sm">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th>NIS</th>
                    <th>Nama Lengkap</th>
                    <th>L/P</th>
                    <th>Tempat, Tanggal Lahir</th>
                    <th>Nama Wali</th>
                    <th>No. HP Wali</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($siswa as $s): ?>
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
                        <td class="text-center">
                            <?= $s['jenis_kelamin']; ?>
                        </td>
                        <td>
                            <?= $s['tempat_lahir'] . ', ' . date('d-m-Y', strtotime($s['tanggal_lahir'])); ?>
                        </td>
                        <td>
                            <?= $s['nama_wali']; ?>
                        </td>
                        <td>
                            <?= $s['no_hp_wali']; ?>
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
            <p>Kepala Sekolah</p>
        </div>
    </div>
</body>

</html>