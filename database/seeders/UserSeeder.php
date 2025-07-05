<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Menggunakan locale Indonesia
        
        // Buat 1 admin terlebih dahulu
        // User::create([
        //     'name' => 'Administrator',
        //     'email' => 'admin@puskesmas.com',
        //     'telepon' => '081234567890',
        //     'password' => Hash::make('password'),
        //     'foto' => null,
        //     'role' => 'Admin',
        //     'jenis_kelamin' => 'Laki-laki',
        //     'usia' => 35,
        //     'nik' => '1234567890123456',
        //     'alamat' => 'Jl. Admin No. 1, Jakarta',
        //     'email_verified_at' => now(),
        // ]);

        // Buat 100 user biasa
        for ($i = 1; $i <= 100; $i++) {
            $jenisKelamin = $faker->randomElement(['Laki-laki', 'Perempuan']);
            $firstName = $jenisKelamin === 'Laki-laki' 
                ? $faker->firstNameMale 
                : $faker->firstNameFemale;
            
            User::create([
                'name' => $firstName . ' ' . $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'telepon' => $this->generatePhoneNumber($faker),
                'password' => Hash::make('password123'), // Password default
                'foto' => null, // Bisa ditambahkan jika ingin random foto
                'role' => 'User',
                'jenis_kelamin' => $jenisKelamin,
                'usia' => $faker->numberBetween(18, 80),
                'nik' => $this->generateNIK($faker),
                'alamat' => $faker->address,
                'email_verified_at' => $faker->optional(0.8)->dateTime(), // 80% terverifikasi
            ]);
        }
    }

    /**
     * Generate nomor telepon Indonesia yang valid
     */
    private function generatePhoneNumber($faker)
    {
        $prefixes = ['0811', '0812', '0813', '0821', '0822', '0823', '0851', '0852', '0853'];
        $prefix = $faker->randomElement($prefixes);
        $number = $faker->numerify('########'); // 8 digit random
        
        return $prefix . $number;
    }

    /**
     * Generate NIK Indonesia yang valid (16 digit)
     */
    private function generateNIK($faker)
    {
        // Format NIK: PPKKSSDDMMYYXXXX
        // PP = Kode Provinsi (2 digit)
        // KK = Kode Kabupaten (2 digit)  
        // SS = Kode Kecamatan (2 digit)
        // DD = Tanggal lahir (2 digit)
        // MM = Bulan lahir (2 digit)
        // YY = Tahun lahir (2 digit)
        // XXXX = Nomor urut (4 digit)
        
        $provinsi = $faker->numberBetween(11, 94); // Kode provinsi Indonesia
        $kabupaten = $faker->numberBetween(01, 99);
        $kecamatan = $faker->numberBetween(01, 99);
        
        $birthDate = $faker->dateTimeBetween('-80 years', '-18 years');
        $day = $birthDate->format('d');
        $month = $birthDate->format('m');
        $year = $birthDate->format('y');
        
        $urut = $faker->numberBetween(0001, 9999);
        
        return sprintf('%02d%02d%02d%02d%02d%02d%04d', 
            $provinsi, $kabupaten, $kecamatan, $day, $month, $year, $urut);
    }
}
