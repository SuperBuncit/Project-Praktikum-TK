<?php
session_start();
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

check_login();
check_role(['admin', 'guru']);

// Hapus Data
if (isset($_GET['delete']) && $_SESSION['role'] == 'admin') {
    $id = $_GET['delete'];
    // Ambil data dulu untuk kurangi/tambah poin kembali ke siswa (opsional, tapi bagus untuk konsistensi)
    // Di sini kita hapus log saja, update total poin di data_siswa idealnya dilakukan via trigger atau logic di sini.
    // Untuk simpelnya, kita hapus log saja.

    // Logic advanced: Restore poin siswa row sebelum hapus (skip untuk simplifikasi praktikum)

    mysqli_query($conn, "DELETE FROM log_poin WHERE id = $id");
    flash_msg('success', 'Riwayat poin berhasil dihapus!');
    redirect('modules/poin/index.php');
}

$query = "SELECT log_poin.*, data_siswa.nama_lengkap as nama_siswa, data_siswa.nis, kelas.nama_kelas, users.username as pencatat
          FROM log_poin 
          JOIN data_siswa ON log_poin.siswa_id = data_siswa.id 
          LEFT JOIN kelas ON data_siswa.kelas_id = kelas.id
          LEFT JOIN users ON log_poin.dicatat_oleh = users.id 
          ORDER BY log_poin.tanggal DESC, log_poin.id DESC";
$data_poin = query($query);

require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Riwayat Poin & Pelanggaran</h1>
    <a href="create.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Catat Poin</a>
</div>

<?php display_flash(); ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Tanggal</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Jenis</th>
                        <th>Poin</th>
                        <th>Keterangan</th>
                        <th>Dicatat Oleh</th>
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                            <th>Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data_poin as $row): ?>
                        <tr>
                            <td>
                                <?= date('d/m/Y', strtotime($row['tanggal'])); ?>
                            </td>
                            <td>
                                <strong>
                                    <?= $row['nama_siswa']; ?>
                                </strong><br>
                                <small class="text-muted">
                                    <?= $row['nis']; ?>
                                </small>
                            </td>
                            <td><span class="badge bg-secondary">
                                    <?= $row['nama_kelas']; ?>
                                </span></td>
                            <td>
                                <?php if ($row['jenis'] == 'Prestasi'): ?>
                                    <span class="badge bg-success">Prestasi</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Pelanggaran</span>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold <?= ($row['jumlah_poin'] > 0) ? 'text-success' : 'text-danger'; ?>">
                                <?= ($row['jumlah_poin'] > 0) ? '+' : ''; ?>
                                <?= $row['jumlah_poin']; ?>
                            </td>
                            <td>
                                <?= $row['keterangan']; ?>
                            </td>
                            <td><small>
                                    <?= ucfirst($row['pencatat']); ?>
                                </small></td>
                            <?php if ($_SESSION['role'] == 'admin'): ?>
                                <td>
                                    <a href="?delete=<?= $row['id']; ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Hapus riwayat ini?');"><i class="fas fa-trash"></i></a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>