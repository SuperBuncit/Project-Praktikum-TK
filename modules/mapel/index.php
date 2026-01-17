<?php
session_start();
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

check_login();
check_role(['admin']);

// Tambah Mapel
if (isset($_POST['add_mapel'])) {
    $kode = clean_input($_POST['kode']);
    $nama = clean_input($_POST['nama']);
    $deskripsi = clean_input($_POST['deskripsi']);

    // Check Duplicate
    $cek = query("SELECT * FROM mata_pelajaran WHERE kode_mapel = '$kode'");
    if (count($cek) > 0) {
        flash_msg('danger', 'Kode mapel sudah ada!');
    } else {
        if (mysqli_query($conn, "INSERT INTO mata_pelajaran (kode_mapel, nama_mapel, deskripsi) VALUES ('$kode', '$nama', '$deskripsi')")) {
            flash_msg('success', 'Mata Pelajaran berhasil ditambahkan!');
        } else {
            flash_msg('danger', 'Gagal tambah mapel: ' . mysqli_error($conn));
        }
    }
    redirect('modules/mapel/index.php');
}

// Edit Mapel
if (isset($_POST['edit_mapel'])) {
    $id = $_POST['id'];
    $kode = clean_input($_POST['kode']);
    $nama = clean_input($_POST['nama']);
    $deskripsi = clean_input($_POST['deskripsi']);

    if (mysqli_query($conn, "UPDATE mata_pelajaran SET kode_mapel = '$kode', nama_mapel = '$nama', deskripsi = '$deskripsi' WHERE id = $id")) {
        flash_msg('success', 'Mata Pelajaran berhasil diupdate!');
    } else {
        flash_msg('danger', 'Gagal update mapel: ' . mysqli_error($conn));
    }
    redirect('modules/mapel/index.php');
}

// Hapus Mapel
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if (mysqli_query($conn, "DELETE FROM mata_pelajaran WHERE id = $id")) {
        flash_msg('success', 'Mata Pelajaran berhasil dihapus!');
    } else {
        flash_msg('danger', 'Gagal hapus mapel: ' . mysqli_error($conn));
    }
    redirect('modules/mapel/index.php');
}

$mapel_list = query("SELECT * FROM mata_pelajaran ORDER BY kode_mapel ASC");

require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Mata Pelajaran</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fas fa-plus me-2"></i>Tambah Mapel
    </button>
</div>

<?php display_flash(); ?>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Mapel</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($mapel_list as $row): ?>
                            <tr>
                                <td>
                                    <?= $no++; ?>
                                </td>
                                <td><span class="badge bg-secondary">
                                        <?= $row['kode_mapel']; ?>
                                    </span></td>
                                <td>
                                    <?= $row['nama_mapel']; ?>
                                </td>
                                <td>
                                    <?= $row['deskripsi']; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal"
                                        data-bs-target="#editModal<?= $row['id']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="?delete=<?= $row['id']; ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Hapus mapel ini?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?= $row['id']; ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="post">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Mapel</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                <div class="mb-3">
                                                    <label>Kode Mapel</label>
                                                    <input type="text" name="kode" class="form-control"
                                                        value="<?= $row['kode_mapel']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Nama Mapel</label>
                                                    <input type="text" name="nama" class="form-control"
                                                        value="<?= $row['nama_mapel']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Deskripsi</label>
                                                    <textarea name="deskripsi" class="form-control"
                                                        rows="3"><?= $row['deskripsi']; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" name="edit_mapel" class="btn btn-primary">Simpan
                                                    Perubahan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Mata Pelajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Kode Mapel</label>
                        <input type="text" name="kode" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Nama Mapel</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="add_mapel" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>