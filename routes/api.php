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
use App\Http\Controllers\Api\NotifikasiController;
use App\Http\Controllers\Api\PasswordResetController;

// ==========================================
// AUTHENTICATION & PASSWORD RESET
// ==========================================
Route::post('/login', [AuthController::class, 'login']);

Route::post('/forgot-password', [PasswordResetController::class, 'sendResetToken']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'user' => $request->user(),
        ]);
    });

    // ==========================================
    // DRIVER ROUTES
    // ==========================================
    Route::prefix('driver')->group(function () {
        Route::get('/profil', [DriverProfilController::class, 'show']);
        Route::put('/profil', [DriverProfilController::class, 'update']);
        Route::post('/lokasi', [DriverPengirimanController::class, 'updateLokasi']);
        Route::get('/pesanan', [DriverPengirimanController::class, 'pesanan']);
        Route::get('/pesanan/{id}', [DriverPengirimanController::class, 'showPesanan']);
        Route::post('/pesanan/{id}/selesai', [DriverPengirimanController::class, 'selesaiPengiriman']);
        Route::post('/pesanan/{id}/bukti', [DriverPengirimanController::class, 'kirimBukti']);
        Route::get('/riwayat', [DriverPengirimanController::class, 'riwayat']);
    });

    // ==========================================
    // PELANGGAN ROUTES
    // ==========================================
    Route::prefix('pelanggan')->group(function () {
        Route::post('/pesanan', [PelangganController::class, 'storePesanan']);  
        Route::get('/pesanan', [PelangganController::class, 'getPesananSaya']);
        Route::get('/profil', [PelangganProfilController::class, 'show']);
        Route::put('/profil', [PelangganProfilController::class, 'update']);
        Route::get('/riwayat', [PelangganController::class, 'riwayat']);
    });        

    // ==========================================
    // ADMIN ROUTES
    // ==========================================
    Route::prefix('admin')->group(function () {
        Route::post('/assign-driver', [AdminController::class, 'assignDriver']);
        Route::get('/pesanan', [AdminController::class, 'pesananAll']);
        Route::get('/pesanan/{id}', [AdminController::class, 'showPesanan']);
    });

    // ==========================================
    // NOTIFIKASI (GLOBAL UNTUK SEMUA ROLE)
    // ==========================================
    Route::prefix('notifikasi')->group(function () {
        Route::post('/token', [NotifikasiController::class, 'simpanToken']);
        Route::get('/', [NotifikasiController::class, 'index']);
        Route::patch('/baca-semua', [NotifikasiController::class, 'tandaiSemuaDibaca']);
        Route::patch('/{id}/baca', [NotifikasiController::class, 'tandaiDibaca']);
        Route::delete('/{id}', [NotifikasiController::class, 'destroy']);
    });

});