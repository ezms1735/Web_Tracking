<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Pengiriman;

class DriverPengirimanController extends Controller
{
    public function pesanan(Request $request)
    {
        $driver = $request->user();

        $pesanan = Pengiriman::with('pesanan') 
            ->where('driver_id', $driver->id)
            ->where('status_pengiriman', '!=', 'selesai') 
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

    public function showPesanan($id)
    {
        $driverId = auth()->id();

        $pengiriman = Pengiriman::with([
                'pesanan.pelanggan'
            ])
            ->where('driver_id', $driverId)
            ->where('pesanan_id', $id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'pesanan' => $pengiriman->pesanan,
        ]);
    }

    public function kirimBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_foto' => 'required|image',
            'jumlah_terkirim' => 'required|integer',
        ]);

        $pengiriman = Pengiriman::where('pesanan_id', $id)->where('driver_id', auth()->id())->first();

        if (!$pengiriman) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        if ($request->hasFile('bukti_foto')) {
            $path = $request->file('bukti_foto')->store('bukti', 'public');
            $pengiriman->bukti_foto = $path;
        }

        $pengiriman->jumlah_terkirim = $request->jumlah_terkirim;
        $pengiriman->waktu_selesai = now();
        $pengiriman->status_pengiriman = 'selesai';
        $pengiriman->save();

        $pesanan = Pesanan::find($id);
        if ($pesanan) {
            $pesanan->status_pesanan = 'selesai';
            $pesanan->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Bukti berhasil dikirim'
        ]);
    }

    public function riwayat(Request $request)
    {
        $driver = $request->user();

        $pengiriman = Pengiriman::with('pesanan.pelanggan')
            ->where('driver_id', $driver->id)
            ->where('status_pengiriman', 'selesai')
            ->latest('waktu_selesai')
            ->get();

        return response()->json([
            'success' => true,
            'pesanan' => $pengiriman->map(function ($p) {
                return [
                    'id' => $p->pesanan->id,
                    'jumlah_pesanan' => $p->pesanan->jumlah_pesanan,
                    'jumlah_terkirim' => $p->jumlah_terkirim,
                    'bukti_foto' => $p->bukti_foto,
                    'waktu_selesai' => $p->waktu_selesai, // ✅ tambah ini untuk grouping
                    'pelanggan' => $p->pesanan->pelanggan, // ✅ tambah ini untuk nama, telp, alamat
                ];
            }),
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
        }

        return response()->json([
            'success' => true,
            'message' => 'Lokasi driver diperbarui',
        ]);
    }
}
