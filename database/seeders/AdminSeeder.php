<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Pengguna::updateOrCreate(
            ['email' => 'admin@moyakristal.com'],
            [
                'nama_lengkap'  => 'Admin Moya Kristal',
                'password'      => Hash::make('admin123'),
                'peran'         => 'admin',
                'status'        => 'aktif',
                'nomor_telepon' => '081234567890',
                'alamat'        => 'Kantor Moya Kristal'
            ]
        );
    }
}
