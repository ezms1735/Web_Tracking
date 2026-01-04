<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pengguna';


    protected $fillable = [
        'nama_lengkap',
        'email',
        'nomor_telepon',
        'alamat',
        'password',
        'peran',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

   
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function scopeDriver($query)
    {
        return $query->where('peran', 'driver');
    }

    public function scopePelanggan($query)
    {
        return $query->where('peran', 'pelanggan');
    }


    public function pesananSebagaiDriver()
    {
        return $this->hasMany(Pesanan::class, 'driver_id');
    }

    public function pesananSebagaiPelanggan()
    {
        return $this->hasMany(Pesanan::class, 'pelanggan_id');
    }


    public function isDriver(): bool
    {
        return $this->peran === 'driver';
    }

    public function isPelanggan(): bool
    {
        return $this->peran === 'pelanggan';
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }
}
