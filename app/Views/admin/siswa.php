<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manajemen Data Siswa</h2>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus"></i> Tambah Siswa</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Nomor Induk</th>
                        <th>Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($siswa)): ?>
                        <tr><td colspan="5" class="text-center">Belum ada data siswa.</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach($siswa as $s): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($s['nama']) ?></td>
                            <td><?= esc($s['nomor_induk']) ?></td>
                            <td><?= esc($s['nama_kelas'] ?? '-') ?></td>
                            <td>
                                <form action="/admin/siswa/delete/<?= $s['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus siswa ini?');">
                                    <?= csrf_field() ?>
                                    <!-- Use DELETE method workaround for full security if needed, but post is ok for simplicity -->
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Siswa -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/siswa/store" method="POST">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Nama Siswa</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Induk (NIS / NISN)</label>
                        <input type="text" name="nomor_induk" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Sementara</label>
                        <input type="password" name="password" class="form-control" minlength="6" value="123456" required>
                        <small class="text-muted">Default: 123456</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach($kelas as $k): ?>
                                <option value="<?= $k['id'] ?>"><?= esc($k['nama_kelas']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
