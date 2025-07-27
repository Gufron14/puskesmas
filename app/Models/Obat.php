<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Obat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'jenis_obat_id',
        'jenis_obat', // Keep for backward compatibility
        'harga',
        'stok',
        'status',
        'deskripsi'
    ];

    /**
     * Relationship with JenisObat model
     * Many Obat belongs to one JenisObat
     */
    public function jenisObat()
    {
        return $this->belongsTo(JenisObat::class, 'jenis_obat_id');
    }

    /**
     * Legacy relationship - keep for backward compatibility
     */
    public function jenisObats()
    {
        return $this->belongsTo (JenisObat::class, 'jenis_obat_id');
    }

    /**
     * Relationship with Pemeriksaan through resep_obat JSON field
     */
    public function pemeriksaans()
    {
        return $this->hasMany(Pemeriksaan::class);
    }

    protected $casts = [
        'harga' => 'decimal:2',
        'stok' => 'integer'
    ];

    // Scope untuk obat aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope untuk obat dengan stok tersedia
    public function scopeStokTersedia($query)
    {
        return $query->where('stok', '>', 0);
    }

    // Accessor untuk format harga
    public function getHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Mutator untuk nama obat (capitalize)
    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = ucwords(strtolower($value));
    }
}
