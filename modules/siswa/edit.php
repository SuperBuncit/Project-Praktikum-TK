<?php
session_start();
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

check_login();
check_role(['admin']);

$id = $_GET['id'];
$siswa = query("SELECT * FROM data_siswa WHERE id = $id")[0];
$kelas = query("SELECT * FROM kelas ORDER BY nama_kelas ASC");

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nis = clean_input($_POST['nis']);
    $nama = clean_input($_POST['nama']);
    $jk = clean_input($_POST['jk']);
    $tmp_lahir = clean_input($_POST['tmp_lahir']);
    $tgl_lahir = clean_input($_POST['tgl_lahir']);
    $alamat = clean_input($_POST['alamat']);
    $wali = clean_input($_POST['wali']);
    $hp_wali = clean_input($_POST['hp_wali']);
    $kelas_id = clean_input($_POST['kelas_id']);

    // Logika Update Foto
    $foto_query = "";
    if ($_FILES['foto']['error'] !== 4) {
        $target_dir = '../../assets/uploads/siswa/';
        $upload = upload_foto($_FILES['foto'], $target_dir);
        if ($upload) {
            $foto_query = ", foto = '$upload'";
            // Optional: Hapus foto lama jika bukan default
        } else {
            flash_msg('danger', 'Gagal upload foto! Cek format & ukuran.');
            echo "<script>window.history.back();</script>";
            exit;
        }
    }

    $query = "UPDATE data_siswa SET 
              nis = '$nis',
              nama_lengkap = '$nama',
              jenis_kelamin = '$jk',
              tempat_lahir = '$tmp_lahir',
              tanggal_lahir = '$tgl_lahir',
              alamat = '$alamat',
              nama_wali = '$wali',
              no_hp_wali = '$hp_wali',
              kelas_id = '$kelas_id'
              $foto_query
              WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        flash_msg('success', 'Data siswa berhasil diupdate!');
        redirect('modules/siswa/index.php');
    } else {
        flash_msg('danger', 'Gagal update data: ' . mysqli_error($conn));
    }
}

require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Siswa</h1>
    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <?php display_flash(); ?>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $siswa['id']; ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">NIS</label>
                    <input type="text" name="nis" class="form-control" value="<?= $siswa['nis']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="<?= $siswa['nama_lengkap']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jk" class="form-select">
                        <option value="L" <?= ($siswa['jenis_kelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                        <option value="P" <?= ($siswa['jenis_kelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kelas</label>
                    <select name="kelas_id" class="form-select">
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($kelas as $k): ?>
                            <option value="<?= $k['id']; ?>" <?= ($siswa['kelas_id'] == $k['id']) ? 'selected' : ''; ?>>
                                <?= $k['nama_kelas']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tmp_lahir" class="form-control" value="<?= $siswa['tempat_lahir']; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" class="form-control" value="<?= $siswa['tanggal_lahir']; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Wali</label>
                    <input type="text" name="wali" class="form-control" value="<?= $siswa['nama_wali']; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">No. HP Wali</label>
                    <input type="text" name="hp_wali" class="form-control" value="<?= $siswa['no_hp_wali']; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Foto Siswa</label>
                    <div class="mb-2">
                        <img src="../../assets/uploads/siswa/<?= $siswa['foto'] ?? 'default_siswa.png'; ?>"
                            alt="Foto Lama" width="80" class="img-thumbnail">
                    </div>
                    <input type="file" name="foto" class="form-control">
                    <div class="form-text">Biarkan kosong jika tidak ingin mengubah foto.</div>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3"><?= $siswa['alamat']; ?></textarea>
                </div>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update Data</button>
        </form>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>