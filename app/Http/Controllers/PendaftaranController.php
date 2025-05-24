<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function index()
    {
        $kuota_harian = 10;
        $tanggal = now()->toDateString();

        $antrian_terpakai = Pasien::where('tanggal_antrian', $tanggal)
            ->pluck('nomor_antrian')
            ->toArray();

        $semua_nomor = range(1, $kuota_harian);
        $nomor_tersedia = array_diff($semua_nomor, $antrian_terpakai);

        $waktu_mulai = strtotime('08:00');
        $durasi_per_antrian = 20 * 60; // 20 menit

        $nomorWaktu = [];
        foreach ($nomor_tersedia as $no) {
            $start = date('H:i', $waktu_mulai + ($no - 1) * $durasi_per_antrian);
            $end = date('H:i', $waktu_mulai + $no * $durasi_per_antrian);
            $nomorWaktu[] = [
                'nomor' => $no,
                'waktu' => "$start - $end"
            ];
        }

        $sisaAntrian = count($nomor_tersedia);
        return view('frontend.pendaftaran', compact('nomorWaktu', 'sisaAntrian'));
    }
    public function store(Request $request)
    {
        $kuota_harian = 10;
        $tanggal = now()->toDateString();

        $request->validate([
            'nama_pasien'   => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'usia'          => 'required|integer|min:0',
            'nik'           => 'required|digits:16|unique:pasiens,nik',
            'alamat'        => 'nullable|string',
            'telepon'       => 'nullable|string|max:20',
            'nomor_antrian' => [
                'required',
                'integer',
                'between:1,' . $kuota_harian,
                // validasi custom untuk nomor antrian belum dipakai hari ini
                function ($attribute, $value, $fail) use ($tanggal) {
                    $exists = Pasien::where('tanggal_antrian', $tanggal)
                                    ->where('nomor_antrian', $value)
                                    ->exists();
                    if ($exists) {
                        $fail("Nomor antrian $value sudah dipakai hari ini.");
                    }
                }
            ],
        ]);

        // Simpan data pasien dengan nomor antrian dan tanggal antrian
        Pasien::create([
            'nama_pasien'       => $request->nama_pasien,
            'jenis_kelamin'     => $request->jenis_kelamin,
            'usia'              => $request->usia,
            'nik'               => $request->nik,
            'alamat'            => $request->alamat,
            'telepon'           => $request->telepon,
            'nomor_antrian'     => $request->nomor_antrian,
            'tanggal_antrian'   => $tanggal,
        ]);

        return redirect()->back()->with('success', 'Pendaftaran berhasil ditambahkan.');
    }
}
