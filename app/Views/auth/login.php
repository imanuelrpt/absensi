<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary">Aplikasi Absensi</h3>
                    <p class="text-muted">Silakan login untuk melanjutkan</p>
                </div>

                <form action="<?= site_url('login/process') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Nomor Induk (NIP / NIS / Admin)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input type="text" name="nomor_induk" class="form-control" value="<?= old('nomor_induk') ?>" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary shadow-sm"><i class="fas fa-sign-in-alt me-2"></i> Login</button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    Belum punya akun? <a href="/register" class="text-decoration-none">Daftar sekarang</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
