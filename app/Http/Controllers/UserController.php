<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $pasiens = User::where('role', 'User')->orderBy('created_at', 'DESC')->get();
        return view('backend.pages.pasien.index', compact('pasiens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.pages.pasien.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'telepon' => ['required', 'string', 'max:14', 'unique:users,telepon', 'regex:/^08[0-9]{8,12}$/'],
                'password' => 'required|string|min:8',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'usia' => 'required|integer|min:1|max:150',
                'nik' => 'required|unique:users,nik|digits:16',
                'alamat' => 'required|string',
            ],
            [
                'telepon.regex' => 'Nomor telepon harus dimulai dengan 08 dan hanya berisi angka.',
                'telepon.unique' => 'Nomor telepon sudah digunakan.',
            ],
        );

        User::create([
            'name' => $request->name,
            'telepon' => $request->telepon,
            'password' => Hash::make($request->password),
            'role' => 'User',
            'jenis_kelamin' => $request->jenis_kelamin,
            'usia' => $request->usia,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
        ]);
        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $pasien)
    {
        return view('backend.pages.pasien.show', compact('pasien'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $pasien)
    {
        return view('backend.pages.pasien.edit', compact('pasien'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'telepon' => ['required', 'string', 'max:14', Rule::unique('users')->ignore($user->id), 'regex:/^08[0-9]{8,12}$/'],
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'usia' => 'required|integer|min:1|max:150',
                'nik' => 'required|digits:16',
                'alamat' => 'required|string',
            ],
            [
                'telepon.regex' => 'Nomor telepon harus dimulai dengan 08 dan hanya berisi angka.',
                'telepon.unique' => 'Nomor telepon sudah digunakan.',
                'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            ],
        );

        // Simpan foto jika diupload
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('avatars', 'public');
            $user->foto = $path;
        }

        // Update data profil
        $user->name = $request->name;
        $user->telepon = $request->telepon;
        $user->jenis_kelamin = $request->jenis_kelamin;
        $user->usia = $request->usia;
        $user->nik = $request->nik;
        $user->alamat = $request->alamat;
        $user->save();

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $pasien)
    {
        // Hapus semua pasien terkait
        $pasien->pasiens()->delete();

        // Baru hapus user
        $pasien->delete();

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil dihapus.');
    }

    public function profil()
    {
        return view('frontend.profil');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validasi
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'telepon' => ['required', 'string', 'max:14', Rule::unique('users')->ignore($user->id), 'regex:/^08[0-9]{8,12}$/'],
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'usia' => 'required|integer|min:1|max:150',
                'nik' => 'required|digits:16',
                'alamat' => 'required|string',
            ],
            [
                'telepon.regex' => 'Nomor telepon harus dimulai dengan 08 dan hanya berisi angka.',
                'telepon.unique' => 'Nomor telepon sudah digunakan.',
                'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            ],
        );

        // Simpan foto jika diupload
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('avatars', 'public');
            $user->foto = $path;
        }

        // Update data profil
        $user->name = $request->name;
        $user->telepon = $request->telepon;
        $user->jenis_kelamin = $request->jenis_kelamin;
        $user->usia = $request->usia;
        $user->nik = $request->nik;
        $user->alamat = $request->alamat;
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
