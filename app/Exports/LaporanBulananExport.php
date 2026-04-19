<?php

namespace App\Exports;

use App\Models\Pengiriman;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanBulananExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected int $bulan;
    protected int $tahun;

    public function __construct(int $bulan, int $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $pengiriman = Pengiriman::with(['driver', 'pesanan.pelanggan'])
            ->whereNotNull('waktu_selesai')
            ->whereMonth('waktu_selesai', $this->bulan)
            ->whereYear('waktu_selesai', $this->tahun)
            ->get();

        return $pengiriman->map(function ($p, $index) {
            return [
                'no'             => $index + 1,
                'driver'         => $p->driver?->nama_lengkap ?? '-',
                'pelanggan'      => $p->pesanan?->pelanggan?->nama_lengkap ?? '-',
                'jumlah_pesanan' => $p->pesanan?->jumlah_pesanan ?? 0,
                'jumlah_terkirim'=> $p->jumlah_terkirim ?? 0,
                'waktu_selesai'  => $p->waktu_selesai
                    ? Carbon::parse($p->waktu_selesai)->translatedFormat('d F Y H:i')
                    : '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Driver',
            'Nama Pelanggan',
            'Jumlah Pesanan',
            'Jumlah Terkirim',
            'Waktu Selesai',
        ];
    }

    public function title(): string
    {
        return 'Laporan ' . Carbon::create($this->tahun, $this->bulan)->translatedFormat('F Y');
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Baris header (baris 1) bold dan background biru gelap
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1E3A5F'],
                ],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}