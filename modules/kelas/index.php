<?php
session_start();
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

check_login();
check_role(['admin']);

// Tambah Kelas
if (isset($_POST['add_kelas'])) {
    $nama_kelas = clean_input($_POST['nama_kelas']);
    $kapasitas = clean_input($_POST['kapasitas']);

    // Check Duplicate
    $cek = query("SELECT * FROM kelas WHERE nama_kelas = '$nama_kelas'");
    if (count($cek) > 0) {
        flash_msg('danger', 'Nama kelas sudah ada!');
    } else {
        if (mysqli_query($conn, "INSERT INTO kelas (nama_kelas, kapasitas) VALUES ('$nama_kelas', '$kapasitas')")) {
            flash_msg('success', 'Kelas berhasil ditambahkan!');
        } else {
            flash_msg('danger', 'Gagal tambah kelas: ' . mysqli_error($conn));
        }
    }
    redirect('modules/kelas/index.php');
}

// Edit Kelas
if (isset($_POST['edit_kelas'])) {
    $id = $_POST['id'];
    $nama_kelas = clean_input($_POST['nama_kelas']);
    $kapasitas = clean_input($_POST['kapasitas']);

    if (mysqli_query($conn, "UPDATE kelas SET nama_kelas = '$nama_kelas', kapasitas = '$kapasitas' WHERE id = $id")) {
        flash_msg('success', 'Kelas berhasil diupdate!');
    } else {
        flash_msg('danger', 'Gagal update kelas: ' . mysqli_error($conn));
    }
    redirect('modules/kelas/index.php');
}

// Hapus Kelas
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if (mysqli_query($conn, "DELETE FROM kelas WHERE id = $id")) {
        flash_msg('success', 'Kelas berhasil dihapus!');
    } else {
        flash_msg('danger', 'Gagal hapus kelas: ' . mysqli_error($conn));
    }
    redirect('modules/kelas/index.php');
}

$kelas_list = query("SELECT * FROM kelas ORDER BY nama_kelas ASC");

require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Kelas</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fas fa-plus me-2"></i>Tambah Kelas
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
                            <th>Nama Kelas</th>
                            <th>Kapasitas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($kelas_list as $row): ?>
                            <tr>
                                <td>
                                    <?= $no++; ?>
                                </td>
                                <td>
                                    <?= $row['nama_kelas']; ?>
                                </td>
                                <td>
                                    <?= $row['kapasitas']; ?> Siswa
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal"
                                        data-bs-target="#editModal<?= $row['id']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="?delete=<?= $row['id']; ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Hapus kelas ini? Data siswa di kelas ini akan kehilangan referensi kelas.');">
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
                                                <h5 class="modal-title">Edit Kelas</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                <div class="mb-3">
                                                    <label>Nama Kelas</label>
                                                    <input type="text" name="nama_kelas" class="form-control"
                                                        value="<?= $row['nama_kelas']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Kapasitas</label>
                                                    <input type="number" name="kapasitas" class="form-control"
                                                        value="<?= $row['kapasitas']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" name="edit_kelas" class="btn btn-primary">Simpan
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
                    <h5 class="modal-title">Tambah Kelas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Kelas</label>
                        <input type="text" name="nama_kelas" class="form-control" placeholder="Contoh: TK-A Bintang"
                            required>
                    </div>
                    <div class="mb-3">
                        <label>Kapasitas</label>
                        <input type="number" name="kapasitas" class="form-control" value="20" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="add_kelas" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>