<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('frontend.home');
});
Route::get('/daftar', function () {
    return view('frontend.pendaftaran');
});
Route::get('/masukan', function () {
    return view('frontend.masukan');
});
Route::get('/register', function () {
    return view('auth.register');
});
Route::get('/login', function () {
    return view('auth.login');
});
Route::get('/dashboard', function () {
    return view('backend.pages.dashboard');
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
