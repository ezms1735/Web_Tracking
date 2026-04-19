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
            'pelanggan_id' => auth()->id(),  // atau user_id tergantung model
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
        $pesanan = Pesanan::where('pelanggan_id', $request->user()->id)
            ->with('pengiriman.driver:id,nama_lengkap')
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'jumlah_pesanan' => $item->jumlah_pesanan,
                    'nama_driver' => $item->pengiriman?->driver?->nama_lengkap,
                    'status_pesanan' => $item->status_pesanan,
                    'created_at' => $item->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $pesanan,
        ]);
    }
}