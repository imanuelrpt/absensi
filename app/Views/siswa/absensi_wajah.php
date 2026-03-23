<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row mb-4 mt-3">
    <div class="col-12 text-center">
        <h2 class="fw-bold"><i class="fas fa-face-smile text-primary me-2"></i> Absensi Wajah</h2>
        <p class="text-muted">Arahkan wajah Anda ke kamera. Absensi akan otomatis tercatat jika wajah dikenali.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-7 text-center">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div id="status-msg" class="alert alert-info mb-3">Memuat model AI... Harap tunggu.</div>

                <div class="position-relative d-inline-block mb-3">
                    <video id="video" width="100%" style="max-width:480px; border-radius:12px; border:3px solid #0d6efd;" autoplay muted></video>
                    <canvas id="overlay" style="position:absolute;top:0;left:0;"></canvas>
                </div>

                <div id="result-box" class="d-none mt-3 p-3 rounded" style="font-size:1.2rem;font-weight:bold;"></div>

                <div class="d-grid mt-3">
                    <button id="btn-scan" class="btn btn-primary btn-lg" disabled>
                        <i class="fas fa-play-circle me-2"></i> Mulai Scan &amp; Absen
                    </button>
                </div>
                <small class="text-muted d-block mt-2">Pastikan pencahayaan cukup dan wajah terlihat jelas.</small>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
const video     = document.getElementById('video');
const overlay   = document.getElementById('overlay');
const btnScan   = document.getElementById('btn-scan');
const statusMsg = document.getElementById('status-msg');
const resultBox = document.getElementById('result-box');

function showMsg(msg, type = 'info') {
    statusMsg.className = `alert alert-${type} mb-3`;
    statusMsg.textContent = msg;
}

async function startCamera() {
    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
    video.srcObject = stream;
}

async function loadModels() {
    const MODEL_URL = 'https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js/weights';
    await Promise.all([
        faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
        faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
        faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL),
    ]);
    showMsg('Model siap. Klik tombol untuk mulai scan.', 'success');
    startPreview();
    btnScan.disabled = false;
}

function startPreview() {
    video.addEventListener('play', async () => {
        overlay.width  = video.videoWidth;
        overlay.height = video.videoHeight;
        const ctx = overlay.getContext('2d');

        setInterval(async () => {
            const detections = await faceapi
                .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks();
            ctx.clearRect(0, 0, overlay.width, overlay.height);
            faceapi.draw.drawDetections(overlay, detections);
        }, 400);
    });
}

btnScan.addEventListener('click', async () => {
    btnScan.disabled = true;
    showMsg('Mendeteksi wajah...', 'info');
    resultBox.classList.add('d-none');

    const detection = await faceapi
        .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
        .withFaceLandmarks();

    if (!detection) {
        showMsg('Wajah tidak terdeteksi. Pastikan wajah terlihat jelas lalu coba lagi.', 'warning');
        btnScan.disabled = false;
        return;
    }

    showMsg('Wajah terdeteksi! Mencatat absensi...', 'info');

    // Capture image
    const captureCanvas = document.createElement('canvas');
    captureCanvas.width  = video.videoWidth;
    captureCanvas.height = video.videoHeight;
    const captureCtx    = captureCanvas.getContext('2d');
    captureCtx.drawImage(video, 0, 0, captureCanvas.width, captureCanvas.height);
    const photoBase64   = captureCanvas.toDataURL('image/jpeg', 0.7);

    const formData = new FormData();
    formData.append('face_detected', '1');
    formData.append('foto_absen', photoBase64);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    const res  = await fetch('/siswa/proses-absensi-wajah', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    });
    const data = await res.json();

    resultBox.classList.remove('d-none');
    if (data.status === 'success') {
        resultBox.className = 'mt-3 p-3 rounded bg-success text-white';
        resultBox.innerHTML = `✅ ${data.message}`;
        showMsg('Absensi tercatat!', 'success');
    } else {
        resultBox.className = 'mt-3 p-3 rounded bg-danger text-white';
        resultBox.innerHTML = `❌ ${data.message}`;
        showMsg('Gagal mencatat absensi.', 'danger');
        btnScan.disabled = false;
    }
});

startCamera().then(loadModels);
</script>
<?= $this->endSection() ?>
