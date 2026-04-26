<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengiriman;
use App\Models\Pesanan;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengirimanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengiriman::with([
            'pesanan.pelanggan',
            'driver'
        ])->latest();

        if ($request->filled('tanggal') && is_numeric($request->tanggal)) {
            $query->whereDay('created_at', $request->tanggal);
        }

        if ($request->filled('bulan') && is_numeric($request->bulan)) {
            $query->whereMonth('created_at', $request->bulan);
        }

        if ($request->filled('tahun') && is_numeric($request->tahun)) {
            $query->whereYear('created_at', $request->tahun);
        }

        $pengiriman = $query->paginate(10);

        $daftarTahun = Pengiriman::select(DB::raw('YEAR(created_at) as tahun'))
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        if (empty($daftarTahun)) {
            $daftarTahun = [now()->year];
        }

        return view('admin.pengiriman.index', compact('pengiriman', 'daftarTahun'));
    }

    public function create()
    {
        $pesanan = Pesanan::where('status_pesanan', 'menunggu')->get();

        $driver = Pengguna::where('peran', 'driver')
            ->where('status', 'aktif')
            ->get();

        return view('admin.pengiriman.tambah', compact('pesanan', 'driver'));
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

        Pengiriman::create($data);

        Pesanan::where('id', $request->pesanan_id)
            ->update(['status_pesanan' => 'proses']);

        return redirect()->route('admin.pengiriman.index')
            ->with('success', 'Pengiriman berhasil dibuat');
    }

    public function show(Pengiriman $pengiriman)
    {
        $pengiriman->load(['pesanan.pelanggan', 'driver']); 
        return view('admin.pengiriman.show', compact('pengiriman'));
    }

    public function edit(Pengiriman $pengiriman)
    {
        $pengiriman->load(['pesanan', 'driver']);
        return view('admin.pengiriman.edit', compact('pengiriman'));
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