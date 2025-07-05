<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'telepon',
        'password',
        'foto',
        'role',
        'jenis_kelamin',
        'usia',
        'nik',
        'alamat',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

        /**
     * Cek apakah user dapat dihapus (tidak memiliki data terkait)
     */
    public function canBeDeleted()
    {
        return !$this->hasRelatedData();
    }
    
    /**
     * Cek apakah user memiliki data terkait
     */
    public function hasRelatedData()
    {
        return $this->pasiens()->exists() || 
               $this->pemeriksaans()->exists() || 
               $this->masukans()->exists();
    }
    
    /**
     * Dapatkan informasi data terkait
     */
    public function getRelatedDataInfo()
    {
        $info = [];
        
        $pasienCount = $this->pasiens()->count();
        if ($pasienCount > 0) {
            $info[] = "{$pasienCount} data pasien";
        }
        
        // Tambahkan pengecekan untuk relasi lain
        // $pemeriksaanCount = $this->pemeriksaans()->count();
        // if ($pemeriksaanCount > 0) {
        //     $info[] = "{$pemeriksaanCount} data pemeriksaan";
        // }
        
        return $info;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

        /**
     * Relasi ke model Pasien
     */
    public function pasiens()
    {
        return $this->hasMany(Pasien::class);
    }
    
    /**
     * Cek apakah user sudah ada dalam antrian pada tanggal tertentu
     */
    public function sudahDalamAntrian($tanggal = null)
    {
        $tanggal = $tanggal ?? now()->toDateString();
        
        return $this->pasiens()
            ->where('tanggal_antrian', $tanggal)
            ->exists();
    }
}
