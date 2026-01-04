<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $fillable = [
        'pelanggan_id',
        'driver_id',
        'jumlah_pesanan',
        'status_pesanan',
    ];

    /* ================= RELATION ================= */

    public function pelanggan()
    {
        return $this->belongsTo(Pengguna::class, 'pelanggan_id');
    }

    public function driver()
    {
        return $this->belongsTo(Pengguna::class, 'driver_id');
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class);
    }
}
