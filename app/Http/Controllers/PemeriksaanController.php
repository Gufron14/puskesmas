<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;

class PemeriksaanController extends Controller
{
    public function index()
    {
        $pasiens = Pasien::orderBy('created_at', 'DESC')->get();
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

        $pemeriksaan = Pemeriksaan::create([
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

        return redirect()->route('pembayaran', ['id' => $pemeriksaan->id])->with('success', 'Data pemeriksaan berhasil disimpan.');
    }

    public function destroy(Pemeriksaan $pemeriksaan)
    {
        $pemeriksaan->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

}
