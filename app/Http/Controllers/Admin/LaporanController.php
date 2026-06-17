<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Carbon\Carbon;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

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
                    'total_pengiriman'  => $group->sum(fn($item) => $item->pesanan->jumlah_pesanan ?? 0),
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

        if ($driverId && $tanggal) {
            $query->where('driver_id', $driverId)
                ->whereDate('waktu_selesai', $tanggal);
        } else {
            if ($bulan) $query->whereMonth('waktu_selesai', $bulan);
            if ($tahun) $query->whereYear('waktu_selesai', $tahun);
        }

        $pengiriman = $query->get();

        // Tentukan nama file
        if ($driverId && $tanggal) {
            $namaDriver = $pengiriman->first()?->driver?->nama_lengkap ?? 'Driver';
            $namaFile = 'Laporan_' . str_replace(' ', '_', $namaDriver) . '_' . $tanggal . '.xlsx';
        } elseif ($bulan && $tahun) {
            $namaFile = 'Laporan_' . Carbon::create($tahun, $bulan)->format('F_Y') . '.xlsx';
        } else {
            $namaFile = 'Laporan_Semua_Pengiriman.xlsx';
        }

        // ============== BUAT SPREADSHEET ==============
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan');

        // Header kolom
        $headers = [
            'A' => 'Tanggal',
            'B' => 'Nama Driver',
            'C' => 'No. Telepon Driver',
            'D' => 'Nama Pelanggan',
            'E' => 'No. Telepon Pelanggan',
            'F' => 'Alamat Pelanggan',
            'G' => 'Jumlah Pesanan',
            'H' => 'Jumlah Terkirim',
            'I' => 'Bukti Foto',
            'J' => 'Waktu Mulai',
            'K' => 'Waktu Selesai',
        ];

        foreach ($headers as $col => $label) {
            $sheet->setCellValue($col . '1', $label);
        }

        // Style header
        $headerStyle = [
            'font' => ['bold' => true, 'size' => 12],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '92D050'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ];
        $sheet->getStyle('A1:K1')->applyFromArray($headerStyle);

        // Lebar kolom
        $sheet->getColumnDimension('A')->setWidth(14);
        $sheet->getColumnDimension('B')->setWidth(22);
        $sheet->getColumnDimension('C')->setWidth(18);
        $sheet->getColumnDimension('D')->setWidth(22);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(14);
        $sheet->getColumnDimension('H')->setWidth(14);
        $sheet->getColumnDimension('I')->setWidth(18);
        $sheet->getColumnDimension('J')->setWidth(18);
        $sheet->getColumnDimension('K')->setWidth(18);

        $rowsStyle = [
            'font' => ['size' => 11],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        $row = 2;

        foreach ($pengiriman as $item) {
            $sheet->setCellValue('A' . $row, $item->waktu_selesai
                ? Carbon::parse($item->waktu_selesai)->format('d/m/Y')
                : '-');
            $sheet->setCellValue('B' . $row, $item->driver->nama_lengkap ?? '-');
            $sheet->setCellValue('C' . $row, $item->driver->nomor_telepon ?? '-');
            $sheet->setCellValue('D' . $row, $item->pesanan?->pelanggan?->nama_lengkap ?? '-');
            $sheet->setCellValue('E' . $row, $item->pesanan?->pelanggan?->nomor_telepon ?? '-');
            $sheet->setCellValue('F' . $row, $item->pesanan?->pelanggan?->alamat ?? '-');
            $sheet->setCellValue('G' . $row, $item->pesanan?->jumlah_pesanan ?? '-');
            $sheet->setCellValue('H' . $row, $item->jumlah_terkirim ?? '-');
            $sheet->setCellValue('J' . $row, $item->waktu_mulai
                ? Carbon::parse($item->waktu_mulai)->format('d/m/Y H:i')
                : '-');
            $sheet->setCellValue('K' . $row, $item->waktu_selesai
                ? Carbon::parse($item->waktu_selesai)->format('d/m/Y H:i')
                : '-');

            // Tinggi baris agar muat gambar
            $sheet->getRowDimension($row)->setRowHeight(80);

            // Embed gambar bukti foto
            if ($item->bukti_foto) {
                $path = storage_path('app/public/' . $item->bukti_foto);

                if (file_exists($path)) {
                    $drawing = new Drawing();
                    $drawing->setName('Bukti Foto');
                    $drawing->setPath($path);
                    $drawing->setHeight(100);
                    $drawing->setCoordinates('I' . $row);
                    $drawing->setOffsetX(5);
                    $drawing->setOffsetY(5);
                    $drawing->setWorksheet($sheet);
                } else {
                    $sheet->setCellValue('I' . $row, 'Foto tidak ditemukan');
                }
            } else {
                $sheet->setCellValue('I' . $row, '-');
            }

            $row++;
        }

        // Apply border ke semua data
        $lastRow = $row - 1;
        if ($lastRow >= 2) {
            $sheet->getStyle('A2:K' . $lastRow)->applyFromArray($rowsStyle);
        }

        // ============== OUTPUT ==============
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $namaFile, [
            'Content-Type'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}