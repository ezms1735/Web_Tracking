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

        $pengirimanAktif = Pengiriman::with(['driver', 'pesanan.pelanggan'])
            ->whereNotNull('driver_id')
            ->latest()
            ->get()
            ->groupBy('driver_id');

        $pengirimanHariIni = Pengiriman::with(['driver', 'pesanan.pelanggan'])
            ->whereNotNull('driver_id')
            ->whereDate('waktu_mulai', $today)
            ->latest()
            ->get()
            ->groupBy('driver_id');

        $markers = [];

        try {
            $database = app('firebase.database');
            $reference = $database->getReference('drivers');
            $snapshot  = $reference->getSnapshot();

            $firebaseDrivers = $snapshot->getValue() ?? [];
        } catch (\Throwable $e) {
            Log::error('Firebase connection failed', [
                'error' => $e->getMessage(),
            ]);
            $firebaseDrivers = [];
        }

        foreach ($pengirimanAktif as $driver_id => $grup) {
            $driver = $grup->first()->driver;

            if (!$driver) continue;

            $totalPengiriman = $grup->count();
            if ($totalPengiriman === 0) continue;

            $lat = null;
            $lng = null;

            $firebaseKey = (string) $driver_id;

            if (isset($firebaseDrivers[$firebaseKey]) && is_array($firebaseDrivers[$firebaseKey])) {
                $lokasi = $firebaseDrivers[$firebaseKey];
                if (isset($lokasi['latitude']) && isset($lokasi['longitude'])) {
                    $lat = (float) $lokasi['latitude'];
                    $lng = (float) $lokasi['longitude'];
                }
            }

            if (!$lat || !$lng) {
                if ($driver->latitude && $driver->longitude) {
                    $lat = (float) $driver->latitude;
                    $lng = (float) $driver->longitude;
                }
            }

            if ($lat && $lng && $lat != 0 && $lng != 0) {
                $markers[] = [
                    'id'     => (string) $driver_id,
                    'lat'    => $lat,
                    'lng'    => $lng,
                    'nama'   => $driver->nama_lengkap ?? 'Driver ' . $driver_id,
                    'jumlah' => $totalPengiriman,
                ];
            }
        }

        return view('admin.pemantauan.index', compact('pengirimanHariIni', 'markers'));
    }
}