<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Admin\PengirimanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('admin.login'));

/*
|--------------------------------------------------------------------------
| AUTH ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // LOGIN
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | ADMIN AREA (LOGIN + ROLE ADMIN)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'admin'])->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | DRIVER (CRUD)
        |--------------------------------------------------------------------------
        */
        Route::resource('driver', DriverController::class);
        Route::patch('driver/{driver}/nonaktif', [DriverController::class, 'nonaktif'])
            ->name('driver.nonaktif');

        /*
        |--------------------------------------------------------------------------
        | PELANGGAN (CRUD)
        |--------------------------------------------------------------------------
        */
        Route::resource('pelanggan', PelangganController::class);
        Route::patch('pelanggan/{pelanggan}/nonaktif', [PelangganController::class, 'nonaktif'])
            ->name('pelanggan.nonaktif');

        /*
        |--------------------------------------------------------------------------
        | PESANAN (CRUD)
        |--------------------------------------------------------------------------
        */
        Route::resource('pesanan', PesananController::class);

        /*
        |--------------------------------------------------------------------------
        | PENGIRIMAN (CRUD)
        |--------------------------------------------------------------------------
        */
        Route::resource('pengiriman', PengirimanController::class);

        /*
        |--------------------------------------------------------------------------
        | LAPORAN (VIEW)
        |--------------------------------------------------------------------------
        */
        Route::get('laporan', [LaporanController::class, 'index'])
            ->name('laporan.index');
    });
});
