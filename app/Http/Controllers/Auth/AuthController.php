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
     * Tampilkan halaman register admin (sementara untuk debug)
     */
    public function showRegisterAdmin()
    {
        return view('auth.register-admin');
    }

    /**
     * Proses registrasi user baru
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'telepon'        => [
                'required',
                'string',
                'max:14',
                'unique:users,telepon',
                'regex:/^08[0-9]{8,12}$/'
            ],
            'password'       => 'required|string|min:8',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'usia'           => 'required|integer|min:1|max:150',
            'nik'            => 'required|digits:16',
            'alamat'         => 'required|string',
        ], [
            'telepon.regex'     => 'Nomor telepon harus dimulai dengan 08 dan hanya berisi angka.',
            'telepon.unique'    => 'Nomor telepon sudah digunakan.',
        ]);

        User::create([
            'name'          => $request->name,
            'telepon'       => $request->telepon,
            'password'      => Hash::make($request->password),
            'role'          => 'User',
            'jenis_kelamin' => $request->jenis_kelamin,
            'usia'          => $request->usia,
            'nik'           => $request->nik,
            'alamat'        => $request->alamat,
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
    }

    /**
     * Proses registrasi admin/mantri/puskesmas induk (sementara untuk debug)
     */
    public function registerAdmin(Request $request)
    {
        $request->validate([
            'role'           => 'required|in:Admin,Mantri,Puskesmas Induk',
            'name'           => 'required|string|max:255',
            'telepon'        => [
                'required',
                'string',
                'max:14',
                'unique:users,telepon',
                'regex:/^08[0-9]{8,12}$/'
            ],
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|string|min:8|confirmed',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'usia'           => 'required|integer|min:18|max:70',
            'nik'            => 'required|digits:16|unique:users,nik',
            'alamat'         => 'required|string',
        ], [
            'telepon.regex'     => 'Nomor telepon harus dimulai dengan 08 dan hanya berisi angka.',
            'telepon.unique'    => 'Nomor telepon sudah digunakan.',
            'email.unique'      => 'Email sudah digunakan.',
            'nik.unique'        => 'NIK sudah digunakan.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'name'              => $request->name,
            'telepon'           => $request->telepon,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'role'              => $request->role,
            'jenis_kelamin'     => $request->jenis_kelamin,
            'usia'              => $request->usia,
            'nik'               => $request->nik,
            'alamat'            => $request->alamat,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('register.admin')->with('success', 
            "Registrasi {$request->role} berhasil! Silakan login dengan telepon: {$request->telepon}");
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

            $user = Auth::user();
            $role = strtolower($user->role);

            // Debug log untuk cek role
            \Log::info('User login:', ['user' => $user->telepon, 'role' => $user->role, 'role_lower' => $role]);

            if (in_array($role, ['admin', 'mantri', 'puskesmas induk'])) {
                return redirect()->intended('/dashboard');
            } else {
                return redirect()->intended('/'); // halaman frontend untuk user biasa
            }
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

        return redirect()->route('frontend');
    }
}
