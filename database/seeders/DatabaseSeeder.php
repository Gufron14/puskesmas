<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call Seeder Lain
        $this->call([
            // UserSeeder::class,
            JenisObatSeeder::class,
            ObatSeeder::class,
        ]);

        User::create([
            'name' => 'Admin SIM RM',
            'telepon' => '08123456789',
            'password' => Hash::make('12345678'),
            'role' => 'Admin',
        ]);

        // Mantri -> Pemeriksaan, Rekam Medis
        User::create([
            'name' => 'Mantri SIM RM',
            'telepon' => '081234567890',
            'password' => Hash::make('12345678'),
            'role' => 'Mantri',
        ]);

        User::create([
            'name' => 'Puskesmas Induk',
            'telepon' => '081234567891',
            'password' => Hash::make('12345678'),
            'role' => 'Puskesmas Induk',
        ]);
    }
}
