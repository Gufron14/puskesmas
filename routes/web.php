<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\AntrianController;
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
    Route::post('/profil/update', [UserController::class, 'updateProfile'])->name('profil.update');
    Route::post('/saran-keluhan', [MasukanController::class, 'store'])->name('masukan.post');
    Route::post('/daftar', [PendaftaranController::class, 'store'])->name('daftar.post');
    Route::post('/get-nomor-antrian', [PendaftaranController::class, 'getNomorAntrian'])->name('get.nomor.antrian');
    Route::get('/rekammedis/pdf/{id}', [RekamMedisController::class, 'exportPdf'])->name('pemeriksaan.exportPdf');
});

// Khusus admin
Route::middleware(['auth', 'role:Admin,Mantri'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
    Route::resource('pasien', UserController::class);
    Route::resource('antrian', PasienController::class);
    Route::get('/pengaturan/antrian', [AntrianController::class, 'index'])->name('pengaturan.antrian');
    Route::post('/antrian/post', [AntrianController::class, 'storeOrUpdate'])->name('pengaturan.antrian.store');
    Route::post('/antrian/buka', [AntrianController::class, 'bukaAntrian'])->name('antrian.buka');
    Route::get('/pemeriksaan', [PemeriksaanController::class, 'index'])->name('pemeriksaan');
    Route::post('/pemeriksaan', [PemeriksaanController::class, 'store'])->name('pemeriksaan.post');
    Route::delete('/pemeriksaan/{pemeriksaan}', [PemeriksaanController::class, 'destroy'])->name('pemeriksaan.destroy');
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.post');
    Route::delete('/pembayaran/{pembayaran}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy');
    Route::get('/rekamedis', [RekamMedisController::class, 'index'])->name('rekamedis');

    
    Route::get('/rekamedis/edit/{id}', [RekamMedisController::class, 'edit'])->name('rekamedis.edit');
    Route::put('/rekamedis/update/{id}', [RekamMedisController::class, 'update'])->name('rekamedis.update');
    Route::get('/rekamedis/{id}', [RekamMedisController::class, 'show'])->name('rekamedis.show');

    Route::get('/data-laporan', [RekamMedisController::class, 'keuangan'])->name('datalaporan');
    Route::get('/pembayaran/invoice/{id}', [RekamMedisController::class, 'cetakInvoice'])->name('pembayaran.invoice');
    Route::get('/laporan/bulanan', [RekamMedisController::class, 'cetakLaporanBulanan'])->name('laporan.bulanan');
    Route::delete('/pasien/{id}', [PasienController::class, 'destroy'])->name('pasien.destroy');

    Route::get('/admin/rekamedis/print-laporan', [RekamMedisController::class, 'printLaporan'])->name('rekamedis.print');

    // Print routes

    Route::get('/pembayaran/print/{id}', [RekamMedisController::class, 'printInvoice'])->name('pembayaran.print');
    Route::get('/laporan/print-bulanan', [RekamMedisController::class, 'printLaporanBulanan'])->name('laporan.print');

    // Spesifik Resource CRUD Obat
    Route::get('/obat', [ObatController::class, 'index'])->name('obat.index');
    Route::get('/obat/create', [ObatController::class, 'create'])->name('obat.create');
    Route::post('/obat', [ObatController::class, 'store'])->name('obat.store');
    Route::get('/obat/{id}/edit', [ObatController::class, 'edit'])->name('obat.edit');
    Route::put('/obat/{id}', [ObatController::class, 'update'])->name('obat.update');
    Route::delete('/obat/{id}', [ObatController::class, 'destroy'])->name('obat.destroy');
});

Route::get('/pemeriksaan/print/{id}', [RekamMedisController::class, 'printPemeriksaan'])->name('laporan.print');

Route::get('/pemeriksaan/export-pdf/{id}', [RekamMedisController::class, 'exportPdf'])->name('pemeriksaan.exportPdf');

Route::get('/saran-keluhan', [MasukanController::class, 'index'])->name('masukan');
Route::get('/pelayanan', [PendaftaranController::class, 'index'])->name('daftar');
Route::get('/riwayat/rekam-medis', [RekamMedisController::class, 'rekamMedis'])->name('riwayat');
Route::get('/', [HomeController::class, 'frontend'])->name('frontend');

