<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\KelasModel;
use App\Models\QrCodeModel;
use App\Models\AbsensiModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class AdminController extends BaseController
{
    protected $userModel;
    protected $kelasModel;
    protected $qrCodeModel;
    protected $absensiModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->kelasModel = new KelasModel();
        $this->qrCodeModel = new QrCodeModel();
        $this->absensiModel = new AbsensiModel();
    }

    public function index()
    {
        $data = [
            'title'       => 'Dashboard Admin',
            'total_siswa' => $this->userModel->where('role', 'siswa')->countAllResults(),
            'total_guru'  => $this->userModel->where('role', 'guru')->countAllResults(),
            'siswa_hadir' => $this->absensiModel->where('tanggal', date('Y-m-d'))->where('status', 'hadir')->countAllResults(),
            'qr_aktif'    => $this->qrCodeModel->where('tanggal', date('Y-m-d'))->where('status', 'aktif')->countAllResults() > 0 ? 'Ada' : 'Belum Ada',
        ];
        return view('admin/dashboard', $data);
    }

    // --- Manajemen Siswa ---
    public function siswa()
    {
        // manually join to get kelas name
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('users.*, kelas.nama_kelas');
        $builder->join('kelas', 'kelas.id = users.kelas_id', 'left');
        $builder->where('users.role', 'siswa');
        $data['siswa'] = $builder->get()->getResultArray();
        $data['kelas'] = $this->kelasModel->findAll();
        $data['title'] = 'Data Siswa';
        
        return view('admin/siswa', $data);
    }

    public function storeSiswa()
    {
        $rules = [
            'nama'        => 'required',
            'nomor_induk' => 'required|is_unique[users.nomor_induk]',
            'password'    => 'required|min_length[6]',
            'kelas_id'    => 'required|integer'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->insert([
            'nama'        => $this->request->getPost('nama'),
            'nomor_induk' => $this->request->getPost('nomor_induk'),
            'password'    => $this->request->getPost('password'),
            'role'        => 'siswa',
            'kelas_id'    => $this->request->getPost('kelas_id')
        ]);

        return redirect()->to('/admin/siswa')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function deleteSiswa($id)
    {
        $this->userModel->delete($id);
        return redirect()->to('/admin/siswa')->with('success', 'Data siswa berhasil dihapus.');
    }

    // --- Manajemen Guru ---
    public function guru()
    {
        $data['guru'] = $this->userModel->where('role', 'guru')->findAll();
        $data['title'] = 'Data Guru';
        return view('admin/guru', $data);
    }

    public function storeGuru()
    {
        $rules = [
            'nama'        => 'required',
            'nomor_induk' => 'required|is_unique[users.nomor_induk]',
            'password'    => 'required|min_length[6]'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->insert([
            'nama'        => $this->request->getPost('nama'),
            'nomor_induk' => $this->request->getPost('nomor_induk'),
            'password'    => $this->request->getPost('password'),
            'role'        => 'guru',
        ]);

        return redirect()->to('/admin/guru')->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function deleteGuru($id)
    {
        $this->userModel->delete($id);
        return redirect()->to('/admin/guru')->with('success', 'Data guru berhasil dihapus.');
    }

    // --- Manajemen Kelas ---
    public function kelas()
    {
        $data['kelas'] = $this->kelasModel->findAll();
        $data['title'] = 'Data Kelas';
        return view('admin/kelas', $data);
    }

    public function storeKelas()
    {
        $rules = [
            'nama_kelas' => 'required',
            'wali_kelas' => 'required'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->kelasModel->insert([
            'nama_kelas' => $this->request->getPost('nama_kelas'),
            'wali_kelas' => $this->request->getPost('wali_kelas')
        ]);

        return redirect()->to('/admin/kelas')->with('success', 'Data kelas berhasil ditambahkan.');
    }

    // --- Generate QR Code ---
    public function generateQr()
    {
        $hari_ini = date('Y-m-d');
        $qrExists = $this->qrCodeModel->where('tanggal', $hari_ini)->first();

        $data = [
            'title'    => 'Generate QR Code Absensi',
            'qr_today' => $qrExists,
            'qr_image' => null
        ];

        if ($qrExists) {
            $qr = new QrCode($qrExists['kode_qr']);
            $writer = new PngWriter();
            $result = $writer->write($qr);
            $data['qr_image'] = $result->getDataUri();
        }

        return view('admin/generate_qr', $data);
    }

    public function processGenerateQr()
    {
        $hari_ini = date('Y-m-d');
        $qrExists = $this->qrCodeModel->where('tanggal', $hari_ini)->first();

        if ($qrExists) {
            return redirect()->to('/admin/generate-qr')->with('error', 'QR Code untuk hari ini sudah ada.');
        }

        $uniqueString = 'ABSEN|' . $hari_ini . '|SMPCB|' . strtoupper(bin2hex(random_bytes(4)));

        $this->qrCodeModel->insert([
            'kode_qr'         => $uniqueString,
            'tanggal'         => $hari_ini,
            'jam_masuk'       => '07:00:00',
            'batas_terlambat' => '07:30:00',
            'status'          => 'aktif'
        ]);

        return redirect()->to('/admin/generate-qr')->with('success', 'QR Code berhasil di-generate dan sesi absensi hari ini AKTIF.');
    }

    public function quickActivateSession()
    {
        $hari_ini = date('Y-m-d');
        $qrExists = $this->qrCodeModel->where('tanggal', $hari_ini)->first();

        if ($qrExists) {
            // Pastikan status aktif
            $this->qrCodeModel->update($qrExists['id'], ['status' => 'aktif']);
            return redirect()->to('/admin')->with('success', 'Sesi absensi hari ini sudah aktif kembali.');
        }

        $uniqueString = 'ABSEN|' . $hari_ini . '|SMPCB|' . strtoupper(bin2hex(random_bytes(4)));
        $this->qrCodeModel->insert([
            'kode_qr'         => $uniqueString,
            'tanggal'         => $hari_ini,
            'jam_masuk'       => '07:00:00',
            'batas_terlambat' => '07:30:00',
            'status'          => 'aktif'
        ]);

        return redirect()->to('/admin')->with('success', '✅ Sesi absensi hari ini berhasil DIAKTIFKAN!');
    }

    // --- Absensi Wajah ---
    public function absensiWajah()
    {
        $tanggal  = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $kelas_id = $this->request->getGet('kelas_id');

        $db      = \Config\Database::connect();
        $builder = $db->table('absensi');
        $builder->select('absensi.*, users.nama as nama_siswa, kelas.nama_kelas');
        $builder->join('users', 'users.id = absensi.user_id');
        $builder->join('kelas', 'kelas.id = absensi.kelas_id', 'left');
        $builder->where('absensi.metode', 'wajah');
        $builder->where('absensi.tanggal', $tanggal);
        if ($kelas_id) {
            $builder->where('absensi.kelas_id', $kelas_id);
        }
        $builder->orderBy('absensi.jam_masuk', 'DESC');

        $data = [
            'title'          => 'Absensi Wajah Siswa',
            'absensi'        => $builder->get()->getResultArray(),
            'kelas'          => $this->kelasModel->findAll(),
            'tanggal_filter' => $tanggal,
            'kelas_filter'   => $kelas_id,
            'total_hari_ini' => $db->table('absensi')
                                    ->where('metode', 'wajah')
                                    ->where('tanggal', date('Y-m-d'))
                                    ->countAllResults(),
        ];

        return view('admin/absensi_wajah', $data);
    }

    // --- Laporan Absensi ---
    public function laporan()
    {
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $kelas_id = $this->request->getGet('kelas_id');

        $data['absensi'] = $this->absensiModel->getAbsensiWithDetails(null, $kelas_id, $tanggal);
        $data['kelas'] = $this->kelasModel->findAll();
        $data['tanggal_filter'] = $tanggal;
        $data['kelas_filter'] = $kelas_id;
        $data['title'] = 'Laporan Absensi';

        return view('admin/laporan', $data);
    }
}
