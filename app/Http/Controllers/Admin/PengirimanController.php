<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengiriman;
use App\Models\Pesanan;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class PengirimanController extends Controller
{
    public function index()
    {
        $pengiriman = Pengiriman::with([
            'pesanan.pelanggan',
            'driver'
        ])->latest()->get();

        return view('admin.pengiriman.index', compact('pengiriman'));
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
            'pesanan_id' => 'required|exists:pesanan,id',
            'driver_id'  => 'required|exists:pengguna,id',
        ]);

        Pengiriman::create([
            'pesanan_id'       => $request->pesanan_id,
            'driver_id'        => $request->driver_id,
            'status_pengiriman'=> 'belum_dikirim',
            'waktu_mulai'      => now(),
            'waktu_selesai'    => null,
        ]);

        // Pesanan masuk proses
        Pesanan::where('id', $request->pesanan_id)
            ->update(['status_pesanan' => 'proses']);

        return redirect()->route('admin.pengiriman.index')
            ->with('success', 'Pengiriman berhasil dibuat');
    }

    public function show(Pengiriman $pengiriman)
    {
        return view('admin.pengiriman.show', compact('pengiriman'));
    }

    public function edit(Pengiriman $pengiriman)
    {
        return view('admin.pengiriman.edit', compact('pengiriman'));
    }

    public function update(Request $request, Pengiriman $pengiriman)
    {
        $request->validate([
            'status_pengiriman' => 'required|in:belum_dikirim,proses,selesai',
        ]);

        $data = [
            'status_pengiriman' => $request->status_pengiriman,
        ];

        // Jika pengiriman selesai
        if ($request->status_pengiriman === 'selesai') {
            $data['waktu_selesai'] = now();

            // Sinkronkan pesanan
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
