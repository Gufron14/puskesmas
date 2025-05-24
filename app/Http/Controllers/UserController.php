<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function profil()
    {
        return view('frontend.profil');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Simpan file foto jika diupload
        if ($request->hasFile('foto')) {
            // Simpan foto baru
            $path = $request->file('foto')->store('avatars', 'public');
            $user->foto = $path;
        }

        // Update data user
        $user->name = $request->name;
        $user->telepon = $request->telepon;
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
