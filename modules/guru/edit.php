<?php
session_start();
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

check_login();
check_role(['admin']);

$id = $_GET['id'];
$guru = query("SELECT * FROM data_guru WHERE id = $id")[0];

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nip = clean_input($_POST['nip']);
    $nama = clean_input($_POST['nama']);
    $gelar = clean_input($_POST['gelar']);
    $alamat = clean_input($_POST['alamat']);
    $hp = clean_input($_POST['hp']);

    // Logika Update Foto
    $foto_query = "";
    if ($_FILES['foto']['error'] !== 4) {
        $target_dir = '../../assets/uploads/guru/';
        $upload = upload_foto($_FILES['foto'], $target_dir);
        if ($upload) {
            $foto_query = ", foto = '$upload'";
        } else {
            flash_msg('danger', 'Gagal upload foto! Cek format & ukuran.');
            echo "<script>window.history.back();</script>";
            exit;
        }
    }

    $query = "UPDATE data_guru SET 
              nip = '$nip',
              nama_lengkap = '$nama',
              gelar = '$gelar',
              alamat = '$alamat',
              no_hp = '$hp'
              $foto_query
              WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        flash_msg('success', 'Data guru berhasil diupdate!');
        redirect('modules/guru/index.php');
    } else {
        flash_msg('danger', 'Gagal update data: ' . mysqli_error($conn));
    }
}

require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Guru</h1>
    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <?php display_flash(); ?>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $guru['id']; ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-control" value="<?= $guru['nip']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="<?= $guru['nama_lengkap']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Gelar Akdemik</label>
                    <input type="text" name="gelar" class="form-control" value="<?= $guru['gelar']; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="hp" class="form-control" value="<?= $guru['no_hp']; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Foto Guru</label>
                    <div class="mb-2">
                        <img src="../../assets/uploads/guru/<?= $guru['foto'] ?? 'default_guru.png'; ?>" alt="Foto Lama"
                            width="80" class="img-thumbnail">
                    </div>
                    <input type="file" name="foto" class="form-control">
                    <div class="form-text">Biarkan kosong jika tidak ingin mengubah foto.</div>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3"><?= $guru['alamat']; ?></textarea>
                </div>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update Data</button>
        </form>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>