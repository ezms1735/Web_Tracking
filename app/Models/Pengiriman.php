<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    protected $table = 'pengiriman';

    protected $fillable = [
        'pesanan_id',
        'driver_id',
        'status_pengiriman',
        'waktu_mulai',
        'waktu_selesai',
    ];

    protected $casts = [
        'waktu_mulai'   => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    /* ================= RELATION ================= */

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function driver()
    {
        return $this->belongsTo(Pengguna::class, 'driver_id');
    }
}
