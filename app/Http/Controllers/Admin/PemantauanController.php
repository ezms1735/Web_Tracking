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

        // Ambil semua pengiriman yang punya driver (untuk markers)
        $pengirimanAktif = Pengiriman::with(['driver', 'pesanan.pelanggan'])
            ->whereNotNull('driver_id')
            ->latest()
            ->get()
            ->groupBy('driver_id');

        // Untuk tampilan list: hanya pengiriman hari ini
        $pengirimanHariIni = Pengiriman::with(['driver', 'pesanan.pelanggan'])
            ->whereNotNull('driver_id')
            ->whereDate('waktu_mulai', $today)
            ->latest()
            ->get()
            ->groupBy('driver_id');

        $markers = [];

        // Ambil data lokasi dari Firebase 
        try {
            $database = app('firebase.database');
            $reference = $database->getReference('drivers');
            $snapshot = $reference->getSnapshot();
            
            $firebaseDrivers = $snapshot->getValue() ?? [];
            
            Log::info('Firebase drivers node raw', [
                'count'  => count($firebaseDrivers),
                'keys'   => array_keys($firebaseDrivers),
                'sample' => array_slice($firebaseDrivers, true)
            ]);
        } catch (\Throwable $e) {
            Log::error('Firebase connection failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $firebaseDrivers = [];
        }

        foreach ($pengirimanAktif as $driver_id => $grup) {
            $driver = $grup->first()->driver;
            
            if (!$driver) continue;

            $totalPengiriman = $grup->count();
            if ($totalPengiriman === 0) continue;

            Log::debug("Memproses driver_id: {$driver_id} - {$driver->nama_lengkap}");

            $lat = null;
            $lng = null;

            $firebaseKey = (string) $driver_id; 

            if (isset($firebaseDrivers[$firebaseKey]) && is_array($firebaseDrivers[$firebaseKey])) {
                $lokasi = $firebaseDrivers[$firebaseKey];
                
                if (isset($lokasi['latitude']) && isset($lokasi['longitude'])) {
                    $lat = (float) $lokasi['latitude'];
                    $lng = (float) $lokasi['longitude'];
                    Log::info("Lokasi DITEMUKAN di Firebase untuk driver_id {$driver_id}", [
                        'lat' => $lat,
                        'lng' => $lng,
                        'updated_at' => $lokasi['updated_at'] ?? 'tidak ada'
                    ]);
                } else {
                    Log::warning("Data Firebase ada tapi latitude/longitude tidak lengkap untuk driver_id {$driver_id}", $lokasi);
                }
            } else {
                Log::warning("Tidak ada entri di Firebase drivers untuk key '{$firebaseKey}'. Driver ID tersedia: " . implode(', ', array_keys($firebaseDrivers)));
            }

            if (!$lat || !$lng) {
                if ($driver->latitude && $driver->longitude) {
                    $lat = (float) $driver->latitude;
                    $lng = (float) $driver->longitude;
                    Log::info("Menggunakan lokasi fallback dari tabel drivers untuk ID {$driver_id}", [
                        'lat' => $lat,
                        'lng' => $lng
                    ]);
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

        Log::info('Markers yang dikirim ke view', [
            'jumlah' => count($markers),
            'detail' => $markers
        ]);

        return view('admin.pemantauan.index', compact('pengirimanHariIni', 'markers'));
    }
}