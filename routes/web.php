<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Admin\PengirimanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PemantauanController;
use App\Http\Controllers\Api\DriverPengirimanController;

/*
|--------------------------------------------------------------------------
| DEFAULT LOGIN (WAJIB UNTUK auth MIDDLEWARE)
|--------------------------------------------------------------------------
*/
Route::get('/login', fn () => redirect()->route('admin.login'))
    ->name('login');

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('admin.login'));

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // === AUTH ADMIN (TANPA middleware auth) ===
    Route::get('/login', [LoginController::class, 'index'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.post');

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    // === WAJIB LOGIN + ROLE ADMIN ===
    Route::middleware(['auth', 'admin'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/pemantauan', [PemantauanController::class, 'index'])
            ->name('pemantauan');

        // Driver
        Route::resource('driver', DriverController::class);
        Route::patch('driver/{driver}/nonaktif', [DriverController::class, 'nonaktif'])
            ->name('driver.nonaktif');

        // Pelanggan
        Route::resource('pelanggan', PelangganController::class);
        Route::patch('pelanggan/{pelanggan}/nonaktif', [PelangganController::class, 'nonaktif'])
            ->name('pelanggan.nonaktif');

        // Pesanan
        Route::resource('pesanan', PesananController::class);

        // Pengiriman
        Route::resource('pengiriman', PengirimanController::class);

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])
            ->name('laporan.index');

        Route::get('/laporan/download', [LaporanController::class, 'downloadExcel'])
            ->name('laporan.download');
    });


    Route::middleware('auth:sanctum')->group(function () {
    Route::get('/driver/pengiriman', [DriverPengirimanController::class, 'index']);
});
});
