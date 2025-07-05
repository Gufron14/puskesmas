<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendaftaranController extends Controller
{
    public function index()
    {
        $antrian = Antrian::first();
        return view('frontend.pendaftaran', compact('antrian'));
    }

    public function getNomorAntrian(Request $request)
    {
        // Validasi user login
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu'
            ]);
        }

        $tanggal = $request->tanggal;
        $antrian = Antrian::first();
        $kuota_harian = $antrian ? $antrian->jumlah : 0;

        // Validasi tanggal tidak boleh masa lalu
        if ($tanggal < now()->toDateString()) {
            return response()->json([
                'success' => false,
                'message' => 'Tanggal antrian tidak boleh masa lalu'
            ]);
        }

        // Cek apakah user sudah mendaftar pada tanggal tersebut
        $userSudahDaftar = Pasien::where('user_id', Auth::id())
            ->where('tanggal_antrian', $tanggal)
            ->exists();

        if ($userSudahDaftar) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah mendaftar antrian pada tanggal tersebut'
            ]);
        }

        // Ambil nomor antrian yang sudah terpakai pada tanggal tersebut
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

        return response()->json([
            'success' => true,
            'data' => $nomorWaktu,
            'sisaAntrian' => $sisaAntrian
        ]);
    }
    public function store(Request $request)
    {
        $antrian = Antrian::first();
        $kuota_harian = $antrian ? $antrian->jumlah : 0;

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_antrian' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) use ($request) {
                    // Cek apakah user sudah mendaftar pada tanggal tersebut
                    $exists = Pasien::where('user_id', $request->user_id)
                                    ->where('tanggal_antrian', $value)
                                    ->exists();
                    if ($exists) {
                        $fail("Anda sudah mendaftar antrian pada tanggal tersebut.");
                    }
                }
            ],
            'nomor_antrian' => [
                'required',
                'integer',
                'between:1,' . $kuota_harian,
                function ($attribute, $value, $fail) use ($request) {
                    $exists = Pasien::where('tanggal_antrian', $request->tanggal_antrian)
                                    ->where('nomor_antrian', $value)
                                    ->exists();
                    if ($exists) {
                        $fail("Nomor antrian $value sudah digunakan pada tanggal tersebut.");
                    }
                }
            ],
        ], [
            'tanggal_antrian.required' => 'Tanggal antrian harus diisi',
            'tanggal_antrian.date' => 'Format tanggal tidak valid',
            'tanggal_antrian.after_or_equal' => 'Tanggal antrian tidak boleh masa lalu',
            'nomor_antrian.required' => 'Nomor antrian harus dipilih',
            'nomor_antrian.between' => 'Nomor antrian tidak valid',
        ]);

        // Simpan data pasien dengan nomor antrian & tanggal
        Pasien::create([
            'user_id'         => $request->user_id,
            'nomor_antrian'   => $request->nomor_antrian,
            'tanggal_antrian' => $request->tanggal_antrian,
        ]);

        return redirect()->route('daftar')->with('success', 'Berhasil didaftarkan ke antrian pada tanggal ' . \Carbon\Carbon::parse($request->tanggal_antrian)->translatedFormat('d F Y'));
    }
}
