<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
    <h2>Rekap Absensi: Kelas <span class="text-primary"><?= esc($kelas['nama_kelas']) ?></span></h2>
    <button onclick="window.print()" class="btn btn-success shadow-sm"><i class="fas fa-print me-2"></i> Export / Print</button>
</div>

<!-- Laporan Print Header -->
<div class="d-none d-print-block text-center mb-4">
    <h3 class="mb-1">LAPORAN ABSENSI SISWA</h3>
    <h5 class="mb-1"><strong>SMP NEGERI CERDAS BANGSA</strong></h5>
    <p class="mb-0">Kelas: <?= esc($kelas['nama_kelas']) ?> | Wali Kelas: <?= esc(session()->get('nama')) ?></p>
    <p class="mb-0">Bulan: <?= date('F Y', strtotime($bulan_filter . '-01')) ?></p>
    <hr style="border-top: 2px solid #000;">
</div>

<div class="card shadow-sm border-0 mb-4 d-print-none">
    <div class="card-body bg-light rounded">
        <form action="/guru/rekap" method="GET" class="d-flex align-items-end gap-3">
            <div>
                <label class="form-label fw-bold">Pilih Bulan</label>
                <input type="month" name="bulan" class="form-control" value="<?= esc($bulan_filter) ?>" required>
            </div>
            <div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-2"></i> Tampilkan Rekap</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>No</th>
                        <th class="text-start">Nama Siswa</th>
                        <th>Tanggal</th>
                        <th>Waktu Scan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($absensi)): ?>
                        <tr><td colspan="5" class="py-4 text-muted">Tidak ada data absensi pada bulan ini.</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach($absensi as $a): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="text-start fw-bold"><?= esc($a['nama_siswa']) ?></td>
                            <td><?= date('d M Y', strtotime($a['tanggal'])) ?></td>
                            <td class="font-monospace text-muted"><?= date('H:i:s', strtotime($a['jam_masuk'])) ?></td>
                            <td>
                                <?php if($a['status'] === 'hadir'): ?>
                                    <span class="text-success fw-bold d-print-none">Hadir</span>
                                    <span class="d-none d-print-inline">Hadir</span>
                                <?php elseif($a['status'] === 'terlambat'): ?>
                                    <span class="text-warning fw-bold d-print-none">Terlambat</span>
                                    <span class="d-none d-print-inline">Terlambat</span>
                                <?php else: ?>
                                    <span class="text-danger fw-bold d-print-none">Tidak Hadir</span>
                                    <span class="d-none d-print-inline">Tidak Hadir</span>
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
