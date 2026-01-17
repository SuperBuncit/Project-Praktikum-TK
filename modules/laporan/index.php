<?php
session_start();
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

check_login();
check_role(['admin', 'guru']);

$kelas_list = query("SELECT * FROM kelas ORDER BY nama_kelas ASC");

require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Pusat Laporan</h1>
</div>

<div class="row">
    <!-- Laporan Siswa -->
    <div class="col-md-4 mb-4">
        <div class="card shadow h-100 border-left-primary">
            <div class="card-body">
                <h5 class="card-title fw-bold text-primary"><i class="fas fa-users me-2"></i>Laporan Data Siswa</h5>
                <p class="card-text text-muted">Cetak daftar siswa per kelas.</p>
                <form action="cetak_siswa.php" method="get" target="_blank">
                    <div class="mb-3">
                        <select name="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($kelas_list as $k): ?>
                                <option value="<?= $k['id']; ?>">
                                    <?= $k['nama_kelas']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-print me-2"></i>Cetak
                        PDF</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Laporan Absensi -->
    <div class="col-md-4 mb-4">
        <div class="card shadow h-100 border-left-success">
            <div class="card-body">
                <h5 class="card-title fw-bold text-success"><i class="fas fa-calendar-check me-2"></i>Rekap Absensi</h5>
                <p class="card-text text-muted">Cetak rekap kehadiran bulanan.</p>
                <form action="cetak_absensi.php" method="get" target="_blank">
                    <div class="mb-3">
                        <select name="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($kelas_list as $k): ?>
                                <option value="<?= $k['id']; ?>">
                                    <?= $k['nama_kelas']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="month" name="bulan" class="form-control" value="<?= date('Y-m'); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100"><i class="fas fa-print me-2"></i>Cetak
                        Rekap</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Laporan Poin -->
    <div class="col-md-4 mb-4">
        <div class="card shadow h-100 border-left-danger">
            <div class="card-body">
                <h5 class="card-title fw-bold text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Laporan
                    Pelanggaran</h5>
                <p class="card-text text-muted">Cetak rekap poin dan pelanggaran.</p>
                <form action="cetak_poin.php" method="get" target="_blank">
                    <div class="mb-3">
                        <select name="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($kelas_list as $k): ?>
                                <option value="<?= $k['id']; ?>">
                                    <?= $k['nama_kelas']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-danger w-100"><i class="fas fa-print me-2"></i>Cetak
                        Laporan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>