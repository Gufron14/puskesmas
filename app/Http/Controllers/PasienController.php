<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pasien;
use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pasiens = Pasien::where('tanggal_antrian', date('Y-m-d'))->orderBy('created_at', 'DESC')->get();
        $antrian = Antrian::where('status', 'on')->first();
        return view('backend.pages.antri.index', compact('pasiens', 'antrian'));
    }

public function create()
{
    $antrian = Antrian::first();
    $kuota_harian = $antrian ? $antrian->jumlah : 0;
    $tanggal = now()->toDateString();

    $antrian_terpakai = Pasien::where('tanggal_antrian', $tanggal)->pluck('nomor_antrian')->toArray();
    $semua_nomor = $kuota_harian > 0 ? range(1, $kuota_harian) : [];
    $nomor_tersedia = array_diff($semua_nomor, $antrian_terpakai);

    $sisaAntrian = count($nomor_tersedia);

    // Nomor antrian berikutnya (otomatis)
    $lastNomor = Pasien::where('tanggal_antrian', $tanggal)->max('nomor_antrian');
    $nextNomor = $lastNomor ? $lastNomor + 1 : 1;

    $pasiens = User::where('role', 'User')
        ->whereDoesntHave('pasiens', function ($query) use ($tanggal) {
            $query->where('tanggal_antrian', $tanggal);
        })
        ->orderBy('created_at', 'DESC')
        ->get();

    // Pastikan $nextNomor dikirim ke view
    return view('backend.pages.antri.create', compact('sisaAntrian', 'pasiens', 'nextNomor'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $antrian = Antrian::first();
        $kuota_harian = $antrian ? $antrian->jumlah : 0;
        $tanggal = now()->toDateString();

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Hitung nomor antrian berikutnya
        $lastNomor = Pasien::where('tanggal_antrian', $tanggal)->max('nomor_antrian');
        $nextNomor = $lastNomor ? $lastNomor + 1 : 1;

        // Cek kuota
        if ($nextNomor > $kuota_harian) {
            return back()->withErrors(['nomor_antrian' => 'Kuota antrian hari ini sudah penuh.']);
        }

        // Cek user sudah daftar hari ini
        $sudahDaftar = Pasien::where('user_id', $request->user_id)->where('tanggal_antrian', $tanggal)->exists();
        if ($sudahDaftar) {
            return back()->withErrors(['user_id' => 'User sudah terdaftar di antrian hari ini.']);
        }

        // Simpan data pasien dengan nomor antrian & tanggal
        Pasien::create([
            'user_id' => $request->user_id,
            'nomor_antrian' => $nextNomor,
            'tanggal_antrian' => $tanggal,
        ]);

        return redirect()
            ->route('antrian.index')
            ->with('success', 'Pasien berhasil didaftarkan ke antrian dengan nomor ' . $nextNomor . '.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pasien $pasien)
    {
        // return view('backend.pages.antri.show', compact('pasien'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pasien $pasien)
    {
        return view('backend.pages.antri.edit', compact('pasien'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pasien $pasien)
    {
        $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'usia' => 'required|integer|min:0',
            'nik' => 'required|digits:16',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
        ]);

        $pasien->update($request->all());

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // ✅ BENAR - Hapus Pasien, bukan User
            $pasien = Pasien::findOrFail($id);

            // Cek apakah pasien memiliki data pemeriksaan terkait
            // if ($pasien->pemeriksaans()->exists()) {
            //     return back()->with('error', 'Tidak dapat menghapus pasien karena sudah memiliki data pemeriksaan.');
            // }

            $userName = $pasien->user->name;
            $pasien->delete();

            return back()->with('success', "Data pasien {$userName} berhasil dihapus dari antrian.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data pasien: ' . $e->getMessage());
        }
    }
}
