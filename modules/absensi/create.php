<?php
session_start();
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

check_login();
check_role(['admin', 'guru']);

$kelas_list = query("SELECT * FROM kelas ORDER BY nama_kelas ASC");
$students = [];

// Jika kelas dipilih, ambil siswanya
if (isset($_GET['kelas_id']) && $_GET['kelas_id'] != '') {
    $kelas_id = $_GET['kelas_id'];
    $students = query("SELECT * FROM data_siswa WHERE kelas_id = $kelas_id ORDER BY nama_lengkap ASC");
}

// Proses Simpan Absensi
if (isset($_POST['simpan'])) {
    $tgl = $_POST['tanggal'];
    $siswa_ids = $_POST['siswa_id']; // Array
    $statuses = $_POST['status'];   // Array
    $keterangans = $_POST['keterangan']; // Array

    $count = 0;
    foreach ($siswa_ids as $key => $s_id) {
        $status = $statuses[$key];
        $ket = clean_input($keterangans[$key]);

        // Cek apakah sudah absen hari ini? (Update if exists, Insert if not)
        $cek = query("SELECT id FROM absensi WHERE siswa_id = '$s_id' AND tanggal = '$tgl'");

        if (count($cek) > 0) {
            // Update
            $id_absen = $cek[0]['id'];
            $query = "UPDATE absensi SET status = '$status', keterangan = '$ket' WHERE id = $id_absen";
        } else {
            // Insert
            $query = "INSERT INTO absensi (siswa_id, tanggal, status, keterangan) VALUES ('$s_id', '$tgl', '$status', '$ket')";
        }

        if (mysqli_query($conn, $query))
            $count++;
    }

    flash_msg('success', "Berhasil menyimpan absensi untuk $count siswa!");
    redirect("modules/absensi/index.php?tanggal=$tgl&kelas_id=" . $_POST['kelas_id_hidden']);
}

require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Input Absensi Kelas</h1>
    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="card shadow mb-4">
    <div class="card-header bg-white py-3">
        <form action="" method="get">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Pilih Kelas untuk Diabsen</label>
                    <select name="kelas_id" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($kelas_list as $k): ?>
                            <option value="<?= $k['id']; ?>" <?= (isset($_GET['kelas_id']) && $_GET['kelas_id'] == $k['id']) ? 'selected' : ''; ?>>
                                <?= $k['nama_kelas']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Tampilkan Siswa</button>
                </div>
            </div>
        </form>
    </div>

    <?php if (isset($_GET['kelas_id']) && $_GET['kelas_id'] != ''): ?>
        <div class="card-body">
            <?php if (count($students) > 0): ?>
                <form action="" method="post">
                    <input type="hidden" name="kelas_id_hidden" value="<?= $_GET['kelas_id']; ?>">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Tanggal Absensi</label>
                            <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="30%">Nama Siswa</th>
                                    <th width="30%">Status Kehadiran</th>
                                    <th>Keterangan (Opsional)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($students as $s): ?>
                                    <tr>
                                        <td>
                                            <?= $no++; ?>
                                            <input type="hidden" name="siswa_id[]" value="<?= $s['id']; ?>">
                                        </td>
                                        <td>
                                            <strong>
                                                <?= $s['nama_lengkap']; ?>
                                            </strong><br>
                                            <small class="text-muted">
                                                <?= $s['nis']; ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <input type="radio" class="btn-check" name="status[<?= $no - 2; ?>]"
                                                    id="hadir<?= $s['id']; ?>" value="Hadir" checked>
                                                <label class="btn btn-outline-success btn-sm"
                                                    for="hadir<?= $s['id']; ?>">Hadir</label>

                                                <input type="radio" class="btn-check" name="status[<?= $no - 2; ?>]"
                                                    id="sakit<?= $s['id']; ?>" value="Sakit">
                                                <label class="btn btn-outline-warning btn-sm"
                                                    for="sakit<?= $s['id']; ?>">Sakit</label>

                                                <input type="radio" class="btn-check" name="status[<?= $no - 2; ?>]"
                                                    id="izin<?= $s['id']; ?>" value="Izin">
                                                <label class="btn btn-outline-info btn-sm" for="izin<?= $s['id']; ?>">Izin</label>

                                                <input type="radio" class="btn-check" name="status[<?= $no - 2; ?>]"
                                                    id="alpa<?= $s['id']; ?>" value="Alpa">
                                                <label class="btn btn-outline-danger btn-sm" for="alpa<?= $s['id']; ?>">Alpa</label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="keterangan[]" class="form-control form-control-sm"
                                                placeholder="Keterangan...">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <button type="submit" name="simpan" class="btn btn-success mt-3"><i class="fas fa-save me-2"></i>Simpan
                        Absensi</button>
                </form>
            <?php else: ?>
                <div class="alert alert-info">Tidak ada siswa di kelas ini.</div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../../layouts/footer.php'; ?>