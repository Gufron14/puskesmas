<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Tampilkan halaman register
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi user baru
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'telepon'   => [
                'required',
                'string',
                'max:14',
                'unique:users,telepon',
                'regex:/^08[0-9]{8,12}$/'
            ],
            'password'  => 'required|string|min:8',
        ],[
            'telepon.regex'  => 'Nomor telepon harus dimulai dengan 08 dan hanya berisi angka.',
            'telepon.unique' => 'Nomor telepon sudah digunakan.',
        ]);



        User::create([
            'name'     => $request->name,
            'telepon'  => $request->telepon,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
    }

    /**
     * Proses login user
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'telepon'   => [
                'required',
                'string',
                'max:14',
                'regex:/^08[0-9]{8,12}$/'
            ],
            'password' => 'required',
        ], [
            'telepon.regex'  => 'Nomor telepon harus dimulai dengan 08 dan hanya berisi angka.',
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'telepon' => 'Nomor telepon atau password salah.',
        ])->onlyInput('telepon');
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
