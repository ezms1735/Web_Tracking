<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Pengiriman;

class PelangganController extends Controller
{ 
        public function storePesanan(Request $request)
        {
            $request->validate([
                'jumlah_pesanan' => 'required|integer|min:1',
            ]);

            $pesanan = \App\Models\Pesanan::create([
                'pelanggan_id' => auth()->id(),  
                'jumlah_pesanan' => $request->jumlah_pesanan,
                'status_pesanan' => 'menunggu',
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'data' => $pesanan
            ], 201);
        }

    public function getPesananSaya(Request $request)
    {
        $pelanggan = $request->user();

        $pesanan = Pesanan::with(['pengiriman.driver'])
            ->where('pelanggan_id', $pelanggan->id)
            ->where('status_pesanan', '!=', 'selesai') 
            ->latest()
            ->get()
            ->map(function ($p) {
                return [
                    'id'             => $p->id,
                    'jumlah_pesanan' => $p->jumlah_pesanan,
                    'status_pesanan' => $p->status_pesanan,
                    'nama_driver'    => $p->pengiriman?->driver?->nama_lengkap,
                    'driver_id'      => $p->pengiriman?->driver?->id, // <-- TAMBAHAN
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $pesanan,
        ]);
    }

    public function riwayat(Request $request)
    {
        $pelanggan = $request->user();

        $data = Pesanan::with(['pengiriman.driver'])
            ->where('pelanggan_id', $pelanggan->id)
            ->where('status_pesanan', 'selesai')
            ->latest()
            ->get()
            ->map(function ($p) {
                return [
                    'id'              => $p->id,
                    'jumlah_pesanan'  => $p->jumlah_pesanan,
                    'jumlah_terkirim' => $p->pengiriman?->jumlah_terkirim,
                    'waktu_selesai'   => $p->pengiriman?->waktu_selesai,
                    'nama_driver'     => $p->pengiriman?->driver?->nama_lengkap,
                    'nomor_telepon_driver' => $p->pengiriman?->driver?->nomor_telepon,
                    'bukti_foto'      => $p->pengiriman?->bukti_foto ? asset('storage/' . $p->pengiriman->bukti_foto) : null,
                ];
            });

        return response()->json([
            'success' => true,
            'riwayat' => $data,
        ]);
    }
}