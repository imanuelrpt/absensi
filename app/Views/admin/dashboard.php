<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<h2 class="mb-4">Dashboard Admin</h2>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white h-100 shadow-sm">
            <div class="card-body">
                <h6 class="card-title"><i class="fas fa-users"></i> Total Siswa</h6>
                <h3 class="fw-bold"><?= $total_siswa ?></h3>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white h-100 shadow-sm">
            <div class="card-body">
                <h6 class="card-title"><i class="fas fa-chalkboard-teacher"></i> Total Guru</h6>
                <h3 class="fw-bold"><?= $total_guru ?></h3>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white h-100 shadow-sm">
            <div class="card-body">
                <h6 class="card-title"><i class="fas fa-check-circle"></i> Siswa Hadir Hari Ini</h6>
                <h3 class="fw-bold"><?= $siswa_hadir ?></h3>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-dark h-100 shadow-sm">
            <div class="card-body">
                <h6 class="card-title"><i class="fas fa-qrcode"></i> QR Code Hari Ini</h6>
                <h3 class="fw-bold"><?= $qr_aktif ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-info-circle text-primary"></i> Informasi Sistem
            </div>
            <div class="card-body">
                <p>Selamat datang di panel Administrator Sistem Absensi Siswa berbasis QR Code.</p>
                <ul>
                    <li>Gunakan menu <strong>Data Siswa</strong>, <strong>Guru</strong>, dan <strong>Kelas</strong> untuk mengelola data master.</li>
                    <li>Gunakan menu <strong>Generate QR</strong> SETIAP HARI untuk membuat QR code absensi yang akan discan oleh siswa.</li>
                    <li>Lihat rekapan absensi keseluruhan melalui menu <strong>Laporan</strong>.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php 
$qrIsAktif = ($qr_aktif === 'Ada');
?>

<div class="row mt-4">
    <div class="col-12">
        <?php if ($qrIsAktif): ?>
            <div class="alert alert-success d-flex align-items-center shadow-sm">
                <i class="fas fa-circle-check fs-4 me-3"></i>
                <div>
                    <strong>Sesi Absensi Hari Ini: AKTIF ✅</strong><br>
                    <small>Siswa sudah bisa melakukan absensi via QR Code maupun Deteksi Wajah.</small>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger d-flex align-items-center justify-content-between shadow-sm">
                <div>
                    <i class="fas fa-triangle-exclamation fs-4 me-3"></i>
                    <strong>Sesi Absensi Hari Ini: BELUM AKTIF ⚠️</strong><br>
                    <small>Siswa belum bisa absensi. Aktifkan sesi sekarang.</small>
                </div>
                <form action="/admin/quick-activate" method="POST" class="ms-3">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-light fw-bold px-4 shadow-sm text-danger">
                        <i class="fas fa-bolt me-2"></i> Aktifkan Sesi Sekarang
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
