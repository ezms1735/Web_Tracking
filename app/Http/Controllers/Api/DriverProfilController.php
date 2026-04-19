<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverProfilController extends Controller
{
    public function show()
    {
        $driver = Auth::user(); // asumsi model User atau Driver

        return response()->json([
            'success' => true,
            'data' => [
                'id'            => $driver->id,
                'nama_lengkap'  => $driver->nama_lengkap ?? $driver->name,
                'email'         => $driver->email,
                'telepon'       => $driver->telepon ?? $driver->phone,
                'alamat'        => $driver->alamat ?? '',
            ]
        ]);
    }

    public function update(Request $request)
    {
        $driver = Auth::user();

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $driver->id,
            'nomor_telepon'      => 'nullable|string|max:20',
            'alamat'       => 'nullable|string',
        ]);

        $driver->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data'    => $validated
        ]);
    }
}