<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Pasien;
use App\Models\JenisObat;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;
use Illuminate\Support\FacadesDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PemeriksaanController extends Controller
{
public function index(Request $request)
{
    $tanggal = now()->toDateString();
    $pasiens = Pasien::with('user')
        ->where('status', 'menunggu')
        ->where('tanggal_antrian', $tanggal)
        ->orderBy('nomor_antrian', 'ASC')
        ->get();

    $jenisObatId = $request->input('jenis_obat_id');

    if ($jenisObatId) {
        $obats = Obat::where('status', 'aktif')
            ->where('stok', '>', 0)
            ->where('jenis_obat_id', $jenisObatId) // filter by jenis obat
            ->orderBy('nama')
            ->get();
    } else {
        $obats = Obat::where('status', 'aktif')
            ->where('stok', '>', 0)
            ->orderBy('nama')
            ->get();
    }

    $jenisObats = JenisObat::all();

    return view('backend.pages.pemeriksaan', compact('pasiens', 'obats', 'jenisObats', 'jenisObatId'));
}
    
    public function create()
    {
        $tanggal = now()->toDateString();
        $pasiens = Pasien::with('user')->where('tanggal_antrian', $tanggal)->orderBy('nomor_antrian')->get();
        $obats = Obat::where('status', 'aktif')->where('stok', '>', 0)->orderBy('nama')->get();
        $jenisObats = JenisObat::all();
    
        return view('backend.pages.pemeriksaan', compact('pasiens', 'obats', 'jenisObats'));
    }

    /**
     * Show detailed information for a specific examination
     */
    public function show($id)
    {
        try {
            $pemeriksaan = Pemeriksaan::with(['user', 'pasien.user'])
                ->findOrFail($id);
            
            // Decode resep obat
            $resepObat = json_decode($pemeriksaan->resep_obat, true) ?? [];
            
            // Calculate totals
            $totalObat = collect($resepObat)->sum(function($obat) {
                return ($obat['jumlah'] ?? 0) * ($obat['harga'] ?? 0);
            });
            
            $totalBiaya = $totalObat + ($pemeriksaan->biaya ?? 0);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'pemeriksaan' => $pemeriksaan,
                    'resep_obat' => $resepObat,
                    'total_obat' => $totalObat,
                    'total_biaya' => $totalBiaya,
                    'formatted' => [
                        'tanggal' => \Carbon\Carbon::parse($pemeriksaan->waktu_pemeriksaan)->format('d/m/Y H:i'),
                        'tanggal_lengkap' => \Carbon\Carbon::parse($pemeriksaan->waktu_pemeriksaan)->locale('id')->translatedFormat('l, d F Y, H:i'),
                        'total_obat' => 'Rp' . number_format($totalObat, 0, ',', '.'),
                        'total_biaya' => 'Rp' . number_format($totalBiaya, 0, ',', '.'),
                        'biaya_pemeriksaan' => 'Rp' . number_format($pemeriksaan->biaya ?? 0, 0, ',', '.')
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }
    
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
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
        'resep.*.keterangan_makan' => 'required',
        'resep.*.jumlah' => 'required|numeric|min:1',
    ]);

    if ($validator->fails()) {
        return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput()
            ->with('error', 'Terdapat kesalahan pada form: ' . implode(', ', $validator->errors()->all()));
    }

    try {
        DB::beginTransaction();

        $totalObat = 0;
        $resepFormatted = [];

        foreach ($request->resep as $resep) {
            $obat = Obat::find($resep['nama_obat']);
            $jenisObat = JenisObat::find($resep['jenis_obat']);
            
            if (!$obat) {
                throw new \Exception('Obat tidak ditemukan');
            }

            if ($obat->stok < $resep['jumlah']) {
                throw new \Exception("Stok obat {$obat->nama} tidak mencukupi. Stok tersedia: {$obat->stok}");
            }

            $subtotal = $resep['jumlah'] * $obat->harga;
            $totalObat += $subtotal;

            $resepFormatted[] = [
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

        // Simpan data pemeriksaan
        $pemeriksaan = Pemeriksaan::create([
            'user_id' => $request->user_id,
            'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
            'tensi_sistolik' => $request->sistolik,
            'tensi_diastolik' => $request->diastolik,
            'suhu' => $request->suhu,
            'diagnosa' => $request->diagnosa,
            'gejala' => $request->gejala,
            'waktu_pemeriksaan' => $request->waktu_pemeriksaan,
            'catatan_dokter' => $request->catatan_dokter,
            'resep_obat' => json_encode($resepFormatted), // This line ensures all prescriptions are saved
            'biaya' => $request->biaya,
            'total_obat' => $totalObat,
            'total_biaya' => $totalBiaya,
        ]);

        // Update status pasien
        $pasien = Pasien::where('user_id', $request->user_id)->first();
        if ($pasien) {
            $pasien->update(['status' => 'selesai']);
        }

        DB::commit();

        return redirect()->route('rekamedis')->with('success', 'Data pemeriksaan berhasil disimpan.');

    } catch (\Exception $e) {
        DB::rollback();
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
    

    public function destroy(Pemeriksaan $pemeriksaan)
    {
        try {
            DB::beginTransaction();

            // Kembalikan stok obat
            $resepObat = json_decode($pemeriksaan->resep_obat, true) ?? [];
            foreach ($resepObat as $resep) {
                if (isset($resep['obat_id'])) {
                    $obat = Obat::find($resep['obat_id']);
                    if ($obat) {
                        $obat->increment('stok', $resep['jumlah']);
                    }
                }
            }

            $pemeriksaan->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Data berhasil dihapus dan stok obat dikembalikan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
