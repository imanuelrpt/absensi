<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Absensi Kelas: <span class="text-primary"><?= esc($kelas['nama_kelas']) ?></span> (<?= date('d M Y') ?>)</h2>
    <a href="/guru/rekap" class="btn btn-outline-secondary"><i class="fas fa-calendar-alt me-2"></i> Lihat Rekap Bulanan</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive mt-3">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Waktu Presensi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($absensi)): ?>
                        <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada siswa yang melakukan absensi hari ini.</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach($absensi as $a): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="fw-bold"><?= esc($a['nama_siswa']) ?></td>
                            <td class="text-muted"><i class="far fa-clock me-1"></i> <?= date('H:i:s', strtotime($a['jam_masuk'])) ?></td>
                            <td>
                                <?php if($a['status'] === 'hadir'): ?>
                                    <span class="badge bg-success rounded-pill px-3 py-2"><i class="fas fa-check me-1"></i> Hadir</span>
                                <?php elseif($a['status'] === 'terlambat'): ?>
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="fas fa-exclamation-triangle me-1"></i> Terlambat</span>
                                <?php else: ?>
                                    <span class="badge bg-danger rounded-pill px-3 py-2"><i class="fas fa-times me-1"></i> Tidak Hadir</span>
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
