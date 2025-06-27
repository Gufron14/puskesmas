<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Antrian;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function index()
    {
        $antrian = Antrian::first();
        $kuota_harian = $antrian ? $antrian->jumlah : 0;
        $tanggal = now()->toDateString();

        // Ambil nomor antrian yang sudah terpakai hari ini
        $antrian_terpakai = Pasien::where('tanggal_antrian', $tanggal)
            ->pluck('nomor_antrian')
            ->toArray();

        // Hindari range invalid jika kuota 0
        $semua_nomor = $kuota_harian > 0 ? range(1, $kuota_harian) : [];

        // Cari nomor yang masih tersedia
        $nomor_tersedia = array_diff($semua_nomor, $antrian_terpakai);

        // Format sebagai array berisi ['nomor' => X]
        $nomorWaktu = [];
        foreach ($nomor_tersedia as $no) {
            $nomorWaktu[] = [
                'nomor' => $no
            ];
        }

        $sisaAntrian = count($nomor_tersedia);
        return view('frontend.pendaftaran', compact('nomorWaktu', 'sisaAntrian'));
    }
    public function store(Request $request)
    {
        $antrian = Antrian::first();
        $kuota_harian = $antrian ? $antrian->jumlah : 0;
        $tanggal = now()->toDateString();

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nomor_antrian' => [
                'required',
                'integer',
                'between:1,' . $kuota_harian,
                function ($attribute, $value, $fail) use ($tanggal) {
                    $exists = Pasien::where('tanggal_antrian', $tanggal)
                                    ->where('nomor_antrian', $value)
                                    ->exists();
                    if ($exists) {
                        $fail("Nomor antrian $value sudah digunakan hari ini.");
                    }
                }
            ],
        ]);

        // Simpan data pasien dengan nomor antrian & tanggal
        Pasien::create([
            'user_id'         => $request->user_id,
            'nomor_antrian'   => $request->nomor_antrian,
            'tanggal_antrian' => $tanggal,
        ]);

        return redirect()->back()->with('success', 'Berhasil didaftarkan ke antrian.');
    }
}
