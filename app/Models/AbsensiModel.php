<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $table            = 'absensi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'kelas_id', 'qr_id', 'tanggal', 'jam_masuk', 'status', 'metode', 'foto_absen'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAbsensiWithDetails($userId = null, $kelasId = null, $tanggal = null)
    {
        $builder = $this->builder();
        $builder->select('absensi.*, users.nama as nama_siswa, kelas.nama_kelas, absensi.metode, absensi.foto_absen');
        $builder->join('users', 'users.id = absensi.user_id');
        $builder->join('kelas', 'kelas.id = absensi.kelas_id', 'left');

        if ($userId) {
            $builder->where('absensi.user_id', $userId);
        }
        if ($kelasId) {
            $builder->where('absensi.kelas_id', $kelasId);
        }
        if ($tanggal) {
            $builder->where('absensi.tanggal', $tanggal);
        }

        $builder->orderBy('absensi.tanggal', 'DESC');
        $builder->orderBy('absensi.jam_masuk', 'DESC');

        return $builder->get()->getResultArray();
    }
}
