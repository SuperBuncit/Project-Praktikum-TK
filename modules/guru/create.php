<?php
session_start();
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

check_login();
check_role(['admin']);

if (isset($_POST['tambah'])) {
    // 1. Create User Account First
    $username = clean_input($_POST['username']);
    $password = password_hash(clean_input($_POST['password']), PASSWORD_DEFAULT);

    // Check Username
    $cek_user = query("SELECT * FROM users WHERE username = '$username'");
    if (count($cek_user) > 0) {
        flash_msg('danger', 'Username sudah digunakan!');
    } else {
        // Insert User
        $query_user = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'guru')";
        if (mysqli_query($conn, $query_user)) {
            $user_id = mysqli_insert_id($conn); // Get generated ID

            // 2. Insert Data Guru
            $nip = clean_input($_POST['nip']);
            $nama = clean_input($_POST['nama']);
            $gelar = clean_input($_POST['gelar']);
            $alamat = clean_input($_POST['alamat']);
            $hp = clean_input($_POST['hp']);

            // Upload Foto
            $foto = 'default_guru.png';
            if ($_FILES['foto']['error'] !== 4) {
                $target_dir = '../../assets/uploads/guru/';
                $upload = upload_foto($_FILES['foto'], $target_dir);
                if ($upload) {
                    $foto = $upload;
                } else {
                    // Rollback delete user
                    mysqli_query($conn, "DELETE FROM users WHERE id = $user_id");
                    flash_msg('danger', 'Gagal upload foto! Cek format & ukuran.');
                    echo "<script>window.history.back();</script>";
                    exit;
                }
            }

            $query_guru = "INSERT INTO data_guru (user_id, nip, nama_lengkap, gelar, alamat, no_hp, foto) 
                           VALUES ('$user_id', '$nip', '$nama', '$gelar', '$alamat', '$hp', '$foto')";

            if (mysqli_query($conn, $query_guru)) {
                flash_msg('success', 'Data guru dan akun berhasil ditambahkan!');
                redirect('modules/guru/index.php');
            } else {
                // Rollback user if failed (Manual delete)
                mysqli_query($conn, "DELETE FROM users WHERE id = $user_id");
                flash_msg('danger', 'Gagal menambahkan profil guru: ' . mysqli_error($conn));
            }
        } else {
            flash_msg('danger', 'Gagal membuat akun user: ' . mysqli_error($conn));
        }
    }
}

require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tambah Guru</h1>
    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <?php display_flash(); ?>
        <form action="" method="post" enctype="multipart/form-data">
            <h5 class="mb-3 text-primary">Informasi Akun Login</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>

            <hr>
            <h5 class="mb-3 text-primary">Biodata Guru</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Gelar Akdemik</label>
                    <input type="text" name="gelar" class="form-control" placeholder="Contoh: S.Pd.">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="hp" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Foto Guru</label>
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