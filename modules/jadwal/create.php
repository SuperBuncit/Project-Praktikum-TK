<?php
session_start();
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

check_login();
check_role(['admin']);

$kelas = query("SELECT * FROM kelas ORDER BY nama_kelas ASC");
$mapel = query("SELECT * FROM mata_pelajaran ORDER BY nama_mapel ASC");
$guru = query("SELECT * FROM data_guru ORDER BY nama_lengkap ASC");

if (isset($_POST['tambah'])) {
    $kelas_id = $_POST['kelas_id'];
    $mapel_id = $_POST['mapel_id'];
    $guru_id = $_POST['guru_id'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    // Validasi Tabrakan Jadwal (Sederhana)
    // Cek apakah guru ini sudah mengajar di jam yang sama pada hari yang sama
    $cek_guru = query("SELECT * FROM jadwal_pelajaran WHERE guru_id = '$guru_id' AND hari = '$hari' AND 
                       ((jam_mulai <= '$jam_mulai' AND jam_selesai > '$jam_mulai') OR 
                        (jam_mulai < '$jam_selesai' AND jam_selesai >= '$jam_selesai'))");

    // Cek apakah kelas ini sudah ada jadwal di jam yang sama
    $cek_kelas = query("SELECT * FROM jadwal_pelajaran WHERE kelas_id = '$kelas_id' AND hari = '$hari' AND 
                       ((jam_mulai <= '$jam_mulai' AND jam_selesai > '$jam_mulai') OR 
                        (jam_mulai < '$jam_selesai' AND jam_selesai >= '$jam_selesai'))");

    if (count($cek_guru) > 0) {
        flash_msg('danger', 'Guru tersebut sedang mengajar di kelas lain pada jam ini!');
    } elseif (count($cek_kelas) > 0) {
        flash_msg('danger', 'Kelas ini sudah memiliki jadwal pada jam ini!');
    } else {
        $query = "INSERT INTO jadwal_pelajaran (kelas_id, mapel_id, guru_id, hari, jam_mulai, jam_selesai) 
                  VALUES ('$kelas_id', '$mapel_id', '$guru_id', '$hari', '$jam_mulai', '$jam_selesai')";

        if (mysqli_query($conn, $query)) {
            flash_msg('success', 'Jadwal berhasil ditambahkan!');
            redirect('modules/jadwal/index.php');
        } else {
            flash_msg('danger', 'Gagal: ' . mysqli_error($conn));
        }
    }
}

require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tambah Jadwal</h1>
    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <?php display_flash(); ?>
        <form action="" method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kelas</label>
                    <select name="kelas_id" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($kelas as $row): ?>
                            <option value="<?= $row['id']; ?>">
                                <?= $row['nama_kelas']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mata Pelajaran</label>
                    <select name="mapel_id" class="form-select" required>
                        <option value="">-- Pilih Mapel --</option>
                        <?php foreach ($mapel as $row): ?>
                            <option value="<?= $row['id']; ?>">
                                <?= $row['nama_mapel']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Guru Pengajar</label>
                    <select name="guru_id" class="form-select" required>
                        <option value="">-- Pilih Guru --</option>
                        <?php foreach ($guru as $row): ?>
                            <option value="<?= $row['id']; ?>">
                                <?= $row['nama_lengkap']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Hari</label>
                    <select name="hari" class="form-select" required>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control" required>
                </div>
            </div>
            <button type="submit" name="tambah" class="btn btn-primary">Simpan Jadwal</button>
        </form>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>