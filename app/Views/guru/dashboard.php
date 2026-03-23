<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">Dashboard Guru</h2>
        <p class="text-muted">Selamat datang, <?= esc(session()->get('nama')) ?>!</p>
    </div>
</div>

<?php if($kelas): ?>
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card bg-primary text-white shadow-sm h-100 border-0">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="fs-1 me-4"><i class="fas fa-school"></i></div>
                <div>
                    <h5 class="card-title fw-normal mb-1">Wali Kelas</h5>
                    <h2 class="fw-bold mb-0">Kelas <?= esc($kelas['nama_kelas']) ?></h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card bg-success text-white shadow-sm h-100 border-0">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="fs-1 me-4"><i class="fas fa-check-double"></i></div>
                <div>
                    <h5 class="card-title fw-normal mb-1">Total Kehadiran Hari Ini</h5>
                    <h2 class="fw-bold mb-0"><?= esc($absensi_hari_ini) ?> Siswa</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white pb-0 border-0 pt-4">
        <h5 class="fw-bold"><i class="fas fa-bullhorn text-warning me-2"></i> Pengumuman</h5>
    </div>
    <div class="card-body">
        <p>Anda ditugaskan sebagai Wali Kelas <strong><?= esc($kelas['nama_kelas']) ?></strong>. Anda dapat melihat absensi harian dan merekapitulasi data kehadiran siswa di kelas Anda setiap bulannya. Pastikan untuk selalu memantau tingkat kehadiran siswa.</p>
        <a href="/guru/absensi" class="btn btn-outline-primary mt-2"><i class="fas fa-arrow-right me-2"></i> Lihat Absensi Hari Ini</a>
    </div>
</div>

<?php else: ?>
<div class="alert alert-warning shadow-sm border-0" role="alert">
    <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i> Belum Ada Kelas!</h4>
    <p>Anda belum ditugaskan sebagai wali kelas untuk kelas manapun. Silakan hubungi Administrator untuk pengaturan lebih lanjut.</p>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
