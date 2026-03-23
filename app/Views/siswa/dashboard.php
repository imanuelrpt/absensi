<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row mb-5 text-center mt-3">
    <div class="col-12">
        <h1 class="fw-bold text-primary">Hai, <?= esc(session()->get('nama')) ?>! 👋</h1>
        <p class="text-muted fs-5">Selamat Datang di Portal Siswa SMP Cerdas Bangsa</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card bg-white shadow-lg border-0 mb-4 rounded-4 overflow-hidden">
            <div class="card-header bg-primary text-white text-center py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-alt me-2"></i> Kehadiran Hari Ini: <?= date('d F Y') ?></h5>
            </div>
            <div class="card-body p-5 text-center bg-light">
                <h4 class="text-muted mb-4">Status Anda Saat Ini:</h4>
                
                <?php if($status_absen === 'Belum Absen'): ?>
                    <div class="display-3 mb-3 text-secondary"><i class="fas fa-question-circle"></i></div>
                    <h2 class="fw-bold text-dark mb-4">Belum Absen</h2>
                    <a href="/siswa/scan" class="btn btn-warning btn-lg px-5 shadow rounded-pill fw-bold">
                        <i class="fas fa-qrcode me-2"></i> Scan QR Sekarang
                    </a>
                <?php else: ?>
                    <?php if($status_absen === 'hadir'): ?>
                        <div class="display-1 mb-3 text-success animate__animated animate__bounceIn"><i class="fas fa-check-circle"></i></div>
                        <h1 class="fw-bold text-success mb-2">HADIR</h1>
                    <?php elseif($status_absen === 'terlambat'): ?>
                        <div class="display-1 mb-3 text-warning"><i class="fas fa-exclamation-circle"></i></div>
                        <h1 class="fw-bold text-warning mb-2">TERLAMBAT</h1>
                    <?php endif; ?>
                    
                    <h5 class="text-muted mt-3">Waktu Scan: <span class="fw-bold text-dark"><?= esc($jam_absen) ?></span></h5>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5 justify-content-center">
    <div class="col-md-10 text-center">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100 p-4 hover-shadow transition">
                    <div class="text-primary mb-3 fs-1"><i class="fas fa-camera"></i></div>
                    <h4 class="fw-bold">Fitur Canggih</h4>
                    <p class="text-muted">Gunakan kamera HP/Laptop kamu untuk menscan QR Code yang disediakan oleh Guru/Admin secara instan.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100 p-4 hover-shadow transition">
                    <div class="text-info mb-3 fs-1"><i class="fas fa-history"></i></div>
                    <h4 class="fw-bold">Transparan</h4>
                    <p class="text-muted">Pantau terus riwayat kehadiranmu dari hari ke hari dan pastikan tidak ada data yang terlewat.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .transition {
        transition: all 0.3s ease;
    }
</style>
<?= $this->endSection() ?>
