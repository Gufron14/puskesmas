<?php

namespace App\Http\Controllers;

use App\Models\Pemeriksaan;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        // Cek apakah pemeriksaan_id ada
        if ($request->filled('pemeriksaan_id')) {
            $pemeriksaan = Pemeriksaan::with('pasien')->find($request->pemeriksaan_id);

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

}
