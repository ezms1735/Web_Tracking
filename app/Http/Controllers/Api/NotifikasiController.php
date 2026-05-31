<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $penggunaId = $request->user()->id;

        $belumDibaca = Notifikasi::where('pengguna_id', $penggunaId)
            ->where('dibaca', false)
            ->count();

        $notifikasi = Notifikasi::where('pengguna_id', $penggunaId)
            ->latest()
            ->get();

        return response()->json([
            'success'      => true,
            'notifikasi'   => $notifikasi,
            'belum_dibaca' => $belumDibaca,
        ]);
    }

    public function tandaiDibaca(Request $request, $id)
    {
        $notifikasi = Notifikasi::where('id', $id)
            ->where('pengguna_id', $request->user()->id)
            ->firstOrFail();

        $notifikasi->update(['dibaca' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sudah dibaca',
        ]);
    }

    public function tandaiSemuaDibaca(Request $request)
    {
        Notifikasi::where('pengguna_id', $request->user()->id)
            ->where('dibaca', false)
            ->update(['dibaca' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi telah ditandai dibaca',
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $notifikasi = Notifikasi::where('id', $id)
            ->where('pengguna_id', $request->user()->id)
            ->firstOrFail();

        $notifikasi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi dihapus',
        ]);
    }

    public function simpanToken(Request $request)
    {
        $request->validate([
            'expo_token' => 'required|string',
        ]);

        $request->user()->update([
            'expo_token' => $request->expo_token,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Token berhasil disimpan',
        ]);
    }
}