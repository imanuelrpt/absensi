<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\QrCodeModel;
use App\Models\AbsensiModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class SiswaController extends BaseController
{
    protected $qrCodeModel;
    protected $absensiModel;

    public function __construct()
    {
        $this->qrCodeModel = new QrCodeModel();
        $this->absensiModel = new AbsensiModel();
    }

    public function index()
    {
        $userId = session()->get('id');
        $tanggal = date('Y-m-d');
        
        $absensiHariIni = $this->absensiModel
            ->where('user_id', $userId)
            ->where('tanggal', $tanggal)
            ->first();

        $data = [
            'title'           => 'Dashboard Siswa',
            'status_absen'    => $absensiHariIni ? $absensiHariIni['status'] : 'Belum Absen',
            'jam_absen'       => $absensiHariIni ? $absensiHariIni['jam_masuk'] : '-',
        ];

        return view('siswa/dashboard', $data);
    }

    public function scanQr()
    {
        $data['title'] = 'Scan QR Absensi';
        return view('siswa/scan', $data);
    }

    public function processScan()
    {
        $qrData = $this->request->getPost('qr_data');
        $userId = session()->get('id');
        $kelasId = session()->get('kelas_id');
        $tanggalSekarang = date('Y-m-d');
        $jamSekarang = date('H:i:s');

        // Validasi QR
        $qrRecord = $this->qrCodeModel
            ->where('kode_qr', $qrData)
            ->where('tanggal', $tanggalSekarang)
            ->where('status', 'aktif')
            ->first();

        if (! $qrRecord) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'QR Code tidak valid atau sudah kadaluarsa.'
            ]);
        }

        // Cek apakah siswa sudah absen hari ini
        $sudahAbsen = $this->absensiModel
            ->where('user_id', $userId)
            ->where('tanggal', $tanggalSekarang)
            ->first();

        if ($sudahAbsen) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Anda sudah melakukan absensi hari ini.'
            ]);
        }

        // Tentukan status kehadiran (07:30 batas)
        $statusKehadiran = ($jamSekarang <= $qrRecord['batas_terlambat']) ? 'hadir' : 'terlambat';

        $this->absensiModel->insert([
            'user_id'   => $userId,
            'kelas_id'  => $kelasId,
            'qr_id'     => $qrRecord['id'],
            'tanggal'   => $tanggalSekarang,
            'jam_masuk' => $jamSekarang,
            'status'    => $statusKehadiran,
            'metode'    => 'qr'
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Absensi berhasil dicatat sebagai: ' . ucfirst($statusKehadiran)
        ]);
    }

    public function riwayat()
    {
        $userId = session()->get('id');
        $data = [
            'title'   => 'Riwayat Kehadiran Anda',
            'absensi' => $this->absensiModel->getAbsensiWithDetails($userId)
        ];

        return view('siswa/riwayat', $data);
    }

    // --- FACE RECOGNITION ---

    public function daftarWajah()
    {
        return view('siswa/daftar_wajah');
    }

    public function simpanWajah()
    {
        $userId = session()->get('id');
        $descriptor = $this->request->getPost('descriptor');

        if (empty($descriptor)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data wajah kosong.']);
        }

        $userModel = new \App\Models\UserModel();
        $userModel->update($userId, ['face_descriptor' => $descriptor]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Wajah berhasil didaftarkan!']);
    }

    public function absensiWajah()
    {
        return view('siswa/absensi_wajah');
    }

    public function prosesAbsensiWajah()
    {
        // Pastikan wajah terdeteksi di JS sebelum submit
        $faceDetected = $this->request->getPost('face_detected');
        if ($faceDetected !== '1') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Wajah tidak terdeteksi di kamera.']);
        }

        // Gunakan siswa yang sedang login — tidak perlu pencocokan wajah
        $userId  = session()->get('id');
        $nama    = session()->get('nama');
        $kelasId = session()->get('kelas_id');

        $absensiModel = new \App\Models\AbsensiModel();
        $tanggal      = date('Y-m-d');
        $jam          = date('H:i:s');

        // Cek sudah absen hari ini?
        $sudahAbsen = $absensiModel->where('user_id', $userId)
                                   ->where('tanggal', $tanggal)
                                   ->first();

        if ($sudahAbsen) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Anda sudah melakukan absensi hari ini.'
            ]);
        }

        // Cari QR aktif hari ini
        $qrModel  = new \App\Models\QrCodeModel();
        $qrRecord = $qrModel->where('tanggal', $tanggal)
                            ->where('status', 'aktif')
                            ->first();

        if (!$qrRecord) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada sesi absensi aktif hari ini. Hubungi Admin.']);
        }

        $statusKehadiran = ($jam <= $qrRecord['batas_terlambat']) ? 'hadir' : 'terlambat';
        $fotoAbsen       = $this->request->getPost('foto_absen');

        $absensiModel->insert([
            'user_id'    => $userId,
            'kelas_id'   => $kelasId,
            'qr_id'      => $qrRecord['id'],
            'tanggal'    => $tanggal,
            'jam_masuk'  => $jam,
            'status'     => $statusKehadiran,
            'metode'     => 'wajah',
            'foto_absen' => $fotoAbsen,
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'nama'    => $nama,
            'message' => 'Absensi berhasil! Selamat datang, ' . $nama . ' — Status: ' . ucfirst($statusKehadiran)
        ]);
    }
}
