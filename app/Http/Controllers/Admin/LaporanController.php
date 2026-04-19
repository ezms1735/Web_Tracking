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

        $laporanPerDriver = $pengiriman->groupBy('driver_id')->map(function ($group) {

            $driver = $group->first()->driver ?? null;

            return (object) [
                'driver_id'         => $driver->id ?? null,
                'nama'              => $driver->nama_lengkap ?? 'Driver Tidak Diketahui',
                'total_pelanggan'   => $group->pluck('pesanan.pelanggan_id')->unique()->count(),
                'total_pengiriman'  => $group->count(),
                'waktu_terakhir'    => $group->max('waktu_selesai'), 
            ];
        })->values();

        $daftarTahun = range(2024, Carbon::now()->year);

        return view('admin.laporan.index', compact(
            'laporanPerDriver',
            'bulan',
            'tahun',
            'daftarTahun'
        ));
    }

    public function detail($driverId, Request $request)
    {
        $bulan = $request->bulan ?? Carbon::now()->month;
        $tahun = $request->tahun ?? Carbon::now()->year;

        $pengiriman = Pengiriman::with(['driver', 'pesanan.pelanggan'])
            ->where('driver_id', $driverId)
            ->whereNotNull('waktu_selesai')
            ->whereMonth('waktu_selesai', $bulan)
            ->whereYear('waktu_selesai', $tahun)
            ->get();

        return view('admin.laporan.detail', compact('pengiriman', 'bulan', 'tahun'));
    }

    public function downloadExcel(Request $request)
    {
        $bulan = $request->bulan ?? Carbon::now()->month;
        $tahun = $request->tahun ?? Carbon::now()->year;

        $namaFile = 'Laporan_Pengiriman_' . Carbon::create($tahun, $bulan)->format('F_Y') . '.xlsx';

        return Excel::download(new LaporanBulananExport($bulan, $tahun), $namaFile);
    }
}