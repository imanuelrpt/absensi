<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'nama'        => 'Administrator',
            'nomor_induk' => 'Admin',
            'password'    => password_hash('12909ajdn!@#$8128!@#$', PASSWORD_DEFAULT),
            'role'        => 'admin'
        ];

        // Using Query Builder
        $this->db->table('users')->insert($data);
    }
}
