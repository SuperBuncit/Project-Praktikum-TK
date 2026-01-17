<?php
session_start();
require_once 'config/config.php';
require_once 'helpers/functions.php';

check_login();

// Data Summary (Contoh untuk Admin)
$jml_siswa = 0;
$jml_guru = 0;
$jml_kelas = 0;
$data_mapel = [];

if ($_SESSION['role'] == 'admin') {
    $siswa = query("SELECT COUNT(*) as total FROM data_siswa");
    $jml_siswa = $siswa[0]['total'];

    $guru = query("SELECT COUNT(*) as total FROM data_guru");
    $jml_guru = $guru[0]['total'];

    $kelas = query("SELECT COUNT(*) as total FROM kelas");
    $jml_kelas = $kelas[0]['total'];

    // Data Chart (Siswa per Kelas)
    $kelas_data = query("SELECT kelas.nama_kelas, COUNT(data_siswa.id) as total_siswa 
                         FROM kelas 
                         LEFT JOIN data_siswa ON kelas.id = data_siswa.kelas_id 
                         GROUP BY kelas.id");
    
    $labels = array_column($kelas_data, 'nama_kelas');
    $totals = array_column($kelas_data, 'total_siswa');

    $data_mapel = query("SELECT * FROM mata_pelajaran");
}

require_once 'layouts/header.php';
require_once 'layouts/sidebar.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom fade-in">
    <h1 class="h2 text-dark fw-bold">Dashboard</h1>
</div>

<?php display_flash(); ?>

<div class="row fade-in">
    <div class="col-md-12 mb-4">
        <div class="card shadow border-0 bg-white" style="border-radius: 15px;">
            <div class="card-body p-4">
                <h4 class="fw-bold text-primary mb-2">Selamat Datang, <?= ucfirst($_SESSION['username']); ?>!</h4>
                <p class="text-muted mb-0">Anda login sebagai <span class="badge bg-info text-white"><?= ucfirst($_SESSION['role']); ?></span>. Selamat bekerja dan semoga harimu menyenangkan!</p>
            </div>
        </div>
    </div>
</div>

<?php if ($_SESSION['role'] == 'admin') : ?>
<!-- Statistik Cards -->
<div class="row fade-in">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow h-100 py-2 card-dashboard" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-uppercase fw-bold mb-1" style="font-size: 0.8rem; letter-spacing: 1px; opacity: 0.9;">Total Siswa</div>
                        <div class="h2 mb-0 fw-bold"><?= $jml_siswa; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-3x" style="opacity: 0.4;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow h-100 py-2 card-dashboard" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-uppercase fw-bold mb-1" style="font-size: 0.8rem; letter-spacing: 1px; opacity: 0.9;">Total Guru</div>
                        <div class="h2 mb-0 fw-bold"><?= $jml_guru; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chalkboard-teacher fa-3x" style="opacity: 0.4;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow h-100 py-2 card-dashboard" style="background: linear-gradient(135deg, #ff5f6d 0%, #ffc371 100%); color: white;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-uppercase fw-bold mb-1" style="font-size: 0.8rem; letter-spacing: 1px; opacity: 0.9;">Total Kelas</div>
                        <div class="h2 mb-0 fw-bold"><?= $jml_kelas; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-school fa-3x" style="opacity: 0.4;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow h-100 py-2 card-dashboard" style="background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%); color: white;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-uppercase fw-bold mb-1" style="font-size: 0.8rem; letter-spacing: 1px; opacity: 0.9;">Mapel</div>
                        <div class="h2 mb-0 fw-bold"><?= count($data_mapel); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-book fa-3x" style="opacity: 0.4;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row fade-in">
    <div class="col-lg-8 mb-4">
        <div class="card shadow border-0 mb-4">
            <div class="card-header py-3 bg-white d-flex flex-row align-items-center justify-content-between border-0">
                <h6 class="m-0 fw-bold text-primary">Statistik Siswa per Kelas</h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="position: relative; height: 300px;">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card shadow border-0 mb-4 h-100">
             <div class="card-header py-3 bg-white border-0">
                <h6 class="m-0 fw-bold text-primary">Mapel Tersedia</h6>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php 
                    // Limit to 5
                    $limit_mapel = array_slice($data_mapel, 0, 5);
                    foreach($limit_mapel as $m): 
                    ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                        <?= $m['nama_mapel']; ?>
                        <span class="badge bg-primary rounded-pill"><?= $m['kode_mapel']; ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple Chart Script
    const ctx = document.getElementById('myChart');
    const myChart = new Chart(ctx, {
        type: 'bar', // Change to 'bar' for better view
        data: {
            labels: <?= json_encode($labels); ?>,
            datasets: [{
                label: 'Jumlah Siswa',
                data: <?= json_encode($totals); ?>,
                backgroundColor: 'rgba(78, 115, 223, 0.7)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1,
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
<?php endif; ?>

<?php require_once 'layouts/footer.php'; ?>