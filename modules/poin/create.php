<?php
session_start();
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

check_login();
check_role(['admin', 'guru']);

// Ambil data siswa untuk dropdown
$siswa_list = query("SELECT data_siswa.*, kelas.nama_kelas FROM data_siswa JOIN kelas ON data_siswa.kelas_id = kelas.id ORDER BY kelas.nama_kelas ASC, data_siswa.nama_lengkap ASC");

if (isset($_POST['simpan'])) {
    $siswa_id = $_POST['siswa_id'];
    $jenis = $_POST['jenis'];
    $poin_input = intval($_POST['poin']); // Nilai absolut yang diinput
    $keterangan = clean_input($_POST['keterangan']);
    $tanggal = $_POST['tanggal'];
    $pencatat = $_SESSION['user_id'];

    // Tentukan positif/negatif
    $jumlah_poin = ($jenis == 'Pelanggaran') ? -1 * abs($poin_input) : abs($poin_input);

    // 1. Insert ke Log Poin
    $query_log = "INSERT INTO log_poin (siswa_id, jenis, jumlah_poin, keterangan, tanggal, dicatat_oleh) 
                  VALUES ('$siswa_id', '$jenis', '$jumlah_poin', '$keterangan', '$tanggal', '$pencatat')";

    if (mysqli_query($conn, $query_log)) {
        // 2. Update Total Poin di Tabel Siswa
        $query_update = "UPDATE data_siswa SET poin = poin + ($jumlah_poin) WHERE id = $siswa_id";
        mysqli_query($conn, $query_update);

        flash_msg('success', 'Poin berhasil dicatat!');
        redirect('modules/poin/index.php');
    } else {
        flash_msg('danger', 'Gagal mencatat poin: ' . mysqli_error($conn));
    }
}

require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Catat Prestasi / Pelanggaran</h1>
    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-body">
                <?php display_flash(); ?>
                <form action="" method="post">
                    <div class="mb-3">
                        <label class="form-label">Nama Siswa</label>
                        <select name="siswa_id" class="form-select" required>
                            <option value="">-- Pilih Siswa --</option>
                            <?php foreach ($siswa_list as $row): ?>
                                <option value="<?= $row['id']; ?>">
                                    <?= $row['nama_kelas']; ?> -
                                    <?= $row['nama_lengkap']; ?> (
                                    <?= $row['nis']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis</label>
                            <select name="jenis" class="form-select" required>
                                <option value="Prestasi">Prestasi (Menambah Poin)</option>
                                <option value="Pelanggaran">Pelanggaran (Mengurangi Poin)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Poin</label>
                            <input type="number" name="poin" class="form-control" placeholder="Contoh: 10" min="1"
                                required>
                            <small class="text-muted">Masukkan angka positif saja.</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Kejadian</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan / Detail</label>
                        <textarea name="keterangan" class="form-control" rows="3" required
                            placeholder="Contoh: Juara 1 Lomba Mewarnai / Terlambat Datang"></textarea>
                    </div>

                    <button type="submit" name="simpan" class="btn btn-primary">Simpan Data</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-info text-white shadow">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>Informasi Sistem Poin</h5>
                <p class="card-text">
                    <strong>Prestasi:</strong><br>
                    Menambahkan poin siswa. Digunakan untuk apresiasi pencapaian.<br><br>
                    <strong>Pelanggaran:</strong><br>
                    Mengurangi poin siswa. Digunakan untuk mendisiplinkan siswa.
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>