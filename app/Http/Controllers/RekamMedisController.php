<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Pembayaran;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class RekamMedisController extends Controller
{
    public function index()
    {
        $pemeriksaans = Pemeriksaan::with('pasien')
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
    public function edit($id)
    {
        $tanggal = now()->toDateString(); // hari ini
        $pasiens = Pasien::where('tanggal_antrian', $tanggal)
            ->orderBy('nomor_antrian')
            ->get();
        $pemeriksaan = Pemeriksaan::with('pasien')->findOrFail($id);
        $resepObat = json_decode($pemeriksaan->resep_obat, true) ?? [];

        return view('backend.pages.rekamedis.edit', compact('pemeriksaan', 'pasiens', 'resepObat'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'pasien_id'            => 'required|exists:pasiens,id',
            'tanggal_pemeriksaan'  => 'required|date',
            'sistolik'             => 'required|numeric',
            'diastolik'            => 'required|numeric',
            'gejala'               => 'nullable|string',
            'catatan_dokter'       => 'nullable|string',
            'biaya'                => 'nullable|numeric',
            'resep'                => 'nullable|array',
        ]);

        $pemeriksaan = Pemeriksaan::findOrFail($id);

        $pemeriksaan->update([
            'pasien_id'            => $request->pasien_id,
            'tanggal_pemeriksaan'  => $request->tanggal_pemeriksaan,
            'tensi_sistolik'       => $request->sistolik,
            'tensi_diastolik'      => $request->diastolik,
            'gejala'               => $request->gejala,
            'catatan_dokter'       => $request->catatan_dokter,
            'resep_obat'           => json_encode($request->resep),
            'biaya'                => $request->biaya,
        ]);

        return redirect()->route('rekamedis')->with('success', 'Data berhasil diperbarui.');
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

public function rekamMedis()
{
    $user = Auth::user();
    $pemeriksaans = collect(); // Default kosong untuk guest

    if ($user) {
        $pemeriksaans = Pemeriksaan::with('pasien')
            ->whereHas('pasien', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'DESC')
            ->get()
            ->map(function ($item) {
                $item->resep_decoded = json_decode($item->resep_obat, true) ?? [];
                $item->total_obat = collect($item->resep_decoded)->sum(function ($r) {
                    return ($r['jumlah'] ?? 0) * ($r['harga'] ?? 0);
                });
                return $item;
            });
    }

    return view('frontend.rekammedis', compact('pemeriksaans', 'user'));
}



}
