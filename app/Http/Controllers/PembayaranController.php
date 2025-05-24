<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        // Cek apakah pemeriksaan_id ada
        if ($request->filled('id')) {
            $pemeriksaan = Pemeriksaan::with('pasien')->find($request->id);

            // Jika tidak ditemukan di database, tetap lanjut dengan data kosong
            if ($pemeriksaan) {
                $resep = json_decode($pemeriksaan->resep_obat, true) ?? [];

                $totalObat = collect($resep)->sum(function ($item) {
                    return $item['jumlah'] * $item['harga'];
                });
            } else {
                $pemeriksaan = null;
                $resep = [];
                $totalObat = 0;
            }
        } else {
            $pemeriksaan = null;
            $resep = [];
            $totalObat = 0;
        }

        return view('backend.pages.pembayaran', compact('pemeriksaan', 'resep', 'totalObat'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'pemeriksaan_id' => 'required|exists:pemeriksaans,id',
            'metode' => 'required|in:BCA,BRI,MANDIRI,qris,tunai',
            'biaya_pemeriksaan' => 'required|numeric',
            'total_obat' => 'required|numeric',
            'total_tagihan' => 'required|numeric',
            'jumlah_bayar' => 'nullable|numeric',
        ]);

        $jumlahBayar = $request->input('jumlah_bayar');
        $totalTagihan = $request->input('total_tagihan');

        $pembayaran = Pembayaran::create([
            'pemeriksaan_id' => $request->input('pemeriksaan_id'),
            'metode' => $request->input('metode'),
            'biaya_pemeriksaan' => $request->input('biaya_pemeriksaan'),
            'total_obat' => $request->input('total_obat'),
            'total_tagihan' => $totalTagihan,
            'jumlah_bayar' => $jumlahBayar,
            'kembalian' => $jumlahBayar && $request->input('metode') === 'tunai' ? $jumlahBayar - $totalTagihan : 0,
        ]);

        return redirect()->route('rekamedis')->with('success', 'Pembayaran berhasil.');
    }

}
