<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengiriman;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PemantauanController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Ambil semua pengiriman yang punya driver (status berapa pun)
        $pengirimanAktif = Pengiriman::with(['driver', 'pesanan.pelanggan'])
            ->whereNotNull('driver_id')
            ->latest()
            ->get()
            ->groupBy('driver_id');

        // Untuk tampilan list, ambil pengiriman hari ini
        $pengirimanHariIni = Pengiriman::with(['driver', 'pesanan.pelanggan'])
            ->whereNotNull('driver_id')
            ->whereDate('waktu_mulai', $today)
            ->latest()
            ->get()
            ->groupBy('driver_id');

        $markers = [];

        // Ambil dari Firebase
        try {
            $database = app('firebase.database');
            $firebaseDrivers = $database
                ->getReference('driver_locations')
                ->getValue() ?? [];
        } catch (\Throwable $e) {
            Log::error('Firebase error: ' . $e->getMessage());
            $firebaseDrivers = [];
        }

        // Loop semua pengiriman untuk ambil lokasi driver
        foreach ($pengirimanAktif as $driver_id => $grup) {
            $driver = $grup->first()->driver;
            
            if (!$driver) continue;

            $totalPengiriman = $grup->count();
            if ($totalPengiriman === 0) continue;

            // Coba ambil dari Firebase dulu
            $lat = null;
            $lng = null;

            if (isset($firebaseDrivers[$driver_id]) && is_array($firebaseDrivers[$driver_id])) {
                $lokasi = $firebaseDrivers[$driver_id];
                if (isset($lokasi['latitude']) && isset($lokasi['longitude'])) {
                    $lat = (float) $lokasi['latitude'];
                    $lng = (float) $lokasi['longitude'];
                }
            }

            // Fallback ke database lokal jika Firebase kosong
            if (!$lat || !$lng) {
                if ($driver->latitude && $driver->longitude) {
                    $lat = (float) $driver->latitude;
                    $lng = (float) $driver->longitude;
                }
            }

            // Hanya tambah marker jika ada lokasi
            if ($lat && $lng) {
                $markers[] = [
                    'lat'    => $lat,
                    'lng'    => $lng,
                    'nama'   => $driver->nama_lengkap ?? 'Driver',
                    'jumlah' => $totalPengiriman,
                ];
            }
        }

        return view(
            'admin.pemantauan.index',
            compact('pengirimanHariIni', 'markers')
        );
    }
}
