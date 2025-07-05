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
                'harga' => 2500.00,
                'stok' => 100,
                'status' => 'aktif',
                'deskripsi' => 'Obat penurun panas dan pereda nyeri'
            ],
            [
                'nama' => 'Amoxicillin 500mg',
                'harga' => 5000.00,
                'stok' => 75,
                'status' => 'aktif',
                'deskripsi' => 'Antibiotik untuk infeksi bakteri'
            ],
            [
                'nama' => 'ORS (Oralit)',
                'harga' => 3000.00,
                'stok' => 50,
                'status' => 'aktif',
                'deskripsi' => 'Larutan rehidrasi oral untuk diare'
            ],
            [
                'nama' => 'Antasida',
                'harga' => 4000.00,
                'stok' => 60,
                'status' => 'aktif',
                'deskripsi' => 'Obat maag dan gangguan pencernaan'
            ],
            [
                'nama' => 'CTM (Chlorpheniramine Maleate)',
                'harga' => 2000.00,
                'stok' => 80,
                'status' => 'aktif',
                'deskripsi' => 'Antihistamin untuk alergi'
            ],
            [
                'nama' => 'Vitamin B Complex',
                'harga' => 3500.00,
                'stok' => 90,
                'status' => 'aktif',
                'deskripsi' => 'Suplemen vitamin B kompleks'
            ],
            [
                'nama' => 'Ibuprofen 400mg',
                'harga' => 4500.00,
                'stok' => 70,
                'status' => 'aktif',
                'deskripsi' => 'Anti inflamasi dan pereda nyeri'
            ],
            [
                'nama' => 'Salbutamol Inhaler',
                'harga' => 25000.00,
                'stok' => 20,
                'status' => 'aktif',
                'deskripsi' => 'Bronkodilator untuk asma'
            ],
            [
                'nama' => 'Betadine Solution',
                'harga' => 8000.00,
                'stok' => 40,
                'status' => 'aktif',
                'deskripsi' => 'Antiseptik untuk luka'
            ],
            [
                'nama' => 'Metformin 500mg',
                'harga' => 6000.00,
                'stok' => 65,
                'status' => 'aktif',
                'deskripsi' => 'Obat diabetes tipe 2'
            ],
            [
                'nama' => 'Captopril 25mg',
                'harga' => 7000.00,
                'stok' => 55,
                'status' => 'aktif',
                'deskripsi' => 'Obat hipertensi'
            ],
            [
                'nama' => 'Domperidone 10mg',
                'harga' => 3500.00,
                'stok' => 45,
                'status' => 'aktif',
                'deskripsi' => 'Obat mual dan muntah'
            ],
            [
                'nama' => 'Zinc Tablet',
                'harga' => 2500.00,
                'stok' => 85,
                'status' => 'aktif',
                'deskripsi' => 'Suplemen zinc untuk daya tahan tubuh'
            ],
            [
                'nama' => 'Loratadine 10mg',
                'harga' => 4000.00,
                'stok' => 50,
                'status' => 'aktif',
                'deskripsi' => 'Antihistamin untuk alergi'
            ],
            [
                'nama' => 'Simvastatin 20mg',
                'harga' => 8500.00,
                'stok' => 30,
                'status' => 'aktif',
                'deskripsi' => 'Obat kolesterol tinggi'
            ],
            [
                'nama' => 'Omeprazole 20mg',
                'harga' => 5500.00,
                'stok' => 40,
                'status' => 'aktif',
                'deskripsi' => 'Obat asam lambung'
            ],
            [
                'nama' => 'Cetirizine 10mg',
                'harga' => 3000.00,
                'stok' => 60,
                'status' => 'aktif',
                'deskripsi' => 'Antihistamin untuk alergi'
            ],
            [
                'nama' => 'Dextromethorphan Syrup',
                'harga' => 12000.00,
                'stok' => 25,
                'status' => 'aktif',
                'deskripsi' => 'Obat batuk kering'
            ],
            [
                'nama' => 'Iron Tablet (Fe)',
                'harga' => 2000.00,
                'stok' => 100,
                'status' => 'aktif',
                'deskripsi' => 'Suplemen zat besi untuk anemia'
            ],
            [
                'nama' => 'Aspirin 100mg',
                'harga' => 1500.00,
                'stok' => 80,
                'status' => 'aktif',
                'deskripsi' => 'Pengencer darah dan pereda nyeri'
            ]
        ];

        foreach ($obats as $obat) {
            Obat::create($obat);
        }
    }
}
