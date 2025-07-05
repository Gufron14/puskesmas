<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dari model Obat
        $obats = Obat::all();

        // Filter Search
        if (request('search')) {
            $obats = Obat::where(function ($query) {
                $query->where('nama', 'like', '%' . request('search') . '%')
                    ->orWhere('deskripsi', 'like', '%' . request('search') . '%');
            })->get();
        }

        return view('backend.pages.obat', compact('obats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Create Obat
        return view('backend.pages.obat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Store Obat
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $obat = Obat::create([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'stok' => $request->stok ?? 0,
            'deskripsi' => $request->deskripsi,
        ]);

        // Return JSON response for AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Obat berhasil ditambahkan',
                'data' => $obat
            ]);
        }

        // Validasi
        if ($obat) {
            return redirect()->route('obat.index')->with('success', 'Obat berhasil ditambahkan');
        } else {
            return redirect()->route('obat.index')->with('error', 'Obat gagal ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Show Obat
        $obat = Obat::find($id);

        if (!$obat) {
            return response()->json(['error' => 'Obat tidak ditemukan'], 404);
        }

        // Return JSON response for AJAX
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $obat
            ]);
        }

        return view('backend.pages.obat.show', compact('obat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Edit Obat
        $obat = Obat::find($id);

        if (!$obat) {
            return response()->json(['error' => 'Obat tidak ditemukan'], 404);
        }

        // Return JSON response for AJAX
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $obat
            ]);
        }

        return view('backend.pages.obat.edit', compact('obat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Update Obat
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $obat = Obat::find($id);
        
        if (!$obat) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Obat tidak ditemukan'], 404);
            }
            return redirect()->route('obat.index')->with('error', 'Obat tidak ditemukan');
        }

        $obat->update([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'stok' => $request->stok ?? 0,
            'deskripsi' => $request->deskripsi,
        ]);

        // Return JSON response for AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Obat berhasil diupdate',
                'data' => $obat
            ]);
        }

        // Validasi
        if ($obat) {
            return redirect()->route('obat.index')->with('success', 'Obat berhasil diupdate');
        } else {
            return redirect()->route('obat.index')->with('error', 'Obat gagal diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Hapus Obat
        $obat = Obat::find($id);
        
        if (!$obat) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Obat tidak ditemukan'], 404);
            }
            return redirect()->route('obat.index')->with('error', 'Obat tidak ditemukan');
        }

        $obat->delete();

        // Return JSON response for AJAX
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Obat berhasil dihapus'
            ]);
        }

        return redirect()->route('obat.index')->with('success', 'Obat berhasil dihapus');
    }
}
