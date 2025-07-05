<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisObat extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_obat',
        'deskripsi',
        'status'
    ];

    /**
     * Relationship with Obat model
     * One JenisObat can have many Obat
     */
    public function obats()
    {
        return $this->hasMany(Obat::class, 'jenis_obat_id');
    }

    /**
     * Scope for active drug types
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Accessor for formatted name
     */
    public function getJenisObatFormattedAttribute()
    {
        return ucwords(strtolower($this->jenis_obat));
    }

    /**
     * Mutator for jenis_obat (capitalize)
     */
    public function setJenisObatAttribute($value)
    {
        $this->attributes['jenis_obat'] = ucwords(strtolower($value));
    }
}
