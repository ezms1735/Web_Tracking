<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganProfilController extends Controller
{
    public function show()
    {
        $pelanggan = Auth::user(); 

        return response()->json([
            'success' => true,
            'data' => [
                'id'            => $pelanggan->id,
                'nama_lengkap'  => $pelanggan->nama_lengkap ?? $pelanggan->name,
                'email'         => $pelanggan->email,
                'telepon'       => $pelanggan->nomor_telepon,
                'alamat'        => $pelanggan->alamat ?? '',
            ]
        ]);
    }

    public function update(Request $request)
    {
        $pelanggan = Auth::user();

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $pelanggan->id,
            'nomor_telepon'      => 'nullable|string|max:20',
            'alamat'       => 'nullable|string',
        ]);

        $pelanggan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data'    => $validated
        ]);
    }
}