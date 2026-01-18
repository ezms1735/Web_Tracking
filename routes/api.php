<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DriverPengirimanController;
use App\Http\Controllers\Api\AdminController;


// 🔓 TANPA LOGIN
Route::post('/login', [AuthController::class, 'login']); // POST login untuk mobile

// 🔐 DENGAN LOGIN (SANCTUM)
Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Ambil data user
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'user' => $request->user(),
        ]);
    });

    Route::post('/admin/assign-driver', [AdminController::class, 'assignDriver']);


    // 🚚 Lokasi driver
    Route::post('/driver/lokasi', [DriverPengirimanController::class, 'updateLokasi']);

    // 📦 Pesanan driver
    Route::get('/driver/pesanan', [DriverPengirimanController::class, 'pesanan']);
});
