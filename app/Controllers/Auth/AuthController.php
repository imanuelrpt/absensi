<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\KelasModel;

class AuthController extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    public function login()
    {
        $rules = [
            'nomor_induk' => 'required',
            'password'    => 'required'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $ni = $this->request->getPost('nomor_induk');
        $userModel = new UserModel();
        $user = $userModel->where('nomor_induk', $ni)->first();

        if ($user && password_verify((string) $this->request->getPost('password'), $user['password'])) {
            $sessionData = [
                'id'         => $user['id'],
                'nama'       => $user['nama'],
                'nomor_induk'=> $user['nomor_induk'],
                'role'       => $user['role'],
                'kelas_id'   => $user['kelas_id'],
                'isLoggedIn' => true
            ];
            session()->set($sessionData);
            return redirect()->to('/dashboard');
        }

        return redirect()->back()->withInput()->with('error', 'Nomor Induk atau Password salah.');
    }

    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        $kelasModel = new KelasModel();
        $data['kelas'] = $kelasModel->findAll();
        return view('auth/register', $data);
    }

    public function processRegister()
    {
        $rules = [
            'nama'        => 'required|min_length[3]',
            'nomor_induk' => 'required|is_unique[users.nomor_induk]',
            'password'    => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nomorInduk = $this->request->getPost('nomor_induk');
        $kelasId = $this->request->getPost('kelas_id');
        
        // Deteksi role otomatis
        $role = 'siswa';
        if (strtolower($nomorInduk) === 'admin') {
            return redirect()->back()->withInput()->with('error', 'Username "Admin" sudah digunakan dan tidak bisa didaftarkan ulang.');
        }

        if (strlen($nomorInduk) == 18 && is_numeric($nomorInduk)) {
            $role = 'guru';
        } else {
            $role = 'siswa';
        }

        // Pengecekan kelas untuk Guru dan Siswa
        if ($role !== 'admin' && empty($kelasId)) {
            return redirect()->back()->withInput()->with('error', 'Siswa dan Guru Wajib memilih Kelas.');
        }

        $userModel = new UserModel();
        $data = [
            'nama'        => $this->request->getPost('nama'),
            'nomor_induk' => $nomorInduk,
            'password'    => $this->request->getPost('password'),
            'role'        => $role
        ];

        // tambahkan kelas_id hanya jika tidak kosong
        if (!empty($kelasId)) {
            $data['kelas_id'] = $kelasId;
        }

        $userModel->insert($data);
        return redirect()->to('/login')->with('success', 'Registrasi berhasil. Role terdeteksi sebagai: ' . ucfirst($role));
    }

    public function dashboard()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = session()->get('role');
        if ($role === 'admin') {
            return redirect()->to('/admin');
        } elseif ($role === 'guru') {
            return redirect()->to('/guru');
        } elseif ($role === 'siswa') {
            return redirect()->to('/siswa');
        }

        return redirect()->to('/login')->with('error', 'Role tidak valid.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah logout.');
    }
}
