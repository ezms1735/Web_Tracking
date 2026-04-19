<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DriverPengirimanController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\DriverProfilController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\PelangganController;
use App\Http\Controllers\Api\PelangganProfilController;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'user' => $request->user(),
        ]);
    });

    Route::prefix('driver')->group(function () {

        Route::get('/profil', [DriverProfilController::class, 'show']);
        Route::put('/profil', [DriverProfilController::class, 'update']);
        Route::post('/lokasi', [DriverPengirimanController::class, 'updateLokasi']);
        Route::get('/pesanan', [DriverPengirimanController::class, 'pesanan']);
        Route::get('/pesanan/{id}', [DriverPengirimanController::class, 'showPesanan']);
        // Route::get('/pesanan/{id}/mulai', [DriverPengirimanController::class, 'mulaiPengiriman']);
        Route::post('/pesanan/{id}/selesai', [DriverPengirimanController::class, 'selesaiPengiriman']);
        Route::post('/pesanan/{id}/bukti', [DriverPengirimanController::class, 'kirimBukti']);
        Route::get('/riwayat', [DriverPengirimanController::class, 'riwayat']);
        // Route::post('/save-driver-token', [DriverController::class, 'saveToken']);
  
    });

   Route::prefix('pelanggan')->group(function () {
        Route::post('/pesanan', [PelangganController::class, 'storePesanan']);  
        Route::get('/pesanan', [PelangganController::class, 'getPesananSaya']);
        Route::get('/profil', [PelangganProfilController::class, 'show']);
        Route::put('/profil', [PelangganProfilController::class, 'update']);
        Route::get('/riwayat', [PelangganController::class, 'riwayat']);    });

    Route::prefix('admin')->group(function () {
        Route::post('/assign-driver', [AdminController::class, 'assignDriver']);
        
        Route::get('/pesanan', [AdminController::class, 'pesananAll']);
        Route::get('/pesanan/{id}', [AdminController::class, 'showPesanan']);
    });
});