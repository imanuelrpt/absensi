<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row mb-4 mt-3">
    <div class="col-12 text-center">
        <h2 class="fw-bold"><i class="fas fa-camera text-success me-2"></i> Daftar Wajah</h2>
        <p class="text-muted">Daftarkan wajah Anda agar bisa absensi menggunakan deteksi wajah.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6 text-center">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div id="status-msg" class="alert d-none mb-3"></div>

                <div class="position-relative d-inline-block mb-3">
                    <video id="video" width="100%" style="max-width:400px; border-radius:12px; border:3px solid #0d6efd;" autoplay muted></video>
                    <canvas id="overlay" style="position:absolute;top:0;left:0;"></canvas>
                </div>
                <canvas id="capture-canvas" style="display:none;"></canvas>

                <div class="d-grid gap-2 mt-3">
                    <button id="btn-daftar" class="btn btn-success btn-lg" disabled>
                        <i class="fas fa-user-check me-2"></i> Daftarkan Wajah Saya
                    </button>
                </div>
                <small class="text-muted d-block mt-2">Pastikan wajah Anda terlihat jelas di kamera sebelum mendaftar.</small>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
const video       = document.getElementById('video');
const overlay     = document.getElementById('overlay');
const btnDaftar   = document.getElementById('btn-daftar');
const statusMsg   = document.getElementById('status-msg');

function showMsg(msg, type = 'info') {
    statusMsg.className = `alert alert-${type} mb-3`;
    statusMsg.textContent = msg;
    statusMsg.classList.remove('d-none');
}

async function startCamera() {
    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
    video.srcObject = stream;
}

async function loadModels() {
    showMsg('Memuat model AI deteksi wajah...', 'info');
    const MODEL_URL = 'https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js/weights';
    await Promise.all([
        faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
        faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
        faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL),
    ]);
    showMsg('Model siap. Arahkan wajah ke kamera.', 'success');
    startDetection();
}

let currentDescriptor = null;

function startDetection() {
    video.addEventListener('play', async () => {
        overlay.width  = video.videoWidth;
        overlay.height = video.videoHeight;
        const ctx = overlay.getContext('2d');

        setInterval(async () => {
            const detections = await faceapi
                .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceDescriptors();

            ctx.clearRect(0, 0, overlay.width, overlay.height);
            faceapi.draw.drawDetections(overlay, detections);

            if (detections.length === 1) {
                currentDescriptor = Array.from(detections[0].descriptor);
                btnDaftar.disabled = false;
            } else {
                currentDescriptor = null;
                btnDaftar.disabled = true;
            }
        }, 500);
    });
}

btnDaftar.addEventListener('click', async () => {
    if (!currentDescriptor) return;
    btnDaftar.disabled = true;
    showMsg('Menyimpan data wajah...', 'info');

    const formData = new FormData();
    formData.append('descriptor', JSON.stringify(currentDescriptor));

    const res = await fetch('/siswa/simpan-wajah', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    });
    const data = await res.json();

    if (data.status === 'success') {
        showMsg('✅ ' + data.message, 'success');
    } else {
        showMsg('❌ ' + data.message, 'danger');
        btnDaftar.disabled = false;
    }
});

startCamera().then(loadModels);
</script>
<?= $this->endSection() ?>
