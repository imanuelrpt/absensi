<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sistem Absensi') ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            color: #c2c7d0;
            text-decoration: none;
            padding: 10px 20px;
            display: block;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #495057;
            color: white;
            border-left: 3px solid #0d6efd;
        }
    </style>
</head>
<body>

<?php if(session()->get('isLoggedIn')): ?>
<div class="row g-0">
    <div class="col-md-2 sidebar d-none d-md-block">
        <h5 class="text-center mb-4">Absensi<br>SMP Cerdas Bangsa</h5>
        
        <?php $role = session()->get('role'); ?>
        
        <?php if($role === 'admin'): ?>
            <a href="/admin" class="<?= (uri_string() == 'admin') ? 'active' : '' ?>"><i class="fas fa-home me-2"></i> Dashboard</a>
            <a href="/admin/siswa" class="<?= (strpos(uri_string(), 'admin/siswa') !== false) ? 'active' : '' ?>"><i class="fas fa-users me-2"></i> Data Siswa</a>
            <a href="/admin/guru" class="<?= (strpos(uri_string(), 'admin/guru') !== false) ? 'active' : '' ?>"><i class="fas fa-chalkboard-teacher me-2"></i> Data Guru</a>
            <a href="/admin/kelas" class="<?= (strpos(uri_string(), 'admin/kelas') !== false) ? 'active' : '' ?>"><i class="fas fa-school me-2"></i> Data Kelas</a>
            <a href="/admin/generate-qr" class="<?= (strpos(uri_string(), 'admin/generate-qr') !== false) ? 'active' : '' ?>"><i class="fas fa-qrcode me-2"></i> Generate QR</a>
            <a href="/admin/absensi-wajah" class="<?= (strpos(uri_string(), 'absensi-wajah') !== false) ? 'active' : '' ?>"><i class="fas fa-face-smile me-2"></i> Absensi Wajah</a>
            <a href="/admin/laporan" class="<?= (strpos(uri_string(), 'admin/laporan') !== false) ? 'active' : '' ?>"><i class="fas fa-file-alt me-2"></i> Laporan</a>
        <?php elseif($role === 'guru'): ?>
            <a href="/guru" class="<?= (uri_string() == 'guru') ? 'active' : '' ?>"><i class="fas fa-home me-2"></i> Dashboard</a>
            <a href="/guru/absensi" class="<?= (strpos(uri_string(), 'guru/absensi') !== false) ? 'active' : '' ?>"><i class="fas fa-list-check me-2"></i> Absensi Kelas</a>
            <a href="/guru/rekap" class="<?= (strpos(uri_string(), 'guru/rekap') !== false) ? 'active' : '' ?>"><i class="fas fa-chart-bar me-2"></i> Rekap Absensi</a>
        <?php elseif($role === 'siswa'): ?>
            <a href="/siswa" class="<?= (uri_string() == 'siswa') ? 'active' : '' ?>"><i class="fas fa-home me-2"></i> Dashboard</a>
            <a href="/siswa/scan" class="<?= (strpos(uri_string(), 'siswa/scan') !== false) ? 'active' : '' ?>"><i class="fas fa-qrcode me-2"></i> Scan QR</a>
            <a href="/siswa/absensi-wajah" class="<?= (strpos(uri_string(), 'absensi-wajah') !== false) ? 'active' : '' ?>"><i class="fas fa-face-smile me-2"></i> Absensi Wajah</a>
            <a href="/siswa/riwayat" class="<?= (strpos(uri_string(), 'siswa/riwayat') !== false) ? 'active' : '' ?>"><i class="fas fa-history me-2"></i> Riwayat Absensi</a>
        <?php endif; ?>
        
        <a href="/logout" class="text-danger mt-5"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
    </div>

    <!-- Mobile Navbar -->
    <div class="col-12 d-md-none bg-dark text-white p-3 d-flex justify-content-between">
        <strong>Absensi SMP CB</strong>
        <button class="btn btn-outline-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    <div class="collapse d-md-none bg-dark" id="mobileMenu">
        <!-- Duplicate mobile menu identical to sidebar could go here -->
        <div class="p-3 text-white">Silakan gunakan layar lebih lebar untuk pengalaman optimal atau menu dropdown. <br><a href="/logout" class="text-danger">Logout</a></div>
    </div>

    <div class="col-md-10 p-4">
        <!-- Flash Messages -->
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>
</div>

<?php else: ?>
    <!-- For Login/Register views without sidebar -->
    <div class="container h-100 mt-5">
        <!-- Flash Messages -->
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>
<?php endif; ?>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
