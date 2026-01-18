<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Pengguna;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // ✅ Validasi
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // ✅ Cari user (username / email / no telp)
        $user = Pengguna::where('nama_lengkap', $request->username)
            ->orWhere('email', $request->username)
            ->orWhere('nomor_telepon', $request->username)
            ->first();

        // ❌ Jika user tidak ada / password salah
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah',
            ], 401);
        }

        // ❌ Jika akun nonaktif
        if ($user->status !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak aktif',
            ], 403);
        }

        // 🔑 Buat token Sanctum
        $token = $user->createToken('moya-kristal-mobile')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'nama_lengkap' => $user->nama_lengkap,
                'email' => $user->email,
                'nomor_telepon' => $user->nomor_telepon,
                'peran' => $user->peran, // driver / pelanggan
                'status' => $user->status,
            ],
            'message' => 'Login berhasil',
        ]);
    }

    public function logout(Request $request)
    {
        // ✅ Cegah error jika token null
        if ($request->user() && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }
}
