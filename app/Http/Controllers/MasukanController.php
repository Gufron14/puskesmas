<?php

namespace App\Http\Controllers;

use App\Models\Masukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasukanController extends Controller
{
    public function index()
    {
        return view('frontend.masukan');
    }
    public function store(Request $request)
    {
        $request->validate([
            'masukan' => 'nullable|string|max:1000',
            'keluhan' => 'nullable|string|max:1000',
        ]);

        Masukan::create([
            'user_id' => Auth::id(),
            'masukan' => $request->masukan,
            'keluhan' => $request->keluhan,
        ]);

        return redirect()->route('masukan')->with('success', 'Masukan dan keluhan berhasil dikirim.');
    }
}
