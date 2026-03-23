<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <h2 class="mb-4 text-center">Generate QR Code Absensi Harian</h2>
        
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body p-5">
                <h4 class="card-title fw-bold mb-3">Tanggal: <?= date('d F Y') ?></h4>
                <p class="text-muted mb-4">Jam Masuk: 07:00 | Batas Terlambat: 07:30</p>

                <?php if($qr_today): ?>
                    <div class="alert alert-info">QR Code untuk hari ini sudah ter-generate. Siswa dapat mulai absen.</div>
                    
                    <?php if(isset($qr_image)): ?>
                        <div class="bg-light p-4 rounded d-inline-block border shadow-sm">
                            <img src="<?= $qr_image ?>" alt="QR Code Harian" style="width: 300px; height: 300px;">
                        </div>
                        <div class="mt-4">
                            <a href="<?= $qr_image ?>" download="QR_Absen_<?= date('Ymd') ?>.png" class="btn btn-success"><i class="fas fa-download me-2"></i> Download / Print QR Code</a>
                            <p class="mt-3 text-muted"><strong>Kode QR (Text):</strong> <br><span class="user-select-all font-monospace text-dark bg-light p-2 rounded"><?= esc($qr_today['kode_qr']) ?></span></p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-warning mb-4"><i class="fas fa-exclamation-triangle"></i> Ops, QR Code untuk hari ini belum digenerate. Silakan generate sekarang.</div>
                    
                    <form action="/admin/generate-qr/process" method="POST">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow"><i class="fas fa-qrcode"></i> Generate QR Code Sekarang</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
