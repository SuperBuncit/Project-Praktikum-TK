<?php
session_start();
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

check_login();
check_role(['admin', 'guru']);

// Ambil data siswa + kelas
$query = "SELECT data_siswa.*, kelas.nama_kelas 
          FROM data_siswa 
          LEFT JOIN kelas ON data_siswa.kelas_id = kelas.id 
          ORDER BY kelas.nama_kelas ASC, data_siswa.nama_lengkap ASC";
$data_siswa = query($query);

require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Data Siswa</h1>
    <?php if ($_SESSION['role'] == 'admin'): ?>
        <a href="create.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Tambah Siswa</a>
    <?php endif; ?>
</div>

<?php display_flash(); ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Foto</th>
                        <th>Nama Lengkap</th>
                        <th>Kelas</th>
                        <th>L/P</th>
                        <th>Wali Murid</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($data_siswa as $siswa): ?>
                        <tr>
                            <td>
                                <?= $no++; ?>
                            </td>
                            <td>
                                <?= $siswa['nis']; ?>
                            </td>
                            <td>
                                <img src="<?= base_url('assets/uploads/siswa/' . ($siswa['foto'] ?: 'default_siswa.png')); ?>"
                                    alt="Foto" width="50" height="50" class="rounded-circle" style="object-fit: cover;">
                            </td>
                            <td>
                                <?= $siswa['nama_lengkap']; ?>
                            </td>
                            <td>
                                <?= $siswa['nama_kelas'] ?? '<span class="badge bg-secondary">Belum ada kelas</span>'; ?>
                            </td>
                            <td>
                                <?= $siswa['jenis_kelamin']; ?>
                            </td>
                            <td>
                                <?= $siswa['nama_wali']; ?><br>
                                <small class="text-muted">
                                    <?= $siswa['no_hp_wali']; ?>
                                </small>
                            </td>
                            <td>
                                <a href="detail.php?id=<?= $siswa['id']; ?>" class="btn btn-sm btn-info text-white"
                                    title="Detail"><i class="fas fa-eye"></i></a>
                                <?php if ($_SESSION['role'] == 'admin'): ?>
                                    <a href="edit.php?id=<?= $siswa['id']; ?>" class="btn btn-sm btn-warning text-white"
                                        title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="delete.php?id=<?= $siswa['id']; ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin ingin menghapus data ini?');" title="Hapus"><i
                                            class="fas fa-trash"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>