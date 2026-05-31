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
        'expo_token',
    ];

    protected $hidden = [
        'password'
    ];

     public function pesananSebagaiPelanggan()
    {
        return $this->hasMany(Pesanan::class, 'pelanggan_id');
    }

    public function scopeDriver($query)
    {
        return $query->where('peran', 'driver');
    }

    public function scopePelanggan($query)
    {
        return $query->where('peran', 'pelanggan');
    }

    public function pengiriman()
    {
        return $this->hasMany(Pengiriman::class, 'driver_id');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'pengguna_id');
    }
}

