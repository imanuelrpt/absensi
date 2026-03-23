<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Laporan Absensi Siswa</h2>
    <button onclick="window.print()" class="btn btn-warning shadow-sm"><i class="fas fa-print"></i> Cetak PDF/Laporan</button>
</div>

<div class="card shadow-sm border-0 mb-4 d-print-none">
    <div class="card-body bg-light">
        <form action="/admin/laporan" method="GET" class="row align-items-end g-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">Pilih Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="<?= esc($tanggal_filter) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Pilih Kelas</label>
                <select name="kelas_id" class="form-select">
                    <option value="">-- Semua Kelas --</option>
                    <?php foreach($kelas as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= ($kelas_filter == $k['id']) ? 'selected' : '' ?>><?= esc($k['nama_kelas']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-2"></i> Filter Data</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">
        <h5 class="text-center fw-bold mb-4">Data Kehadiran Tanggal: <span class="text-primary"><?= esc($tanggal_filter) ?></span></h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="bg-primary text-white text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Waktu Scan</th>
                        <th>Foto Scan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($absensi)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data absensi untuk filter ini.</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach($absensi as $a): ?>
                        <tr class="text-center">
                            <td><?= $no++ ?></td>
                            <td class="text-start fw-bold"><?= esc($a['nama_siswa']) ?></td>
                            <td><?= esc($a['nama_kelas'] ?? '-') ?></td>
                            <td class="font-monospace text-muted"><?= date('H:i:s', strtotime($a['jam_masuk'])) ?></td>
                            <td>
                                <?php if($a['foto_absen']): ?>
                                    <img src="<?= $a['foto_absen'] ?>" alt="Foto Absen" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; cursor: pointer;" onclick="window.open(this.src)">
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($a['status'] === 'hadir'): ?>
                                    <span class="badge bg-success w-100 py-2">Hadir</span>
                                <?php elseif($a['status'] === 'terlambat'): ?>
                                    <span class="badge bg-warning text-dark w-100 py-2">Terlambat</span>
                                <?php else: ?>
                                    <span class="badge bg-danger w-100 py-2">Tidak Hadir</span>
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
