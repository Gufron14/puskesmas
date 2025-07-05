<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;

class AntrianController extends Controller
{
    public function index()
    {
        $antrian = Antrian::first(); // ambil data pertama jika ada
        return view('backend.antrian', compact('antrian'));
    }

    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:0',
            'status' => 'required|in:on,off',
        ]);

        $antrian = Antrian::first();

        if ($antrian) {
            // Update
            $antrian->update([
                'jumlah' => $request->jumlah,
                'status' => $request->status,
            ]);
            $message = 'Antrian berhasil diperbarui.';
        } else {
            // Store
            Antrian::create([
                'jumlah' => $request->jumlah,
                'status' => $request->status,
            ]);
            $message = 'Antrian berhasil disimpan.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function bukaAntrian(Request $request)
    {
        try {
            $request->validate([
                'jumlah' => 'required|integer|min:1|max:100',
            ]);

            $antrian = Antrian::first();

            if ($antrian) {
                // Update existing antrian
                $antrian->update([
                    'jumlah' => $request->jumlah,
                    'status' => 'on',
                ]);
            } else {
                // Create new antrian
                Antrian::create([
                    'jumlah' => $request->jumlah,
                    'status' => 'on',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Antrian berhasil dibuka dengan kuota ' . $request->jumlah . ' pasien.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
