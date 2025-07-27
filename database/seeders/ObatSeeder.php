<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Obat;

class ObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $obats = [
            [
                'nama' => 'Paracetamol 500mg',
                'harga' => 2500.0,
                'stok' => 100,
                'status' => 'aktif',
                'deskripsi' => 'Obat penurun panas dan pereda nyeri',
                'jenis_obat_id' => 2, // Tablet
            ],
            [
                'nama' => 'Amoxicillin 500mg',
                'harga' => 5000.0,
                'stok' => 75,
                'status' => 'aktif',
                'deskripsi' => 'Antibiotik untuk infeksi bakteri',
                'jenis_obat_id' => 1, // Kapsul
            ],
            [
                'nama' => 'ORS (Oralit)',
                'harga' => 3000.0,
                'stok' => 50,
                'status' => 'aktif',
                'deskripsi' => 'Larutan rehidrasi oral untuk diare',
                'jenis_obat_id' => 3, // Sirup
            ],
            [
                'nama' => 'Antasida',
                'harga' => 4000.0,
                'stok' => 60,
                'status' => 'aktif',
                'deskripsi' => 'Obat maag dan gangguan pencernaan',
                'jenis_obat_id' => 2, // Tablet
            ],
            [
                'nama' => 'CTM (Chlorpheniramine Maleate)',
                'harga' => 2000.0,
                'stok' => 80,
                'status' => 'aktif',
                'deskripsi' => 'Antihistamin untuk alergi',
                'jenis_obat_id' => 2, // Tablet
            ],
            [
                'nama' => 'Vitamin B Complex',
                'harga' => 3500.0,
                'stok' => 90,
                'status' => 'aktif',
                'deskripsi' => 'Suplemen vitamin B kompleks',
                'jenis_obat_id' => 2, // Tablet
            ],
            [
                'nama' => 'Ibuprofen 400mg',
                'harga' => 4500.0,
                'stok' => 70,
                'status' => 'aktif',
                'deskripsi' => 'Anti inflamasi dan pereda nyeri',
                'jenis_obat_id' => 2, // Tablet
            ],
            [
                'nama' => 'Salbutamol Inhaler',
                'harga' => 25000.0,
                'stok' => 20,
                'status' => 'aktif',
                'deskripsi' => 'Bronkodilator untuk asma',
                'jenis_obat_id' => 6, // Obat Tetes (misal inhaler masuk ke tetes)
            ],
            [
                'nama' => 'Betadine Solution',
                'harga' => 8000.0,
                'stok' => 40,
                'status' => 'aktif',
                'deskripsi' => 'Antiseptik untuk luka',
                'jenis_obat_id' => 5, // Salep (atau sesuaikan)
            ],
            [
                'nama' => 'Metformin 500mg',
                'harga' => 6000.0,
                'stok' => 65,
                'status' => 'aktif',
                'deskripsi' => 'Obat diabetes tipe 2',
                'jenis_obat_id' => 2, // Tablet
            ],
            [
                'nama' => 'Captopril 25mg',
                'harga' => 7000.0,
                'stok' => 55,
                'status' => 'aktif',
                'deskripsi' => 'Obat hipertensi',
                'jenis_obat_id' => 2, // Tablet
            ],
            [
                'nama' => 'Domperidone 10mg',
                'harga' => 3500.0,
                'stok' => 45,
                'status' => 'aktif',
                'deskripsi' => 'Obat mual dan muntah',
                'jenis_obat_id' => 2, // Tablet
            ],
            [
                'nama' => 'Zinc Tablet',
                'harga' => 2500.0,
                'stok' => 85,
                'status' => 'aktif',
                'deskripsi' => 'Suplemen zinc untuk daya tahan tubuh',
                'jenis_obat_id' => 2, // Tablet
            ],
            [
                'nama' => 'Loratadine 10mg',
                'harga' => 4000.0,
                'stok' => 50,
                'status' => 'aktif',
                'deskripsi' => 'Antihistamin untuk alergi',
                'jenis_obat_id' => 2, // Tablet
            ],
            [
                'nama' => 'Simvastatin 20mg',
                'harga' => 8500.0,
                'stok' => 30,
                'status' => 'aktif',
                'deskripsi' => 'Obat kolesterol tinggi',
                'jenis_obat_id' => 2, // Tablet
            ],
            [
                'nama' => 'Omeprazole 20mg',
                'harga' => 5500.0,
                'stok' => 40,
                'status' => 'aktif',
                'deskripsi' => 'Obat asam lambung',
                'jenis_obat_id' => 1, // Kapsul
            ],
            [
                'nama' => 'Cetirizine 10mg',
                'harga' => 3000.0,
                'stok' => 60,
                'status' => 'aktif',
                'deskripsi' => 'Antihistamin untuk alergi',
                'jenis_obat_id' => 2, // Tablet
            ],
            [
                'nama' => 'Dextromethorphan Syrup',
                'harga' => 12000.0,
                'stok' => 25,
                'status' => 'aktif',
                'deskripsi' => 'Obat batuk kering',
                'jenis_obat_id' => 3, // Sirup
            ],
            [
                'nama' => 'Iron Tablet (Fe)',
                'harga' => 2000.0,
                'stok' => 100,
                'status' => 'aktif',
                'deskripsi' => 'Suplemen zat besi untuk anemia',
                'jenis_obat_id' => 2, // Tablet
            ],
            [
                'nama' => 'Aspirin 100mg',
                'harga' => 1500.0,
                'stok' => 80,
                'status' => 'aktif',
                'deskripsi' => 'Pengencer darah dan pereda nyeri',
                'jenis_obat_id' => 2, // Tablet
            ],
        ];

        foreach ($obats as $obat) {
            Obat::create($obat);
        }
    }
}
