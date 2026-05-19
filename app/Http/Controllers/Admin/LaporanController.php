<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $query = Pengiriman::with(['driver', 'pesanan.pelanggan'])
            ->whereNotNull('waktu_selesai');

        if ($bulan) {
            $query->whereMonth('waktu_selesai', $bulan);
        }

        if ($tahun) {
            $query->whereYear('waktu_selesai', $tahun);
        }

        $pengiriman = $query->get();

        $laporanPerDriver = $pengiriman->groupBy(function ($item) {
            return $item->driver_id . '-' . Carbon::parse($item->waktu_selesai)->format('Y-m-d');
            })->map(function ($group) {

                $driver = $group->first()->driver ?? null;

                return (object) [
                    'driver_id'         => $driver->id ?? null,
                    'nama'              => $driver->nama_lengkap ?? 'Driver Tidak Diketahui',
                    'tanggal'           => Carbon::parse($group->first()->waktu_selesai)->format('Y-m-d'),
                    'total_pelanggan'   => $group->pluck('pesanan.pelanggan_id')->unique()->count(),
                    'total_pengiriman'  => $group->count(),
                    'waktu_terakhir'    => $group->max('waktu_selesai'),
                ];
            })
            ->sortByDesc('tanggal')
            ->values();

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
        $tanggal = $request->tanggal;

        $pengiriman = Pengiriman::with(['driver', 'pesanan.pelanggan'])
            ->where('driver_id', $driverId)
            ->whereDate('waktu_selesai', $tanggal) 
            ->get();

        return view('admin.laporan.detail', compact('pengiriman', 'tanggal'));
    }

    public function downloadExcel(Request $request)
    {
        $driverId = $request->driver_id;
        $tanggal  = $request->tanggal;
        $bulan    = $request->bulan;
        $tahun    = $request->tahun;

        $query = Pengiriman::with(['driver', 'pesanan.pelanggan'])
            ->whereNotNull('waktu_selesai');

        // Download per baris (dari tombol Unduh di tabel)
        if ($driverId && $tanggal) {
            $query->where('driver_id', $driverId)
                ->whereDate('waktu_selesai', $tanggal);
        } else {
            // Download semua (dari tombol di form filter)
            if ($bulan) $query->whereMonth('waktu_selesai', $bulan);
            if ($tahun) $query->whereYear('waktu_selesai', $tahun);
        }

        $data = $query->get()->map(function ($item) {
            return [
                'Tanggal'               => $item->waktu_selesai
                                            ? Carbon::parse($item->waktu_selesai)->format('d/m/Y')
                                            : '-',
                'Nama Driver'           => $item->driver->nama_lengkap ?? '-',
                'No. Telepon Driver'    => $item->driver->nomor_telepon ?? '-',
                'Nama Pelanggan'        => $item->pesanan?->pelanggan?->nama_lengkap ?? '-',
                'No. Telepon Pelanggan' => $item->pesanan?->pelanggan?->nomor_telepon ?? '-',
                'Alamat Pelanggan'      => $item->pesanan?->pelanggan?->alamat ?? '-',
                'Jumlah Pesanan'        => $item->pesanan?->jumlah_pesanan ?? '-',
                'Jumlah Terkirim'       => $item->jumlah_terkirim ?? '-',
                'Bukti Foto (URL)'      => $item->bukti_foto
                                            ? asset('storage/' . $item->bukti_foto)
                                            : '-',
                'Waktu Mulai'           => $item->waktu_mulai
                                            ? Carbon::parse($item->waktu_mulai)->format('d/m/Y H:i')
                                            : '-',
                'Waktu Selesai'         => $item->waktu_selesai
                                            ? Carbon::parse($item->waktu_selesai)->format('d/m/Y H:i')
                                            : '-',
            ];
        });

        // Buat nama file dinamis
        if ($driverId && $tanggal) {
            $namaDriver = $data->first()['Nama Driver'] ?? 'Driver';
            $namaFile = 'Laporan_' . str_replace(' ', '_', $namaDriver) . '_' . $tanggal . '.xlsx';
        } elseif ($bulan && $tahun) {
            $namaFile = 'Laporan_' . Carbon::create($tahun, $bulan)->format('F_Y') . '.xlsx';
        } else {
            $namaFile = 'Laporan_Semua_Pengiriman.xlsx';
        }

        return (new FastExcel($data))->download($namaFile);
    }
}