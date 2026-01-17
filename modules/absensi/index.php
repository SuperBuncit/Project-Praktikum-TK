<?php
session_start();
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

check_login();
check_role(['admin', 'guru']);

// Filter Tanggal & Kelas
$tgl = $_GET['tanggal'] ?? date('Y-m-d');
$kelas_id = $_GET['kelas_id'] ?? '';

$where = "WHERE absensi.tanggal = '$tgl'";
if ($kelas_id) {
    $where .= " AND data_siswa.kelas_id = '$kelas_id'";
}

$query = "SELECT absensi.*, data_siswa.nama_lengkap, data_siswa.nis, kelas.nama_kelas 
          FROM absensi 
          JOIN data_siswa ON absensi.siswa_id = data_siswa.id 
          LEFT JOIN kelas ON data_siswa.kelas_id = kelas.id 
          $where
          ORDER BY kelas.nama_kelas ASC, data_siswa.nama_lengkap ASC";
$absensi = query($query);

$kelas_list = query("SELECT * FROM kelas ORDER BY nama_kelas ASC");

require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Data Absensi Harian</h1>
    <a href="create.php" class="btn btn-primary"><i class="fas fa-clipboard-check me-2"></i>Input Absensi</a>
</div>

<?php display_flash(); ?>

<div class="card shadow mb-4">
    <div class="card-header bg-white py-3">
        <form action="" method="get" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-bold">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="<?= $tgl; ?>"
                    onchange="this.form.submit()">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Kelas</label>
                <select name="kelas_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kelas</option>
                    <?php foreach ($kelas_list as $k): ?>
                        <option value="<?= $k['id']; ?>" <?= ($kelas_id == $k['id']) ? 'selected' : ''; ?>>
                            <?= $k['nama_kelas']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
    <div class="card-body">
        <?php if (count($absensi) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kelas</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($absensi as $row): ?>
                            <tr>
                                <td>
                                    <?= $no++; ?>
                                </td>
                                <td>
                                    <?= $row['nama_kelas']; ?>
                                </td>
                                <td>
                                    <?= $row['nis']; ?>
                                </td>
                                <td>
                                    <?= $row['nama_lengkap']; ?>
                                </td>
                                <td>
                                    <?php
                                    $badge = [
                                        'Hadir' => 'success',
                                        'Sakit' => 'warning',
                                        'Izin' => 'info',
                                        'Alpa' => 'danger'
                                    ];
                                    ?>
                                    <span class="badge bg-<?= $badge[$row['status']]; ?>">
                                        <?= $row['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?= $row['keterangan']; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                Belum ada data absensi untuk tanggal <strong>
                    <?= date('d-m-Y', strtotime($tgl)); ?>
                </strong>
                <?= ($kelas_id) ? "di kelas terpilih" : ""; ?>.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>