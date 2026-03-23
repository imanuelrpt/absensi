<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row mb-4 mt-3">
    <div class="col-12 text-center">
        <h2 class="fw-bold">
            <i class="fas fa-qrcode text-primary me-2"></i> QR Code Personal
        </h2>
        <p class="text-muted">Gunakan QR Code di bawah ini sebagai identitas absen digital Anda.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-5 text-center">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <div class="mb-3">
                    <i class="fas fa-user-circle text-primary" style="font-size: 3rem;"></i>
                </div>
                <h5 class="card-title fw-bold mb-1"><?= esc(session()->get('nama')) ?></h5>
                <p class="text-muted mb-4"><?= esc(session()->get('nomor_induk')) ?></p>
                
                <div class="bg-light p-3 rounded mb-4 d-inline-block border">
                    <img src="<?= base_url('/siswa/my-qr-image') ?>" alt="QR Code Siswa" class="img-fluid" style="border-radius: 10px;">
                </div>
                
                <?php
                // Text for WA Share
                $waText = "Halo, ini adalah QR Code identitas untuk absensi sekolah atas nama: \n*" . session()->get('nama') . "* \n(NISN: " . session()->get('nomor_induk') . ")\n\nHarap simpan gambar QR (jika dilampirkan) untuk proses scan di sekolah.";
                $waUrl = "https://wa.me/?text=" . urlencode($waText);
                ?>
                
                <div class="d-grid gap-2">
                    <a href="<?= base_url('/siswa/my-qr-image') ?>" download="QR_<?= esc(session()->get('nomor_induk')) ?>.png" class="btn btn-primary shadow-sm">
                        <i class="fas fa-download me-2"></i> Unduh / Simpan Gambar QR
                    </a>
                    
                    <a href="<?= $waUrl ?>" target="_blank" class="btn btn-success shadow-sm">
                        <i class="fab fa-whatsapp me-2"></i> Bagikan ke WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
