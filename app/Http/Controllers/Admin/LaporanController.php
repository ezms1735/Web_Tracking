<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanBulananExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $pengiriman = Pengiriman::with(['driver', 'pesanan.pelanggan'])
            ->whereNotNull('waktu_selesai')
            ->whereMonth('waktu_selesai', $bulan)
            ->whereYear('waktu_selesai', $tahun)
            ->get();

        $laporanPerDriver = $pengiriman->groupBy('driver_id')->map(function ($group) use ($bulan, $tahun) {
            $driver = $group->first()->driver ?? null;
            $pelangganUnik = $group->pluck('pesanan.pelanggan_id')->unique()->count();
            
            return (object) [
                'driver'             => $driver,
                'nama'               => $driver ? $driver->nama_lengkap : 'Driver Tidak Diketahui',
                'total_pelanggan'    => $pelangganUnik,
                'total_pengiriman'   => $group->count(),
                'waktu_terakhir'     => $group->max('waktu_selesai') 
                                        ? $group->max('waktu_selesai')->translatedFormat('d/m/Y') 
                                        : '-',
            ];
        })->values();

        // Statistik ringkasan (opsional, jika masih mau ditampilkan)
        $totalPackBulanIni      = $pengiriman->sum(fn($p) => $p->pesanan->jumlah_pesanan ?? 0);
        $totalTransaksiBulanIni = $pengiriman->count();
        $totalDriverBulanIni    = $laporanPerDriver->count();

        $daftarTahun = range(2024, Carbon::now()->year);

        return view('admin.laporan.index', compact(
            'laporanPerDriver',
            'totalPackBulanIni',
            'totalTransaksiBulanIni',
            'totalDriverBulanIni',
            'bulan',
            'tahun',
            'daftarTahun'
        ));
    }

    // Route untuk download Excel
    public function downloadExcel(Request $request)
    {
        $bulan = $request->bulan ?? Carbon::now()->month;
        $tahun = $request->tahun ?? Carbon::now()->year;

        $namaFile = 'Laporan_Pengiriman_' . Carbon::create($tahun, $bulan)->format('F_Y') . '.xlsx';

        return Excel::download(new LaporanBulananExport($bulan, $tahun), $namaFile);
    }
}