<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="fas fa-face-smile text-primary me-2"></i> Absensi Wajah Siswa</h2>
    <div class="text-muted">
        <i class="fas fa-clock me-1"></i> 
        Hari ini: <strong><?= date('d F Y') ?></strong> &nbsp;|&nbsp; 
        Total hari ini: <strong><?= $total_hari_ini ?> siswa</strong>
    </div>
</div>

<!-- Filter -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="/admin/absensi-wajah" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Filter Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="<?= esc($tanggal_filter) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Filter Kelas</label>
                <select name="kelas_id" class="form-select">
                    <option value="">-- Semua Kelas --</option>
                    <?php foreach ($kelas as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= ($kelas_filter == $k['id']) ? 'selected' : '' ?>>
                            <?= esc($k['nama_kelas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Absensi Wajah -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Foto</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($absensi)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fs-3 d-block mb-2"></i>
                                Belum ada data absensi wajah <?= ($tanggal_filter == date('Y-m-d')) ? 'hari ini' : 'pada tanggal ini' ?>.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($absensi as $a): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($a['nama_siswa']) ?></td>
                            <td><?= esc($a['nama_kelas'] ?? '-') ?></td>
                            <td><?= date('d/m/Y', strtotime($a['tanggal'])) ?></td>
                            <td><strong><?= substr($a['jam_masuk'], 0, 5) ?></strong></td>
                            <td>
                                <?php if($a['foto_absen']): ?>
                                    <img src="<?= $a['foto_absen'] ?>" alt="Foto Absen" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; cursor: pointer;" onclick="window.open(this.src)">
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($a['status'] === 'hadir'): ?>
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Hadir</span>
                                <?php elseif ($a['status'] === 'terlambat'): ?>
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Terlambat</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Tidak Hadir</span>
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
