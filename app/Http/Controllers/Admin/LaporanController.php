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
        // Ambil input bulan & tahun, default bulan ini
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        // Query pengiriman selesai di bulan & tahun terpilih
        $pengiriman = Pengiriman::with(['driver', 'pesanan.pelanggan'])
            ->whereNotNull('waktu_selesai')
            ->whereMonth('waktu_selesai', $bulan)
            ->whereYear('waktu_selesai', $tahun)
            ->orderBy('waktu_selesai')
            ->get();

        // Kelompokkan per hari (format tanggal Indonesia: 08 Januari 2026)
        $laporan = $pengiriman->groupBy(function ($item) {
            return $item->waktu_selesai->translatedFormat('d F Y');
        });

        // Hitung statistik bulanan
        $totalPackBulanIni = $pengiriman->sum(fn($p) => $p->pesanan->jumlah_pesanan ?? 0);
        $totalTransaksiBulanIni = $pengiriman->count();
        $totalDriverBulanIni = $pengiriman->pluck('driver_id')->unique()->count();

        // List tahun untuk dropdown (2024 sampai tahun sekarang)
        $daftarTahun = range(2024, Carbon::now()->year);

        return view('admin.laporan.index', compact(
            'laporan',                    // ← sudah sesuai nama variabel di Blade
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