<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;

    protected $table = 'pengguna';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'nomor_telepon',
        'alamat',
        'password',
        'peran',
        'status',
        'latitude',
        'longitude'
    ];

    protected $hidden = [
        'password'
    ];

    /* =====================
     | RELASI
     ===================== */

     public function pesananSebagaiPelanggan()
    {
        return $this->hasMany(Pesanan::class, 'pelanggan_id');
    }

    public function pesananSebagaiDriver()
    {
        return $this->hasMany(Pesanan::class, 'driver_id');
    }

    /* =====================
     | SCOPE
     ===================== */

    public function scopeDriver($query)
    {
        return $query->where('peran', 'driver');
    }

    public function scopePelanggan($query)
    {
        return $query->where('peran', 'pelanggan');
    }

    
}

