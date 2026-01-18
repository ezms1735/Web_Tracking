<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;

class AdminController extends Controller
{
    public function assignDriver(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|integer',
            'pesanan_id' => 'required|integer',
        ]);

        $pesanan = Pesanan::find($request->pesanan_id);

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        $pesanan->driver_id = $request->driver_id; // wajib
        $pesanan->status_pesanan = 'proses'; // opsional
        $pesanan->save();


        return response()->json([
            'success' => true,
            'message' => 'Driver berhasil ditugaskan ke pesanan'
        ]);
    }
}
