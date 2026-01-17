<?php
session_start();
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

check_login();

// Hanya siswa yang boleh akses halaman ini
if ($_SESSION['role'] != 'siswa') {
    header("Location: " . base_url('dashboard.php'));
    exit;
}

$user_id = $_SESSION['user_id'];
// Get Siswa ID from User ID
$siswa = query("SELECT * FROM data_siswa WHERE user_id = $user_id")[0];
$siswa_id = $siswa['id'];

// Get Log Poin
$query = "SELECT log_poin.*, users.username as pencatat
          FROM log_poin 
          LEFT JOIN users ON log_poin.dicatat_oleh = users.id 
          WHERE log_poin.siswa_id = $siswa_id
          ORDER BY log_poin.tanggal DESC";
$history = query($query);

require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Poin Saya</h1>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Poin Saat Ini</div>
                        <div class="h1 mb-0 font-weight-bold text-gray-800">
                            <?= $siswa['poin']; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-star fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Riwayat Catatan</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Poin</th>
                        <th>Keterangan</th>
                        <th>Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $row): ?>
                        <tr>
                            <td>
                                <?= date('d/m/Y', strtotime($row['tanggal'])); ?>
                            </td>
                            <td>
                                <?php if ($row['jenis'] == 'Prestasi'): ?>
                                    <span class="badge bg-success">Prestasi</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Pelanggaran</span>
                                <?php endif; ?>
                            </td>
                            <td class="<?= ($row['jumlah_poin'] > 0) ? 'text-success' : 'text-danger'; ?> fw-bold">
                                <?= ($row['jumlah_poin'] > 0) ? '+' : ''; ?>
                                <?= $row['jumlah_poin']; ?>
                            </td>
                            <td>
                                <?= $row['keterangan']; ?>
                            </td>
                            <td>
                                <?= ucfirst($row['pencatat']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>