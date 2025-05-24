<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\MasukanController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\PendaftaranController;

Route::get('/', [HomeController::class, 'frontend'])->name('frontend');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profil', [UserController::class, 'profil'])->name('profil');
    Route::post('/profil/update', [UserController::class, 'update'])->name('profil.update');
});

// Khusus admin
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
    Route::resource('pasien', PasienController::class);
    Route::get('/pendaftaran', [PasienController::class, 'create'])->name('pendaftaran');
    Route::get('/pemeriksaan', [PemeriksaanController::class, 'index'])->name('pemeriksaan');
    Route::post('/pemeriksaan', [PemeriksaanController::class, 'store'])->name('pemeriksaan.post');
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.post');
    Route::get('/rekamedis', [RekamMedisController::class, 'index'])->name('rekamedis');
    Route::get('/pemeriksaan/export-pdf/{id}', [RekamMedisController::class, 'exportPdf'])->name('pemeriksaan.exportPdf');
    Route::get('/data-laporan', [RekamMedisController::class, 'keuangan'])->name('datalaporan');
    Route::get('/pembayaran/invoice/{id}', [RekamMedisController::class, 'cetakInvoice'])->name('pembayaran.invoice');
    Route::get('/laporan/bulanan', [RekamMedisController::class, 'cetakLaporanBulanan'])->name('laporan.bulanan');
});

    Route::get('/', [HomeController::class, 'frontend'])->name('frontend');


    Route::get('/saran-keluhan', [MasukanController::class, 'index'])->name('masukan');
    Route::post('/saran-keluhan', [MasukanController::class, 'store'])->name('masukan.post');
    Route::get('/daftar', [PendaftaranController::class, 'index'])->name('daftar');
    Route::post('/daftar', [PendaftaranController::class, 'store'])->name('daftar.post');
