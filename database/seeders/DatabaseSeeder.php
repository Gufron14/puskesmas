<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Hanya buat 3 akun penting untuk production
        
        User::create([
            'name' => 'Admin SIM RM',
            'telepon' => '08123456789',
            'email' => 'admin@puskesmas.com',
            'password' => Hash::make('12345678'),
            'role' => 'Admin',
            'jenis_kelamin' => 'Laki-laki',
            'usia' => 30,
            'nik' => '1234567890123456',
            'alamat' => 'Puskesmas Pembantu Desa Wakap',
            'email_verified_at' => now(),
        ]);

        // Mantri -> Pemeriksaan, Rekam Medis
        User::create([
            'name' => 'Mantri SIM RM',
            'telepon' => '081234567890', 
            'email' => 'mantri@puskesmas.com',
            'password' => Hash::make('12345678'),
            'role' => 'Mantri',
            'jenis_kelamin' => 'Perempuan',
            'usia' => 28,
            'nik' => '1234567890123457',
            'alamat' => 'Puskesmas Pembantu Desa Wakap',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Puskesmas Induk',
            'telepon' => '081234567891',
            'email' => 'induk@puskesmas.com', 
            'password' => Hash::make('12345678'),
            'role' => 'Puskesmas Induk',
            'jenis_kelamin' => 'Laki-laki',
            'usia' => 35,
            'nik' => '1234567890123458',
            'alamat' => 'Puskesmas Induk Bantarkalong',
            'email_verified_at' => now(),
        ]);
    }
}
