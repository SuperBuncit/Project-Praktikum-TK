<?php
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

session_start();
if (!isset($_SESSION['login'])) exit("Akses Ditolak");

$kelas_id = $_GET['kelas_id'];
$bulan_input = $_GET['bulan']; // Format YYYY-MM
$bulan_str = date('F Y', strtotime($bulan_input));

$kelas = query("SELECT * FROM kelas WHERE id = $kelas_id")[0];
$siswa = query("SELECT * FROM data_siswa WHERE kelas_id = $kelas_id ORDER BY nama_lengkap ASC");

// Get Absensi Data for this month
$month = date('m', strtotime($bulan_input));
$year = date('Y', strtotime($bulan_input));
$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Rekap Absensi - <?= $bulan_str; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12px; }
        @media print {
            .no-print { display: none; }
            @page { size: landscape; }
        }
        table { font-size: 10px; }
        th, td { text-align: center; vertical-align: middle; padding: 2px !important; }
        .nama { text-align: left; }
    </style>
</head>
<body onload="window.print()">
    <div class="container-fluid mt-2">
        <div class="text-center mb-3">
            <h4 class="mb-0">REKAP ABSENSI SISWA</h4>
            <h5 class="mb-0">KELAS <?= strtoupper($kelas['nama_kelas']); ?> - PERIODE <?= strtoupper($bulan_str); ?></h5>
        </div>

        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th rowspan="2" width="30">No</th>
                    <th rowspan="2" width="150" class="nama">Nama Siswa</th>
                    <th colspan="<?= $days_in_month; ?>">Tanggal</th>
                    <th colspan="4">Total</th>
                </tr>
                <tr>
                    <?php for($d=1; $d<=$days_in_month; $d++): ?>
                        <th width="18"><?= $d; ?></th>
                    <?php endfor; ?>
                    <th width="25">H</th>
                    <th width="25">S</th>
                    <th width="25">I</th>
                    <th width="25">A</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; foreach($siswa as $s): 
                    $h=0; $sa=0; $i=0; $a=0;
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td class="nama"><?= $s['nama_lengkap']; ?></td>
                    <?php for($d=1; $d<=$days_in_month; $d++): 
                        $date_check = sprintf("%s-%s-%02d", $year, $month, $d);
                        // Query check per day (Not efficient but works for small scale)
                        $status_cek = query("SELECT status FROM absensi WHERE siswa_id = {$s['id']} AND tanggal = '$date_check'");
                        
                        $code = "";
                        if(count($status_cek) > 0) {
                            $st = $status_cek[0]['status'];
                            if($st == 'Hadir') { $code = "."; $h++; }
                            elseif($st == 'Sakit') { $code = "S"; $sa++; }
                            elseif($st == 'Izin') { $code = "I"; $i++; }
                            elseif($st == 'Alpa') { $code = "A"; $a++; }
                        }
                    ?>
                        <td><?= $code; ?></td>
                    <?php endfor; ?>
                    <td><b><?= $h; ?></b></td>
                    <td><b><?= $sa; ?></b></td>
                    <td><b><?= $i; ?></b></td>
                    <td><b><?= $a; ?></b></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-4">
            <small>Keterangan: (.) Hadir, (S) Sakit, (I) Izin, (A) Alpa</small>
        </div>
    </div>
</body>
</html>
