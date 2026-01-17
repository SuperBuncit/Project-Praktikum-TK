<?php
session_start();
require_once 'config/config.php';
require_once 'helpers/functions.php';

check_login();

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$msg = '';

// 1. Fetch User Data
$user = query("SELECT * FROM users WHERE id = $user_id")[0];
$profile = [];

if ($role == 'guru') {
    $profile = query("SELECT * FROM data_guru WHERE user_id = $user_id");
    if (!empty($profile))
        $profile = $profile[0];
} elseif ($role == 'siswa') {
    $profile = query("SELECT * FROM data_siswa WHERE user_id = $user_id");
    if (!empty($profile))
        $profile = $profile[0];
}

// 2. Handle Form Submission
if (isset($_POST['update_profile'])) {

    // A. Update Password (All Roles)
    if (!empty($_POST['password_baru'])) {
        $password_lama = $_POST['password_lama'];
        $password_baru = $_POST['password_baru'];
        $konfirmasi = $_POST['konfirmasi_password'];

        if (password_verify($password_lama, $user['password'])) {
            if ($password_baru === $konfirmasi) {
                $hash = password_hash($password_baru, PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE users SET password = '$hash' WHERE id = $user_id");
                $msg = "<div class='alert alert-success'>Password berhasil diubah!</div>";
            } else {
                $msg = "<div class='alert alert-danger'>Konfirmasi password tidak cocok!</div>";
            }
        } else {
            $msg = "<div class='alert alert-danger'>Password lama salah!</div>";
        }
    }

    // B. Update Biodata (Guru Only)
    if ($role == 'guru' && empty($msg)) { // Only proceed if no password error
        $nama = clean_input($_POST['nama']);
        $no_hp = clean_input($_POST['no_hp']);
        $alamat = clean_input($_POST['alamat']);
        $gelar = clean_input($_POST['gelar']);
        $id_guru = $profile['id'];

        // Handle Photo Upload
        $foto_query = "";
        if ($_FILES['foto']['error'] !== 4) {
            $target_dir = 'assets/uploads/guru/';
            $upload = upload_foto($_FILES['foto'], $target_dir);
            if ($upload) {
                $foto_query = ", foto = '$upload'";
            } else {
                $msg = "<div class='alert alert-danger'>Gagal upload foto!</div>";
            }
        }

        if (empty($msg)) {
            $query = "UPDATE data_guru SET 
                      nama_lengkap = '$nama', 
                      no_hp = '$no_hp', 
                      alamat = '$alamat',
                      gelar = '$gelar'
                      $foto_query
                      WHERE id = $id_guru";

            if (mysqli_query($conn, $query)) {
                $msg = "<div class='alert alert-success'>Profil berhasil diperbarui!</div>";
                // Refresh Data
                $profile = query("SELECT * FROM data_guru WHERE user_id = $user_id")[0];
            } else {
                $msg = "<div class='alert alert-danger'>Gagal update profil: " . mysqli_error($conn) . "</div>";
            }
        }
    }

    // C. Update Username (Admin Only)
    if ($role == 'admin' && empty($msg)) {
        $username = clean_input($_POST['username']);
        if ($username != $user['username']) {
            $cek = query("SELECT * FROM users WHERE username = '$username'");
            if (count($cek) > 0) {
                $msg = "<div class='alert alert-danger'>Username sudah digunakan!</div>";
            } else {
                mysqli_query($conn, "UPDATE users SET username = '$username' WHERE id = $user_id");
                $_SESSION['username'] = $username;
                $msg = "<div class='alert alert-success'>Username berhasil diubah!</div>";
            }
        }
    }
}

require_once 'layouts/header.php';
require_once 'layouts/sidebar.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Profil Pengguna</h1>
</div>

<?= $msg; ?>

<div class="row">
    <!-- Kolom Kiri: Foto & Info Dasar -->
    <div class="col-md-4 mb-4">
        <div class="card shadow">
            <div class="card-body text-center">
                <?php
                $foto = 'default.png';
                $path = 'assets/img/';

                if ($role == 'guru' && !empty($profile['foto'])) {
                    $foto = $profile['foto'];
                    $path = 'assets/uploads/guru/';
                } elseif ($role == 'siswa' && !empty($profile['foto'])) {
                    $foto = $profile['foto'];
                    $path = 'assets/uploads/siswa/';
                }
                ?>
                <img src="<?= base_url($path . $foto); ?>" alt="Foto Profil"
                    class="img-fluid rounded-circle mb-3 border p-1"
                    style="width: 150px; height: 150px; object-fit: cover;">

                <h4 class="mb-0">
                    <?= ($_SESSION['role'] == 'admin') ? 'Administrator' : ($profile['nama_lengkap'] ?? 'User'); ?>
                </h4>
                <p class="text-muted">
                    <?= ucfirst($role); ?>
                </p>

                <?php if ($role == 'siswa'): ?>
                    <hr>
                    <div class="text-start">
                        <strong>NIS:</strong>
                        <?= $profile['nis']; ?><br>
                        <strong>Poin:</strong> <span class="badge bg-success">
                            <?= $profile['poin']; ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Form Edit -->
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit Profil</h6>
            </div>
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">

                    <!-- ADMIN ONLY: Username -->
                    <?php if ($role == 'admin'): ?>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" value="<?= $user['username']; ?>">
                        </div>
                    <?php endif; ?>

                    <!-- GURU ONLY: Edit Biodata -->
                    <?php if ($role == 'guru'): ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" value="<?= $profile['nama_lengkap']; ?>"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gelar</label>
                                <input type="text" name="gelar" class="form-control" value="<?= $profile['gelar']; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. HP</label>
                                <input type="text" name="no_hp" class="form-control" value="<?= $profile['no_hp']; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ganti Foto</label>
                                <input type="file" name="foto" class="form-control">
                                <div class="form-text text-muted">Format JPG/PNG, Max 2MB</div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2"><?= $profile['alamat']; ?></textarea>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- SISWA & READONLY INFOS -->
                    <?php if ($role == 'siswa'): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-1"></i> Data diri siswa hanya dapat diubah oleh Admin. Silakan
                            hubungi admin jika ada kesalahan data.
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" value="<?= $profile['nama_lengkap']; ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" disabled><?= $profile['alamat']; ?></textarea>
                        </div>
                    <?php endif; ?>

                    <hr>
                    <h6 class="text-primary mb-3">Ganti Password (Opsional)</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Password Lama</label>
                            <input type="password" name="password_lama" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password_baru" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="konfirmasi_password" class="form-control">
                        </div>
                    </div>

                    <button type="submit" name="update_profile" class="btn btn-primary"><i class="fas fa-save me-2"></i>
                        Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>