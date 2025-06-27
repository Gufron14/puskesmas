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
}
