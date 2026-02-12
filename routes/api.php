<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DriverPengirimanController;
use App\Http\Controllers\Api\AdminController;

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

        Route::post('/lokasi', [DriverPengirimanController::class, 'updateLokasi']);

        Route::get('/pesanan', [DriverPengirimanController::class, 'pesanan']);

        Route::get('/pesanan/{id}', [DriverPengirimanController::class, 'showPesanan']);
        Route::get('/pesanan/{id}/mulai', [DriverPengirimanController::class, 'mulaiPengiriman']);
        Route::get('/pesanan/{id}/selesai', [DriverPengirimanController::class, 'selesaiPengiriman']);
        Route::get('/pesanan/{id}/bukti', [DriverPengirimanController::class, 'buktiPengiriman']);
  
    });

    Route::prefix('admin')->group(function () {
        Route::post('/assign-driver', [AdminController::class, 'assignDriver']);
        
        Route::get('/pesanan', [AdminController::class, 'pesananAll']);
        Route::get('/pesanan/{id}', [AdminController::class, 'showPesanan']);
    });
});