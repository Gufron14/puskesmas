<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RekamMedisController extends Controller
{
    public function index()
    {
        $tanggal = now()->toDateString();

        $pemeriksaans = Pemeriksaan::with('pasien')
            ->whereDate('tanggal_pemeriksaan', $tanggal)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->map(function ($item) {
                $item->resep_decoded = json_decode($item->resep_obat, true) ?? [];
                $item->total_obat = collect($item->resep_decoded)->sum(function ($r) {
                    return ($r['jumlah'] ?? 0) * ($r['harga'] ?? 0);
                });
                return $item;
            });

        return view('backend.pages.rekamedis.index', compact('pemeriksaans'));
    }
    public function exportPdf($id)
    {
        $pemeriksaan = Pemeriksaan::with('pasien')
            ->where('pasien_id', $id)
            ->firstOrFail();

        $pemeriksaan->resep_decoded = json_decode($pemeriksaan->resep_obat, true) ?? [];
        $pemeriksaan->total_obat = collect($pemeriksaan->resep_decoded)->sum(function ($r) {
            return ($r['jumlah'] ?? 0) * ($r['harga'] ?? 0);
        });

        $pdf = Pdf::loadView('backend.pages.export.pemeriksaan.pdf', [
            'pemeriksaan' => $pemeriksaan
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-pemeriksaan-' . $pemeriksaan->pasien->nama_pasien . '.pdf');
    }
    public function keuangan()
    {
        $pembayarans = Pembayaran::latest()->get();
        return view('backend.pages.laporan.keuangan', compact('pembayarans'));
    }

    public function cetakInvoice($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $pdf = Pdf::loadView('backend.pages.export.keuangan.pdf', compact('pembayaran'))->setPaper('A4', 'portrait');
        return $pdf->stream('invoice-pembayaran-' . $pembayaran->pemeriksaan->pasien->nama_pasien . '.pdf');
    }
    public function cetakLaporanBulanan(Request $request)
    {
    $bulan = (int) $request->bulan; // pastikan jadi integer
        $tahun = (int) $request->tahun;

        // Ambil semua pembayaran berdasarkan bulan dan tahun
        $pembayarans = Pembayaran::whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->get();

        $nama_bulan = \Carbon\Carbon::create()->month($bulan)->translatedFormat('F');

        $pdf = Pdf::loadView('backend.pages.export.keuangan.bulanan', compact('pembayarans', 'bulan', 'tahun', 'nama_bulan'))
            ->setPaper('A4', 'landscape');

        return $pdf->stream('laporan-keuangan-' . strtolower($nama_bulan) . '-' . $tahun . '.pdf');
    }

}
