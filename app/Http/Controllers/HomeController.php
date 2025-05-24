<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Masukan;
use App\Models\Pembayaran;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $pasien     = Pasien::count();
        $rekamedis  = Pemeriksaan::count();
        $totaluang  = Pembayaran::sum('jumlah_bayar');
        return view('backend.pages.dashboard', compact('pasien', 'rekamedis', 'totaluang'));
    }
    public function frontend()
    {
        $masukans = Masukan::latest()->get();
        return view('frontend.home', compact('masukans'));
    }
}
