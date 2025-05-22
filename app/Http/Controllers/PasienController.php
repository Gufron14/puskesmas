<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tanggal = now()->toDateString(); // hari ini
        $pasiens = Pasien::where('tanggal_antrian', $tanggal)->orderBy('nomor_antrian')->get();
        return view('backend.pages.pasien.index', compact('pasiens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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

        return view('backend.pages.pasien.create', compact('nomorWaktu', 'sisaAntrian'));
    }


    /**
     * Store a newly created resource in storage.
     */
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

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Pasien $pasien)
    {
        return view('backend.pages.pasien.show', compact('pasien'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pasien $pasien)
    {
        return view('backend.pages.pasien.edit', compact('pasien'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pasien $pasien)
    {
        $request->validate([
            'nama_pasien'   => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'usia'          => 'required|integer|min:0',
            'nik'           => 'required|digits:16|unique:pasiens,nik,' . $pasien->id,
            'alamat'        => 'nullable|string',
            'telepon'       => 'nullable|string|max:20',
        ]);

        $pasien->update($request->all());

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pasien $pasien)
    {
        $pasien->delete();
        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil dihapus.');
    }
}
