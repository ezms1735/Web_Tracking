<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengguna extends Authenticatable
{
    use HasFactory;

    protected $table = 'pengguna';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'peran',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function pesananSebagaiDriver()
    {
        return $this->hasMany(Pesanan::class, 'driver_id');
    }

    public function scopeDriver($query)
    {
        return $query->where('peran', 'driver');
    }
}
