<?php

namespace App\Models;

use App\Models\Pasien;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pemeriksaan extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

}
