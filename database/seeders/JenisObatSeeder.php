<?php

namespace Database\Seeders;

use App\Models\JenisObat;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JenisObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisObats = [
            [
                'jenis_obat' => 'Kapsul',
            ],
            [
                'jenis_obat' => 'Tablet',
            ],
            [
                'jenis_obat' => 'Sirup',
            ],
            [
                'jenis_obat' => 'Serbuk',
            ],
            [
                'jenis_obat' => 'Salep',
            ],
            [
                'jenis_obat' => 'Obat Tetes',
            ],
        ];

        foreach ($jenisObats as $jenisObat) {
            JenisObat::create($jenisObat);
        }
    }
}
