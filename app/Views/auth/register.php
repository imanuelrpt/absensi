<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary">Registrasi Akun</h3>
                    <p class="text-muted">Buat akun untuk mengakses sistem absensi</p>
                </div>

                <form action="<?= site_url('register/process') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="nama" class="form-control" value="<?= old('nama') ?>" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor Induk / Username Admin</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input type="text" name="nomor_induk" class="form-control" value="<?= old('nomor_induk') ?>" required>
                        </div>
                        <small class="text-muted">NISN (Siswa) | NIP (Guru) | Ketik Username (Admin)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" id="kelasSelect" class="form-select">
                            <option value="">-- Pilih Kelas (Pilih 'Kosongkan' jika Anda Admin) --</option>
                            <?php foreach($kelas as $k): ?>
                                <option value="<?= $k['id'] ?>" <?= old('kelas_id') == $k['id'] ? 'selected' : '' ?>><?= esc($k['nama_kelas']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" required minlength="6">
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary shadow-sm"><i class="fas fa-user-plus me-2"></i> Register</button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    Sudah punya akun? <a href="/login" class="text-decoration-none">Login di sini</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
