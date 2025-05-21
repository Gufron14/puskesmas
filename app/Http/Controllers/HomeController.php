<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $mantri     = 0;
        $pasien     = 0;
        $rekamedis  = 0;
        $totaluang  = 0;
        return view('backend.pages.dashboard', compact('mantri', 'pasien', 'rekamedis', 'totaluang'));
    }
}
