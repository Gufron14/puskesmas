<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');

});
Route::get('/', function () {
    return view('frontend.home');
});
Route::get('/daftar', function () {
    return view('frontend.pendaftaran');
});
Route::get('/masukan', function () {
    return view('frontend.masukan');
});
Route::get('/pendaftaran', function () {
    return view('backend.pages.pendaftaran');
});
Route::get('/pemeriksaan', function () {
    return view('backend.pages.pemeriksaan');
});
Route::get('/pembayaran', function () {
    return view('backend.pages.pembayaran');
});
Route::get('/mantri', function () {
    return view('backend.pages.mantri.index');
});
Route::get('/pasien', function () {
    return view('backend.pages.pasien.index');
});
Route::get('/rekamedis', function () {
    return view('backend.pages.rekamedis.index');
});
Route::get('/data-laporan', function () {
    return view('backend.pages.laporan.keuangan');
});
