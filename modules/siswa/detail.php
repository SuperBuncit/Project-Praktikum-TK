<?php
session_start();
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

check_login();

$id = $_GET['id'];
$query = "SELECT data_siswa.*, kelas.nama_kelas 
          FROM data_siswa 
          LEFT JOIN kelas ON data_siswa.kelas_id = kelas.id 
          WHERE data_siswa.id = $id";
$siswa = query($query)[0];

require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Siswa</h1>
    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-body text-center">
                <img src="<?= base_url('assets/uploads/siswa/' . ($siswa['foto'] ?: 'default_siswa.png')); ?>"
                    alt="Foto Siswa" class="img-fluid rounded-circle mb-3 border"
                    style="width: 150px; height: 150px; object-fit: cover;">
                <h4>
                    <?= $siswa['nama_lengkap']; ?>
                </h4>
                <p class="text-muted">
                    <?= $siswa['nis']; ?>
                </p>
                <span class="badge bg-primary fs-6">
                    <?= $siswa['nama_kelas'] ?? 'Belum ada kelas'; ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Lengkap</h6>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Jenis Kelamin</th>
                        <td>
                            <?= ($siswa['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan'; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Tempat, Tanggal Lahir</th>
                        <td>
                            <?= $siswa['tempat_lahir'] . ', ' . date('d F Y', strtotime($siswa['tanggal_lahir'])); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Nama Wali</th>
                        <td>
                            <?= $siswa['nama_wali']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>No. HP Wali</th>
                        <td>
                            <?= $siswa['no_hp_wali']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>
                            <?= $siswa['alamat']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Poin Saaat Ini</th>
                        <td><span class="badge bg-success">
                                <?= $siswa['poin']; ?> Poin
                            </span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>