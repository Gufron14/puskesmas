<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Pembayaran;
use App\Models\Pemeriksaan;
use App\Models\Obat;
use App\Models\JenisObat;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RekamMedisController extends Controller
{
    public function index()
    {
        $pemeriksaans = Pemeriksaan::with(['user', 'pasien.user'])
            ->orderBy('created_at', 'DESC')
            ->get()
            ->map(function ($item) {
                $item->resep_decoded = json_decode($item->resep_obat, true) ?? [];
                $item->total_obat = collect($item->resep_decoded)->sum(function ($r) {
                    return ($r['jumlah'] ?? 0) * ($r['harga'] ?? 0);
                });

                // Pastikan data jenis obat ada di setiap resep
                $item->resep_formatted = collect($item->resep_decoded)->map(function ($r) {
                    // Jika tidak ada jenis_obat dalam data resep, coba ambil dari database
                    if (!isset($r['jenis_obat']) && isset($r['obat_id'])) {
                        $obat = Obat::with('jenisObat')->find($r['obat_id']);
                        if ($obat && $obat->jenisObat) {
                            $r['jenis_obat'] = $obat->jenisObat->jenis_obat;
                        } else {
                            $r['jenis_obat'] = $r['jenis_obat'] ?? 'Tidak Diketahui';
                        }
                    }

                    $r['keterangan_display'] = $r['keterangan_makan'] === 'sesudah_makan' ? 'Sesudah Makan' : 'Sebelum Makan';
                    return $r;
                });

                return $item;
            });

        return view('backend.pages.rekamedis.index', compact('pemeriksaans'));
    }

    public function show($id)
    {
        $rekamedis = Pemeriksaan::with('user')->findOrFail($id);
        $rekamedis->resep_decoded = json_decode($rekamedis->resep_obat, true) ?? [];

        $rekamedis->resep_formatted = collect($rekamedis->resep_decoded)->map(function ($r) {
            $r['keterangan_display'] = $r['keterangan_makan'] === 'sesudah_makan' ? 'Sesudah Makan' : 'Sebelum Makan';
            return $r;
        });

        $totalObat = collect($rekamedis->resep_decoded)->sum(function ($r) {
            return ($r['jumlah'] ?? 0) * ($r['harga'] ?? 0);
        });

        $resep = $rekamedis->resep_formatted ?? [];

        return view('backend.pages.rekamedis.show', compact('rekamedis', 'resep', 'totalObat'));
    }

    public function edit($id)
    {
        $pemeriksaan = Pemeriksaan::with('user')->findOrFail($id);
        $resepObat = json_decode($pemeriksaan->resep_obat, true) ?? [];

        // Jika resep kosong, buat format default
        if (empty($resepObat)) {
            $resepObat = [['nama_obat' => '', 'jenis_obat' => '', 'keterangan_makan' => '', 'jumlah' => 1]];
        }

        // Ambil data obat aktif dan jenis obat
        $obats = Obat::where('status', 'aktif')->orderBy('nama')->get();
        $jenisObats = JenisObat::all();

        return view('backend.pages.rekamedis.edit', compact('pemeriksaan', 'resepObat', 'obats', 'jenisObats'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'tanggal_pemeriksaan' => 'required|date',
                'sistolik' => 'required|numeric|min:50|max:300',
                'diastolik' => 'required|numeric|min:30|max:200',
                'suhu' => 'required|numeric|min:30|max:45',
                'diagnosa' => 'nullable|string|max:2000',
                'gejala' => 'nullable|string|max:1000',
                'catatan_dokter' => 'nullable|string|max:2000',
                'waktu_pemeriksaan' => 'required|date',
                'biaya' => 'required|numeric|min:0',
                'resep' => 'required|array|min:1',
                'resep.*.nama_obat' => 'required|exists:obats,id',
                'resep.*.jenis_obat' => 'required|exists:jenis_obats,id',
                'resep.*.keterangan_makan' => 'required|in:sesudah_makan,sebelum_makan',
                'resep.*.jumlah' => 'required|numeric|min:1',
            ],
            [
                'user_id.required' => 'Pasien harus dipilih',
                'user_id.exists' => 'Pasien tidak valid',
                'tanggal_pemeriksaan.required' => 'Tanggal pemeriksaan harus diisi',
                'sistolik.required' => 'Tekanan darah sistolik harus diisi',
                'sistolik.min' => 'Tekanan darah sistolik minimal 50',
                'sistolik.max' => 'Tekanan darah sistolik maksimal 300',
                'diastolik.required' => 'Tekanan darah diastolik harus diisi',
                'diastolik.min' => 'Tekanan darah diastolik minimal 30',
                'diastolik.max' => 'Tekanan darah diastolik maksimal 200',
                'suhu.required' => 'Suhu tubuh harus diisi',
                'suhu.min' => 'Suhu tubuh minimal 30°C',
                'suhu.max' => 'Suhu tubuh maksimal 45°C',
                'biaya.required' => 'Biaya pemeriksaan harus diisi',
                'biaya.min' => 'Biaya pemeriksaan tidak boleh negatif',
                'resep.required' => 'Resep obat harus diisi',
                'resep.*.nama_obat.required' => 'Nama obat harus dipilih',
                'resep.*.nama_obat.exists' => 'Obat yang dipilih tidak valid',
                'resep.*.jenis_obat.required' => 'Jenis obat harus dipilih',
                'resep.*.jenis_obat.exists' => 'Jenis obat yang dipilih tidak valid',
                'resep.*.keterangan_makan.required' => 'Keterangan makan harus dipilih',
                'resep.*.keterangan_makan.in' => 'Keterangan makan harus sesudah_makan atau sebelum_makan',
                'resep.*.jumlah.required' => 'Jumlah obat harus diisi',
                'resep.*.jumlah.min' => 'Jumlah obat minimal 1',
            ],
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan pada input: ' . implode(', ', $validator->errors()->all()));
        }

        try {
            DB::beginTransaction();

            $pemeriksaan = Pemeriksaan::findOrFail($id);

            // Kembalikan stok obat lama
            $resepLama = json_decode($pemeriksaan->resep_obat, true) ?? [];
            foreach ($resepLama as $resep) {
                if (isset($resep['obat_id'])) {
                    $obat = Obat::find($resep['obat_id']);
                    if ($obat) {
                        $obat->increment('stok', $resep['jumlah']);
                    }
                }
            }

            // Validasi dan kurangi stok obat baru
            $totalObat = 0;
            $resepBaru = [];

            foreach ($request->resep as $resep) {
                $obat = Obat::find($resep['nama_obat']);
                $jenisObat = JenisObat::find($resep['jenis_obat']);

                if (!$obat) {
                    throw new \Exception("Obat dengan ID {$resep['nama_obat']} tidak ditemukan");
                }

                if ($obat->stok < $resep['jumlah']) {
                    throw new \Exception("Stok obat {$obat->nama} tidak mencukupi. Stok tersedia: {$obat->stok}");
                }

                $subtotal = $resep['jumlah'] * $obat->harga;
                $totalObat += $subtotal;

                $resepBaru[] = [
                    'obat_id' => $obat->id,
                    'nama_obat' => $obat->nama,
                    'jenis_obat_id' => $jenisObat->id,
                    'jenis_obat' => $jenisObat->jenis_obat,
                    'keterangan_makan' => $resep['keterangan_makan'],
                    'jumlah' => (int) $resep['jumlah'],
                    'harga' => $obat->harga,
                    'subtotal' => $subtotal,
                ];

                // Kurangi stok obat
                $obat->decrement('stok', $resep['jumlah']);
            }

            $totalBiaya = $totalObat + $request->biaya;

            // Update pemeriksaan
            $pemeriksaan->update([
                'user_id' => $request->user_id,
                'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
                'tensi_sistolik' => $request->sistolik,
                'tensi_diastolik' => $request->diastolik,
                'suhu' => $request->suhu,
                'diagnosa' => $request->diagnosa,
                'gejala' => $request->gejala,
                'waktu_pemeriksaan' => $request->waktu_pemeriksaan,
                'catatan_dokter' => $request->catatan_dokter,
                'resep_obat' => json_encode($resepBaru),
                'biaya' => (int) $request->biaya,
                'total_obat' => $totalObat,
                'total_biaya' => $totalBiaya,
            ]);

            DB::commit();

            return redirect()->route('rekamedis')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function printLaporan(Request $request)
    {
        $tanggalMulai = $request->tanggal_mulai ?? date('Y-m-01');
        $tanggalSelesai = $request->tanggal_selesai ?? date('Y-m-d');

        $pemeriksaans = Pemeriksaan::with(['user', 'pasien.user'])
            ->whereBetween('waktu_pemeriksaan', [$tanggalMulai . ' 00:00:00', $tanggalSelesai . ' 23:59:59'])
            ->orderBy('waktu_pemeriksaan', 'DESC')
            ->get()
            ->map(function ($item) {
                $item->resep_decoded = json_decode($item->resep_obat, true) ?? [];
                $item->total_obat = collect($item->resep_decoded)->sum(function ($r) {
                    return ($r['jumlah'] ?? 0) * ($r['harga'] ?? 0);
                });

                $item->resep_formatted = collect($item->resep_decoded)->map(function ($r) {
                    if (!isset($r['jenis_obat']) && isset($r['obat_id'])) {
                        $obat = Obat::with('jenisObat')->find($r['obat_id']);
                        if ($obat && $obat->jenisObat) {
                            $r['jenis_obat'] = $obat->jenisObat->jenis_obat;
                        } else {
                            $r['jenis_obat'] = $r['jenis_obat'] ?? 'Tidak Diketahui';
                        }
                    }
                    $r['keterangan_display'] = $r['keterangan_makan'] === 'sesudah_makan' ? 'Sesudah Makan' : 'Sebelum Makan';
                    return $r;
                });

                return $item;
            });

        return view('backend.pages.print.rekamedis', compact('pemeriksaans', 'tanggalMulai', 'tanggalSelesai'));
    }

    public function exportPdf($id)
    {
        $user = Auth::user();

        // Jika admin, bisa akses semua pemeriksaan
        if ($user->role === 'Admin' || $user->role === 'Mantri') {
            $pemeriksaan = Pemeriksaan::with('user')->findOrFail($id);
        } else {
            // Jika user biasa, hanya bisa akses pemeriksaan milik sendiri
            $pemeriksaan = Pemeriksaan::with('user')->where('id', $id)->where('user_id', $user->id)->firstOrFail();
        }

        $pemeriksaan->resep_decoded = json_decode($pemeriksaan->resep_obat, true) ?? [];
        $pemeriksaan->total_obat = collect($pemeriksaan->resep_decoded)->sum(function ($r) {
            return ($r['jumlah'] ?? 0) * ($r['harga'] ?? 0);
        });

        try {
            $pdf = Pdf::loadView('backend.pages.export.pemeriksaan.pdf', [
                'pemeriksaan' => $pemeriksaan,
            ])->setPaper('A4', 'portrait');

            return $pdf->download('rekam-medis-' . $pemeriksaan->user->name . '.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }

    public function printPemeriksaan($id)
    {
        $pemeriksaan = Pemeriksaan::with('user')->where('id', $id)->firstOrFail();

        $pemeriksaan->resep_decoded = json_decode($pemeriksaan->resep_obat, true) ?? [];
        $pemeriksaan->total_obat = collect($pemeriksaan->resep_decoded)->sum(function ($r) {
            return ($r['jumlah'] ?? 0) * ($r['harga'] ?? 0);
        });

        return view('backend.pages.print.pemeriksaan', compact('pemeriksaan'));
    }

    public function keuangan()
    {
        $pembayarans = Pembayaran::with(['pemeriksaan.user'])
            ->latest()
            ->get();
        return view('backend.pages.laporan.keuangan', compact('pembayarans'));
    }

    public function cetakInvoice($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $pdf = Pdf::loadView('backend.pages.export.keuangan.pdf', compact('pembayaran'))->setPaper('A4', 'portrait');
        return $pdf->stream('invoice-pembayaran-' . $pembayaran->pemeriksaan->user->name . '.pdf');
    }

    public function printInvoice($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        return view('backend.pages.print.invoice', compact('pembayaran'));
    }

    public function cetakLaporanBulanan(Request $request)
    {
        $bulan = (int) $request->bulan;
        $tahun = (int) $request->tahun;

        $pembayarans = Pembayaran::whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->get();

        $nama_bulan = \Carbon\Carbon::create()->month($bulan)->translatedFormat('F');

        $pdf = Pdf::loadView('backend.pages.export.keuangan.bulanan', compact('pembayarans', 'bulan', 'tahun', 'nama_bulan'))->setPaper('A4', 'landscape');

        return $pdf->stream('laporan-keuangan-' . strtolower($nama_bulan) . '-' . $tahun . '.pdf');
    }

    public function printLaporanBulanan(Request $request)
    {
        $bulan = (int) $request->bulan;
        $tahun = (int) $request->tahun;

        $pembayarans = Pembayaran::whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->get();

        $nama_bulan = \Carbon\Carbon::create()->month($bulan)->translatedFormat('F');

        return view('backend.pages.print.laporan-bulanan', compact('pembayarans', 'bulan', 'tahun', 'nama_bulan'));
    }

    public function rekamMedis()
    {
        $user = Auth::user();
        $pemeriksaans = collect();

        if ($user) {
            $pemeriksaans = Pemeriksaan::with('user')
                ->where('user_id', $user->id)
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
