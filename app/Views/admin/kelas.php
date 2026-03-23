<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manajemen Data Kelas</h2>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus"></i> Tambah Kelas</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Kelas</th>
                        <th>Wali Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($kelas)): ?>
                        <tr><td colspan="3" class="text-center">Belum ada data kelas.</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach($kelas as $k): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($k['nama_kelas']) ?></td>
                            <td><?= esc($k['wali_kelas']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Kelas -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/kelas/store" method="POST">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Nama Kelas</label>
                        <input type="text" name="nama_kelas" class="form-control" placeholder="Contoh: 1A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Wali Kelas (Pilih Guru)</label>
                        <input type="text" name="wali_kelas" class="form-control" placeholder="Masukkan nama wali kelas" required>
                        <small class="text-muted">Nama ini harus sama persis dengan nama di Data Guru.</small>
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
