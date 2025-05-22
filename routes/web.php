<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PemeriksaanController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
    Route::resource('pasien', PasienController::class);
    Route::get('/pendaftaran', [PasienController::class, 'create'])->name('pendaftaran');
    Route::get('/pemeriksaan', [PemeriksaanController::class, 'index'])->name('pemeriksaan');
    Route::post('/pemeriksaan', [PemeriksaanController::class, 'store'])->name('pemeriksaan.post');
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');


});
Route::get('/', function () {
    return view('frontend.home');
});
Route::get('/daftar', function () {
    return view('frontend.pendaftaran');
});
Route::get('/profil', function () {
    return view('frontend.profil');
});
Route::get('/masukan', function () {
    return view('frontend.masukan');
});
Route::get('/mantri', function () {
    return view('backend.pages.mantri.index');
});
Route::get('/rekamedis', function () {
    return view('backend.pages.rekamedis.index');
});
Route::get('/data-laporan', function () {
    return view('backend.pages.laporan.keuangan');
});
