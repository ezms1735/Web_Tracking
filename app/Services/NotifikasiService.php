<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\Pengguna;

class NotifikasiService
{
    public static function kirim(
        int $penggunaId,
        string $judul,
        string $pesan,
        string $tipe,
        ?int $referensiId = null,
        ?string $referensiTipe = null
    ): Notifikasi {

        $notifikasi = Notifikasi::create([
            'pengguna_id'    => $penggunaId,
            'judul'          => $judul,
            'pesan'          => $pesan,
            'tipe'           => $tipe,
            'referensi_id'   => $referensiId,
            'referensi_tipe' => $referensiTipe,
            'dibaca'         => false,
        ]);

        $pengguna = Pengguna::find($penggunaId);

        if ($pengguna?->expo_token) {

            ExpoPushService::kirim(
                $pengguna->expo_token,
                $judul,
                $pesan,
                [
                    'tipe'           => $tipe,
                    'referensi_id'   => $referensiId,
                    'referensi_tipe' => $referensiTipe,
                ]
            );
        }

        return $notifikasi;
    }

    public static function kePelanggan(int $pelangganId, string $judul, string $pesan, string $tipe, int $pesananId): Notifikasi
    {
        return self::kirim($pelangganId, $judul, $pesan, $tipe, $pesananId, 'pesanan');
    }

    public static function keDriver(int $driverId, string $judul, string $pesan, string $tipe, int $pengirimanId): Notifikasi
    {
        return self::kirim($driverId, $judul, $pesan, $tipe, $pengirimanId, 'pengiriman');
    }
}
