<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\KelasModel;
use App\Models\AbsensiModel;

class GuruController extends BaseController
{
    protected $userModel;
    protected $kelasModel;
    protected $absensiModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->kelasModel = new KelasModel();
        $this->absensiModel = new AbsensiModel();
    }

    public function index()
    {
        // Wali kelas dashboard
        $guruId = session()->get('id');
        $guruNama = session()->get('nama');
        
        $kelas = $this->kelasModel->where('wali_kelas', $guruNama)->first();
        
        $data = [
            'title' => 'Dashboard Guru',
            'kelas' => $kelas,
            'absensi_hari_ini' => 0
        ];

        if ($kelas) {
            $data['absensi_hari_ini'] = $this->absensiModel
                ->where('kelas_id', $kelas['id'])
                ->where('tanggal', date('Y-m-d'))
                ->countAllResults();
        }

        return view('guru/dashboard', $data);
    }

    public function absensiKelas()
    {
        $guruNama = session()->get('nama');
        $kelas = $this->kelasModel->where('wali_kelas', $guruNama)->first();

        if (! $kelas) {
            return redirect()->to('/guru')->with('error', 'Anda belum ditugaskan sebagai wali kelas.');
        }

        $tanggal = date('Y-m-d');
        
        $data['absensi'] = $this->absensiModel->getAbsensiWithDetails(null, $kelas['id'], $tanggal);
        $data['title'] = 'Absensi Kelas Hari Ini';
        $data['kelas'] = $kelas;

        return view('guru/absensi', $data);
    }

    public function rekapAbsensi()
    {
        $guruNama = session()->get('nama');
        $kelas = $this->kelasModel->where('wali_kelas', $guruNama)->first();

        if (! $kelas) {
            return redirect()->to('/guru')->with('error', 'Anda belum ditugaskan sebagai wali kelas.');
        }

        $bulan = $this->request->getGet('bulan') ?? date('Y-m');
        
        // Manual query for recap
        $db = \Config\Database::connect();
        $builder = $db->table('absensi');
        $builder->select('absensi.*, users.nama as nama_siswa');
        $builder->join('users', 'users.id = absensi.user_id');
        $builder->where('absensi.kelas_id', $kelas['id']);
        $builder->like('absensi.tanggal', $bulan . '%', 'after');
        $builder->orderBy('absensi.tanggal', 'DESC');
        
        $data['absensi'] = $builder->get()->getResultArray();
        $data['title'] = 'Rekap Absensi Bulanan';
        $data['bulan_filter'] = $bulan;
        $data['kelas'] = $kelas;

        return view('guru/rekap', $data);
    }
}
