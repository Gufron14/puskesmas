<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal_pemeriksaan',
        'tensi_sistolik',
        'tensi_diastolik',
        'suhu',
        'diagnosa',
        'gejala',
        'waktu_pemeriksaan',
        'catatan_dokter',
        'resep_obat',
        'biaya',
        'total_obat',
        'total_biaya',
    ];

    protected $casts = [
        'tanggal_pemeriksaan' => 'date',
        'waktu_pemeriksaan' => 'datetime',
        'biaya' => 'decimal:2',
        'total_obat' => 'decimal:2',
        'total_biaya' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'user_id', 'user_id');
    }

    // public function obats()
    // {
    //     return $this->belongsToMany(Obat::class, 'pemeriksaan_obat', 'pemeriksaan_id', 'obat_id');
    // }
}
