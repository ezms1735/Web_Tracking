<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengiriman;
use App\Models\Pesanan;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\NotifikasiService;

class PengirimanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengiriman::with([
            'pesanan.pelanggan',
            'driver'
        ])->latest();

        // Filter Tanggal
        if ($request->filled('tanggal') && is_numeric($request->tanggal)) {
            $query->whereDay('created_at', $request->tanggal);
        }

        // Filter Bulan
        if ($request->filled('bulan') && is_numeric($request->bulan)) {
            $query->whereMonth('created_at', $request->bulan);
        }

        // Filter Tahun
        if ($request->filled('tahun') && is_numeric($request->tahun)) {
            $query->whereYear('created_at', $request->tahun);
        }

        $pengiriman = $query->paginate(10);

        // Daftar Tahun untuk Filter
        $daftarTahun = Pengiriman::select(DB::raw('YEAR(created_at) as tahun'))
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        if (empty($daftarTahun)) {
            $daftarTahun = [now()->year];
        }

        // Ambil data untuk Modal Tambah Pengiriman
        $pesanan = Pesanan::where('status_pesanan', 'menunggu')->get();
        $driver = Pengguna::where('peran', 'driver')
            ->where('status', 'aktif')
            ->get();

        return view('admin.pengiriman.index', compact('pengiriman', 'daftarTahun', 'pesanan', 'driver'));
    }

    public function create()
    {
        return redirect()->route('admin.pengiriman.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pesanan_id'       => 'required|exists:pesanan,id',
            'driver_id'        => 'required|exists:pengguna,id',
            'jumlah_terkirim'  => 'nullable|integer|min:0',
            'bukti_foto'       => 'nullable|image|max:2048', 
        ]);

        $data = [
            'pesanan_id'        => $request->pesanan_id,
            'driver_id'         => $request->driver_id,
            'status_pengiriman' => 'proses',
            'waktu_mulai'       => now(),
            'waktu_selesai'     => null,
            'jumlah_terkirim'   => $request->jumlah_terkirim ?? 0,
        ];

        if ($request->hasFile('bukti_foto')) {
            $data['bukti_foto'] = $request->file('bukti_foto')
                ->store('bukti_pengiriman', 'public');
        }

        $pengiriman = Pengiriman::create($data);

        Pesanan::where('id', $request->pesanan_id)
            ->update(['status_pesanan' => 'proses']);

        $pesanan = Pesanan::with(['pelanggan', 'driver'])->find($request->pesanan_id);

        $jumlahPengiriman = Pengiriman::where('driver_id', $request->driver_id)
            ->where('status_pengiriman', 'proses')
            ->count();

        NotifikasiService::keDriver(
            (int) $request->driver_id, 
            'Penugasan Pengiriman Baru', 
            "Anda memiliki pengiriman {$pesanan->jumlah_pesanan} pack ke {$pesanan->pelanggan->alamat}.", 
            'pengiriman', 
            $pengiriman->id 
        );

        NotifikasiService::kePelanggan(
            (int) $pesanan->pelanggan_id, 
            'Pesanan Sedang Diproses', 
            "Pesanan Anda sedang dalam proses pengiriman oleh driver {$pesanan->driver->nama}.", 
            'pesanan', 
            $pesanan->id 
        );

        return redirect()->route('admin.pengiriman.index')
            ->with('success', 'Pengiriman berhasil dibuat');
    }

    public function show(Pengiriman $pengiriman)
    {
        return redirect()->route('admin.pengiriman.index');
    }

    public function edit(Pengiriman $pengiriman)
    {
        return redirect()->route('admin.pengiriman.index');
    }

    public function update(Request $request, Pengiriman $pengiriman)
    {
        $request->validate([
            'status_pengiriman' => 'required|in:proses,selesai',
            'jumlah_terkirim'   => 'nullable|integer|min:0',
            'bukti_foto'        => 'nullable|image|max:2048',
        ]);

        $data = $request->only('status_pengiriman', 'jumlah_terkirim');

        if ($request->hasFile('bukti_foto')) {
            $data['bukti_foto'] = $request->file('bukti_foto')
                ->store('bukti_pengiriman', 'public');
        }

        if ($request->status_pengiriman === 'selesai') {
            $data['waktu_selesai'] = now();

            $pengiriman->pesanan->update([
                'status_pesanan' => 'selesai'
            ]);

            NotifikasiService::kirim(
                (int) $pengiriman->pesanan->pelanggan_id, 
                'Pesanan Telah Selesai', 
                'Pesanan Anda telah berhasil dikirimkan. Terima kasih!', 
                'pengiriman', 
                $pengiriman->id, 
                'Pengiriman' 
            );
        }

        $pengiriman->update($data);

        return redirect()->route('admin.pengiriman.index')
            ->with('success', 'Status pengiriman berhasil diperbarui');
    }

    public function destroy(Pengiriman $pengiriman)
    {
        $pengiriman->delete();
        return back()->with('success', 'Pengiriman berhasil dihapus');
    }
}