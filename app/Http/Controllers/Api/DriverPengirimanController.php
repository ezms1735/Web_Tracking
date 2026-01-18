<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Pengiriman;

class DriverPengirimanController extends Controller
{
    // Ambil pesanan driver yang ditugaskan
    public function pesanan(Request $request)
    {
        $driver = $request->user();

        $pesanan = Pengiriman::with('pesanan') // join pengiriman + pesanan
            ->where('driver_id', $driver->id)
            ->get()
            ->map(function($p) {
                return [
                    'id' => $p->pesanan->id,
                    'jumlah_pesanan' => $p->pesanan->jumlah_pesanan,
                    'status' => $p->pesanan->status_pesanan,
                    'pelanggan' => $p->pesanan->pelanggan,
                ];
            });

        return response()->json([
            'success' => true,
            'pesanan' => $pesanan,
        ]);
    }


    // Update lokasi driver
    public function updateLokasi(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $driver = $request->user();
        
        // Update di database lokal
        $driver->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        // Update di Firebase Realtime Database
        try {
            $database = app('firebase.database');
            $database
                ->getReference('driver_locations/' . $driver->id)
                ->set([
                    'latitude' => (float) $request->latitude,
                    'longitude' => (float) $request->longitude,
                    'updated_at' => now()->toIso8601String(),
                ]);
        } catch (\Throwable $e) {
            \Log::error('Firebase update lokasi error: ' . $e->getMessage());
            // Jangan return error, lokasi DB sudah tersimpan
        }

        return response()->json([
            'success' => true,
            'message' => 'Lokasi driver diperbarui',
        ]);
    }
}
