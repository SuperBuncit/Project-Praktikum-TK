<?php
session_start();
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

check_login();
check_role(['admin', 'guru', 'siswa']);

if (isset($_GET['delete']) && $_SESSION['role'] == 'admin') {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM jadwal_pelajaran WHERE id = $id");
    flash_msg('success', 'Jadwal berhasil dihapus!');
    redirect('modules/jadwal/index.php');
}

// Filter Kelas
$filter_kelas = $_GET['kelas_id'] ?? '';
$where_clause = "";
if ($filter_kelas) {
    $where_clause = "WHERE jadwal_pelajaran.kelas_id = '$filter_kelas'";
}

$query = "SELECT jadwal_pelajaran.*, kelas.nama_kelas, mata_pelajaran.nama_mapel, data_guru.nama_lengkap as nama_guru 
          FROM jadwal_pelajaran 
          JOIN kelas ON jadwal_pelajaran.kelas_id = kelas.id 
          JOIN mata_pelajaran ON jadwal_pelajaran.mapel_id = mata_pelajaran.id 
          JOIN data_guru ON jadwal_pelajaran.guru_id = data_guru.id 
          $where_clause
          ORDER BY FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), jam_mulai ASC";
$jadwal = query($query);
$kelas_list = query("SELECT * FROM kelas ORDER BY nama_kelas ASC");

require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Jadwal Pelajaran</h1>
    <?php if ($_SESSION['role'] == 'admin'): ?>
        <a href="create.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Tambah Jadwal</a>
    <?php endif; ?>
</div>

<?php display_flash(); ?>

<div class="card shadow mb-4">
    <div class="card-header bg-white py-3">
        <form action="" method="get" class="row g-3 align-items-center">
            <div class="col-auto">
                <label class="col-form-label fw-bold">Filter Kelas:</label>
            </div>
            <div class="col-auto">
                <select name="kelas_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kelas</option>
                    <?php foreach ($kelas_list as $k): ?>
                        <option value="<?= $k['id']; ?>" <?= ($filter_kelas == $k['id']) ? 'selected' : ''; ?>>
                            <?= $k['nama_kelas']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru Pengajar</th>
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                            <th>Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jadwal as $row): ?>
                        <tr>
                            <td class="fw-bold text-primary">
                                <?= $row['hari']; ?>
                            </td>
                            <td>
                                <?= date('H:i', strtotime($row['jam_mulai'])) . ' - ' . date('H:i', strtotime($row['jam_selesai'])); ?>
                            </td>
                            <td><span class="badge bg-info text-dark">
                                    <?= $row['nama_kelas']; ?>
                                </span></td>
                            <td>
                                <?= $row['nama_mapel']; ?>
                            </td>
                            <td>
                                <?= $row['nama_guru']; ?>
                            </td>
                            <?php if ($_SESSION['role'] == 'admin'): ?>
                                <td>
                                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning text-white"><i
                                            class="fas fa-edit"></i></a>
                                    <a href="?delete=<?= $row['id']; ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Hapus jadwal ini?');"><i class="fas fa-trash"></i></a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>