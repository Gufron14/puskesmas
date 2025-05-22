<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;

class PemeriksaanController extends Controller
{
    public function index()
    {
        $tanggal = now()->toDateString(); // hari ini
        $pasiens = Pasien::where('tanggal_antrian', $tanggal)
            ->where('status', 'menunggu')
            ->orderBy('nomor_antrian')
            ->get();

        return view('backend.pages.pemeriksaan', compact('pasiens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pasien_id'            => 'required|exists:pasiens,id',
            'tanggal_pemeriksaan'  => 'required|date',
            'sistolik'             => 'required|numeric',
            'diastolik'            => 'required|numeric',
            'gejala'               => 'nullable|string',
            'catatan_dokter'       => 'nullable|string',
            'biaya'                => 'nullable|numeric',
        ]);

        Pemeriksaan::create([
            'pasien_id'            => $request->pasien_id,
            'tanggal_pemeriksaan'  => $request->tanggal_pemeriksaan,
            'tensi_sistolik'       => $request->sistolik,
            'tensi_diastolik'      => $request->diastolik,
            'gejala'               => $request->gejala,
            'catatan_dokter'       => $request->catatan_dokter,
            'resep_obat'           => json_encode($request->resep),
            'biaya'                => $request->biaya,
        ]);

        // Update status pasien jadi "Diperiksa"
        Pasien::where('id', $request->pasien_id)->update(['status' => 'diperiksa']);

        return redirect()->route('pemeriksaan')->with('success', 'Data pemeriksaan berhasil disimpan.');
    }

}
