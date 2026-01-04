<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pengguna;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPelanggan = Pengguna::where('peran', 'pelanggan')->count();
        $totalDriver    = Pengguna::where('peran', 'driver')->count();

        $pesananBelumDiproses = Pesanan::where('status_pesanan', 'menunggu')->count();
        $pesananDiproses      = Pesanan::where('status_pesanan', 'proses')->count();

        $pesananBaru = Pesanan::with('pelanggan')
            ->where('status_pesanan', 'menunggu')
            ->latest()
            ->get();

        $pesananProses = Pesanan::with(['pelanggan', 'pengiriman.driver'])
            ->where('status_pesanan', 'proses')
            ->latest()
            ->get();

        return view('admin.dashboard', compact(
            'totalPelanggan',
            'totalDriver',
            'pesananBelumDiproses',
            'pesananDiproses',
            'pesananBaru',
            'pesananProses'
        ));
    }
}
