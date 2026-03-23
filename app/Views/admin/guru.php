<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manajemen Data Guru</h2>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus"></i> Tambah Guru</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Nomor Induk (NIP)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($guru)): ?>
                        <tr><td colspan="4" class="text-center">Belum ada data guru.</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach($guru as $g): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($g['nama']) ?></td>
                            <td><?= esc($g['nomor_induk']) ?></td>
                            <td>
                                <form action="/admin/guru/delete/<?= $g['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus guru ini?');">
                                    <?= csrf_field() ?>
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

<!-- Modal Tambah Guru -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Guru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/guru/store" method="POST">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Nama Guru</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Induk (NIP)</label>
                        <input type="text" name="nomor_induk" class="form-control" required>
                        <small class="text-muted">Masukkan 18 digit angka.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Sementara</label>
                        <input type="password" name="password" class="form-control" minlength="6" value="123456" required>
                        <small class="text-muted">Default: 123456</small>
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
