<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8 text-center mb-4">
        <h2 class="fw-bold text-primary"><i class="fas fa-camera"></i> Arahkan Kamera ke QR Code</h2>
        <p class="text-muted">Scan QR Code absensi harian yang ditampilkan oleh Guru atau Admin.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
            <div class="card-body p-1 bg-dark">
                <!-- Area Kamera QR Code -->
                <div id="reader" style="width: 100%; border-radius: 10px; overflow: hidden;"></div>
            </div>
            <div class="card-footer bg-white text-center py-3">
                <div id="scan-result-alert" class="alert d-none mb-0 fw-bold" role="alert"></div>
                <div id="loading" class="d-none mt-2 text-primary">
                    <div class="spinner-border spinner-border-sm" role="status"></div> Sedang memproses...
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- html5-qrcode library -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const scannerElementId = 'reader';
        const resultAlert = document.getElementById('scan-result-alert');
        const loadingDiv = document.getElementById('loading');
        
        // Inisialisasi html5QrcodeScanner
        const html5QrcodeScanner = new Html5QrcodeScanner(
            scannerElementId,
            { 
                fps: 15, 
                qrbox: {width: 300, height: 300},
                aspectRatio: 1.0,
                rememberLastUsedCamera: true
            },
            /* verbose= */ false
        );

        html5QrcodeScanner.render(onScanSuccess, onScanFailure);

        let isScanning = false;

        function onScanSuccess(decodedText, decodedResult) {
            if (isScanning) return; // Mencegah double scan
            isScanning = true;
            
            console.log("QR Terdeteksi:", decodedText);
            
            // Beri feedback visual cepat
            resultAlert.classList.remove('d-none', 'alert-danger');
            resultAlert.classList.add('alert-info');
            resultAlert.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Kode terdeteksi: ' + decodedText;

            // Jeda pemindaian UI agar tidak berkedip banyak
            html5QrcodeScanner.pause();
            
            loadingDiv.classList.remove('d-none');

            // Kirim data QR code via AJAX (Fetch API) menggunakan POST
            let formData = new FormData();
            formData.append('qr_data', decodedText);

            fetch('<?= site_url('siswa/scan/process') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                loadingDiv.classList.add('d-none');
                resultAlert.className = 'alert mt-3 fw-bold '; // Reset
                
                if (data.status === 'success') {
                    resultAlert.classList.add('alert-success');
                    resultAlert.innerHTML = '<i class="fas fa-check-circle me-2"></i>' + data.message;
                    resultAlert.classList.remove('d-none');
                    // Stop kamera karena berhasil
                    html5QrcodeScanner.clear();
                    
                    // Redirect setelah 2 detik
                    setTimeout(() => {
                        window.location.href = '/siswa';
                    }, 2000);
                } else {
                    resultAlert.classList.add('alert-danger');
                    resultAlert.innerHTML = '<i class="fas fa-times-circle me-2"></i>' + data.message;
                    resultAlert.classList.remove('d-none');
                    
                    // Kalau gagal bisa dicoba lagi setelah 3 detik
                    setTimeout(() => {
                        isScanning = false;
                        resultAlert.classList.add('d-none');
                        html5QrcodeScanner.resume();
                    }, 3000);
                }
            })
            .catch(error => {
                loadingDiv.classList.add('d-none');
                console.error('Error:', error);
                alert("Terjadi kesalahan jaringan.");
                isScanning = false;
                html5QrcodeScanner.resume();
            });
        }

        function onScanFailure(error) {
            // handle scan failure (biasanya ignore)
        }
    });
</script>
<?= $this->endSection() ?>
