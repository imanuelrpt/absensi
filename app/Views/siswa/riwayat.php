<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">Riwayat Kehadiran Anda</h2>
        <p class="text-muted">Lihat semua catatan masuk dari akun Anda.</p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle text-center">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Metode</th>
                        <th>Foto</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($absensi)): ?>
                        <tr><td colspan="6" class="py-5 text-muted">Belum ada riwayat absensi yang tersimpan.</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach($absensi as $a): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="fw-bold"><?= date('d M Y', strtotime($a['tanggal'])) ?></td>
                            <td class="font-monospace text-muted"><?= date('H:i', strtotime($a['jam_masuk'])) ?></td>
                            <td>
                                <?php if($a['metode'] === 'wajah'): ?>
                                    <span class="badge bg-info text-dark"><i class="fas fa-face-smile me-1"></i> Wajah</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><i class="fas fa-qrcode me-1"></i> QR</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($a['metode'] === 'wajah' && $a['foto_absen']): ?>
                                    <img src="<?= $a['foto_absen'] ?>" alt="Foto Absen" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; cursor: pointer;" onclick="window.open(this.src)">
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($a['status'] === 'hadir'): ?>
                                    <span class="badge bg-success rounded-pill px-3 py-2"><i class="fas fa-check me-1"></i> Hadir</span>
                                <?php elseif($a['status'] === 'terlambat'): ?>
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="fas fa-exclamation me-1"></i> Terlambat</span>
                                <?php else: ?>
                                    <span class="badge bg-danger rounded-pill px-3 py-2"><i class="fas fa-times me-1"></i> Alpa</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
