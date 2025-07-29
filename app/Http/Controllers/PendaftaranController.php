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
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu',
            ]);
        }

        $tanggal = $request->tanggal;
        $antrian = Antrian::first();
        $kuota_harian = $antrian ? $antrian->jumlah : 0;

        if ($tanggal < now()->toDateString()) {
            return response()->json([
                'success' => false,
                'message' => 'Tanggal antrian tidak boleh masa lalu',
            ]);
        }

        $userSudahDaftar = Pasien::where('user_id', Auth::id())->where('tanggal_antrian', $tanggal)->exists();

        if ($userSudahDaftar) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah mendaftar antrian pada tanggal tersebut',
            ]);
        }

        $antrian_terpakai = Pasien::where('tanggal_antrian', $tanggal)->pluck('nomor_antrian')->toArray();

        $semua_nomor = $kuota_harian > 0 ? range(1, $kuota_harian) : [];
        $nomor_tersedia = array_diff($semua_nomor, $antrian_terpakai);
        $sisaAntrian = count($nomor_tersedia);

        // Nomor antrian berikutnya adalah nomor terkecil yang tersedia
        if ($sisaAntrian <= 0) {
            return response()->json([
                'success' => true,
                'nextNomor' => null,
                'sisaAntrian' => 0,
            ]);
        }

        $nextNomor = min($nomor_tersedia);

        return response()->json([
            'success' => true,
            'nextNomor' => $nextNomor,
            'sisaAntrian' => $sisaAntrian,
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
                    $exists = Pasien::where('user_id', $request->user_id)->where('tanggal_antrian', $value)->exists();
                    if ($exists) {
                        $fail('Anda sudah mendaftar antrian pada tanggal tersebut.');
                    }
                },
            ],
        ]);

        $tanggal = $request->tanggal_antrian;
        $lastNomor = Pasien::where('tanggal_antrian', $tanggal)->max('nomor_antrian');
        $nextNomor = $lastNomor ? $lastNomor + 1 : 1;

        if ($nextNomor > $kuota_harian) {
            return back()->withErrors(['nomor_antrian' => 'Kuota antrian hari ini sudah penuh.']);
        }

        Pasien::create([
            'user_id' => $request->user_id,
            'nomor_antrian' => $nextNomor,
            'tanggal_antrian' => $tanggal,
        ]);

        return redirect()
            ->route('daftar')
            ->with('success', 'Berhasil didaftarkan ke antrian pada tanggal ' . \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') . ' dengan nomor antrian ' . $nextNomor);
    }
}
