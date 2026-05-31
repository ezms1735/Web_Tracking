<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Services\NotifikasiService;

class AdminController extends Controller
{
    public function assignDriver(Request $request)
    {
        $request->validate([
            'driver_id'  => 'required|integer',
            'pesanan_id' => 'required|integer',
        ]);

        $pesanan = Pesanan::find($request->pesanan_id);

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        $pesanan->driver_id      = $request->driver_id;
        $pesanan->status_pesanan = 'proses';
        $pesanan->save();

        NotifikasiService::keDriver(
            (int) $request->driver_id,
            'Penugasan Pengiriman Baru',
            'Anda mendapat penugasan pengiriman baru. Segera cek daftar pesanan.',
            'penugasan_driver',
            $pesanan->id
        );

        NotifikasiService::kePelanggan(
            (int) $pesanan->pelanggan_id,
            'Pesanan Sedang Diproses',
            'Pesanan Anda sedang dalam proses pengiriman oleh driver.',
            'pesanan_proses',
            $pesanan->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Driver berhasil ditugaskan ke pesanan'
        ]);
    }
}