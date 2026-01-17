<?php
session_start();
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

check_login();
check_role(['admin']);

// Ambil data kelas untuk dropdown
$kelas = query("SELECT * FROM kelas ORDER BY nama_kelas ASC");

if (isset($_POST['tambah'])) {
    $nis = clean_input($_POST['nis']);
    $nama = clean_input($_POST['nama']);
    $jk = clean_input($_POST['jk']);
    $tmp_lahir = clean_input($_POST['tmp_lahir']);
    $tgl_lahir = clean_input($_POST['tgl_lahir']);
    $alamat = clean_input($_POST['alamat']);
    $wali = clean_input($_POST['wali']);
    $hp_wali = clean_input($_POST['hp_wali']);
    $kelas_id = clean_input($_POST['kelas_id']);

    // Validasi NIS simple
    $cek = query("SELECT * FROM data_siswa WHERE nis = '$nis'");
    if (count($cek) > 0) {
        flash_msg('danger', 'NIS sudah terdaftar!');
    } else {
        // 1. Buat Akun Siswa (Username = NIS, Password = NIS)
        $password = password_hash($nis, PASSWORD_DEFAULT);

        // Cek ID User apakah sudah ada (mungkin bekas hapus)
        $cek_user = query("SELECT * FROM users WHERE username = '$nis'");
        if (count($cek_user) > 0) {
            flash_msg('danger', 'NIS/Username sudah digunakan di data User account!');
            echo "<script>window.history.back();</script>";
            exit;
        }

        $query_user = "INSERT INTO users (username, password, role) VALUES ('$nis', '$password', 'siswa')";

        if (mysqli_query($conn, $query_user)) {
            $user_id = mysqli_insert_id($conn);

            // Upload Foto
            $foto = 'default_siswa.png';
            if ($_FILES['foto']['error'] !== 4) {
                $target_dir = '../../assets/uploads/siswa/';
                $upload = upload_foto($_FILES['foto'], $target_dir);
                if ($upload) {
                    $foto = $upload;
                } else {
                    // Rollback delete user
                    mysqli_query($conn, "DELETE FROM users WHERE id = $user_id");
                    flash_msg('danger', 'Gagal upload foto! Pastikan format jpg/png dan ukuran < 2MB.');
                    echo "<script>window.history.back();</script>";
                    exit;
                }
            }

            $query = "INSERT INTO data_siswa (user_id, nis, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, nama_wali, no_hp_wali, kelas_id, foto) 
                      VALUES ('$user_id', '$nis', '$nama', '$tmp_lahir', '$tgl_lahir', '$jk', '$alamat', '$wali', '$hp_wali', '$kelas_id', '$foto')";

            if (mysqli_query($conn, $query)) {
                flash_msg('success', 'Data siswa berhasil ditambahkan! Akun login otomatis dibuat (User/Pass: NIS).');
                redirect('modules/siswa/index.php');
            } else {
                // Rollback user if failed
                mysqli_query($conn, "DELETE FROM users WHERE id = $user_id");
                flash_msg('danger', 'Gagal menambahkan data: ' . mysqli_error($conn));
            }
        } else {
            flash_msg('danger', 'Gagal membuat akun login: ' . mysqli_error($conn));
        }
    }
}

require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tambah Siswa</h1>
    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <?php display_flash(); ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">NIS</label>
                    <input type="text" name="nis" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jk" class="form-select">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kelas</label>
                    <select name="kelas_id" class="form-select">
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($kelas as $k): ?>
                            <option value="<?= $k['id']; ?>">
                                <?= $k['nama_kelas']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tmp_lahir" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Wali</label>
                    <input type="text" name="wali" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">No. HP Wali</label>
                    <input type="text" name="hp_wali" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Foto Siswa</label>
                    <input type="file" name="foto" class="form-control">
                    <div class="form-text">Format: JPG/PNG, Max: 2MB</div>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <button type="submit" name="tambah" class="btn btn-primary">Simpan Data</button>
        </form>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>