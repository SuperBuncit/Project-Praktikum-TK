<?php
session_start();
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

check_login();
check_role(['admin']);

// Ambil data guru
$query = "SELECT * FROM data_guru ORDER BY nama_lengkap ASC";
$data_guru = query($query);

require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Data Guru & Staff</h1>
    <a href="create.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Tambah Guru</a>
</div>

<?php display_flash(); ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>NIP</th>
                        <th>Foto</th>
                        <th>Nama Lengkap</th>
                        <th>Gelar</th>
                        <th>No. HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($data_guru as $guru): ?>
                        <tr>
                            <td>
                                <?= $no++; ?>
                            </td>
                            <td>
                                <?= $guru['nip']; ?>
                            </td>
                            <td>
                                <img src="<?= base_url('assets/uploads/guru/' . ($guru['foto'] ?: 'default_guru.png')); ?>"
                                    alt="Foto" width="50" height="50" class="rounded-circle" style="object-fit: cover;">
                            </td>
                            <td>
                                <?= $guru['nama_lengkap']; ?>
                            </td>
                            <td>
                                <?= $guru['gelar']; ?>
                            </td>
                            <td>
                                <?= $guru['no_hp']; ?>
                            </td>
                            <td>
                                <a href="edit.php?id=<?= $guru['id']; ?>" class="btn btn-sm btn-warning text-white"
                                    title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="delete.php?id=<?= $guru['id']; ?>" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin ingin menghapus data ini? Aksi ini juga akan menghapus user login terkait.');"
                                    title="Hapus"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>