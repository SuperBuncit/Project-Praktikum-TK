<?php
$role = $_SESSION['role'] ?? '';
?>
<!-- Sidebar -->
<div class="bg-primary border-right d-flex flex-column" id="sidebar-wrapper"
    style="min-width: 250px; height: 100vh; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; position: sticky; top: 0; transition: margin 0.3s ease-in-out;">
    <div class="sidebar-heading py-4 primary-text fs-4 fw-bold text-white text-uppercase border-bottom px-4">
        <div class="d-flex justify-content-between align-items-center w-100 user-select-none">
            <div class="d-flex align-items-center brand-container">
                <i class="fas fa-school me-2 fs-4"></i>
                <span class="fs-5 brand-text">TK Modern</span>
            </div>
            <div class="sidebar-toggle-btn text-white ps-2" style="cursor: pointer;">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </div>

    <div class="list-group list-group-flush my-3 flex-grow-1 overflow-auto custom-scrollbar">
        <a href="<?= base_url('dashboard.php'); ?>"
            class="list-group-item list-group-item-action bg-transparent text-white border-0 fw-bold">
            <i class="fas fa-tachometer-alt me-2"></i><span>Dashboard</span>
        </a>

        <?php if ($role == 'admin'): ?>
            <div class="sidebar-heading text-white-50 px-3 mt-3 mb-1 text-uppercase small font-weight-bold"><span>Master
                    Data</span>
            </div>

            <a href="<?= base_url('modules/siswa/index.php'); ?>"
                class="list-group-item list-group-item-action bg-transparent text-white border-0">
                <i class="fas fa-user-graduate me-2"></i><span>Data Siswa</span>
            </a>
            <a href="<?= base_url('modules/guru/index.php'); ?>"
                class="list-group-item list-group-item-action bg-transparent text-white border-0">
                <i class="fas fa-chalkboard-teacher me-2"></i><span>Data Guru</span>
            </a>
            <a href="<?= base_url('modules/kelas/index.php'); ?>"
                class="list-group-item list-group-item-action bg-transparent text-white border-0">
                <i class="fas fa-school me-2"></i><span>Data Kelas</span>
            </a>
            <a href="<?= base_url('modules/mapel/index.php'); ?>"
                class="list-group-item list-group-item-action bg-transparent text-white border-0">
                <i class="fas fa-book me-2"></i><span>Mata Pelajaran</span>
            </a>
        <?php endif; ?>

        <?php if ($role == 'admin' || $role == 'guru'): ?>
            <div class="sidebar-heading text-white-50 px-3 mt-3 mb-1 text-uppercase small font-weight-bold">
                <span>Akademik</span>
            </div>

            <a href="<?= base_url('modules/jadwal/index.php'); ?>"
                class="list-group-item list-group-item-action bg-transparent text-white border-0">
                <i class="fas fa-calendar-alt me-2"></i><span>Jadwal Pelajaran</span>
            </a>

            <div class="sidebar-heading text-white-50 px-3 mt-3 mb-1 text-uppercase small font-weight-bold">
                <span>Monitoring</span>
            </div>

            <a href="<?= base_url('modules/poin/index.php'); ?>"
                class="list-group-item list-group-item-action bg-transparent text-white border-0">
                <i class="fas fa-star me-2"></i><span>Poin & Prestasi</span>
            </a>
            <a href="<?= base_url('modules/absensi/index.php'); ?>"
                class="list-group-item list-group-item-action bg-transparent text-white border-0">
                <i class="fas fa-user-check me-2"></i><span>Absensi</span>
            </a>
        <?php endif; ?>

        <!-- Menu Laporan (Baru) -->
        <?php if ($role == 'admin' || $role == 'guru'): ?>
            <div class="sidebar-heading text-white-50 px-3 mt-3 mb-1 text-uppercase small font-weight-bold">
                <span>Laporan</span>
            </div>
            <a href="<?= base_url('modules/laporan/index.php'); ?>"
                class="list-group-item list-group-item-action bg-transparent text-white border-0">
                <i class="fas fa-print me-2"></i><span>Pusat Laporan</span>
            </a>
        <?php endif; ?>

        <?php if ($role == 'siswa'): ?>
            <div class="sidebar-heading text-white-50 px-3 mt-3 mb-1 text-uppercase small font-weight-bold"><span>Menu
                    Siswa</span></div>

            <a href="<?= base_url('modules/jadwal/index.php'); ?>"
                class="list-group-item list-group-item-action bg-transparent text-white border-0">
                <i class="fas fa-calendar-alt me-2"></i><span>Jadwal Saya</span>
            </a>
            <a href="<?= base_url('modules/poin/view.php'); ?>"
                class="list-group-item list-group-item-action bg-transparent text-white border-0">
                <i class="fas fa-star me-2"></i><span>Poin Saya</span>
            </a>
        <?php endif; ?>
    </div>

    <!-- Fixed Logout Button at Bottom -->
    <div class="p-3 border-top border-white-50">
        <a href="<?= base_url('logout.php'); ?>"
            class="btn btn-danger w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center"
            style="background: linear-gradient(135deg, #ff5f6d 0%, #ffc371 100%); border: none;">
            <i class="fas fa-sign-out-alt"></i> <span class="ms-2">Logout</span>
        </a>
    </div>
</div>
<!-- /#sidebar-wrapper -->

<!-- Page Content -->
<div id="page-content-wrapper" class="w-100">
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 shadow-sm">
        <div class="d-flex align-items-center">
            <h2 class="fs-5 m-0 fw-bold text-primary">Sistem Manajemen Sekolah</h2>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle second-text fw-bold text-dark" href="#" id="navbarDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-2 fa-lg text-primary"></i>
                        <?= $_SESSION['username'] ?? 'User' ?>
                        <span class="badge bg-primary ms-1"><?= ucfirst($_SESSION['role'] ?? '') ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?= base_url('profile.php'); ?>"><i
                                    class="fas fa-user me-2"></i>Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="<?= base_url('logout.php') ?>"><i
                                    class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">