<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with('pelanggan')->latest()->get();
        return view('admin.pesanan.index', compact('pesanan'));
    }

    public function create()
    {
        $pelanggan = Pengguna::where('peran', 'pelanggan')->get();
        return view('admin.pesanan.tambah', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id'   => 'required',
            'jumlah_pesanan' => 'required|numeric',
        ]);

        Pesanan::create([
            'pelanggan_id'   => $request->pelanggan_id,
            'jumlah_pesanan' => $request->jumlah_pesanan,
            'status_pesanan' => 'menunggu',
        ]);

        return redirect()->route('admin.pesanan.index')
            ->with('success', 'Pesanan berhasil ditambahkan');
    }

    public function show(Pesanan $pesanan)
    {
        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function edit(Pesanan $pesanan)
    {
        return view('admin.pesanan.edit', compact('pesanan'));
    }

    public function update(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'jumlah_pesanan' => 'required|numeric',
            'status_pesanan' => 'required',
        ]);

        $pesanan->update($request->only('jumlah_pesanan', 'status_pesanan'));

        return redirect()->route('admin.pesanan.index')
            ->with('success', 'Pesanan berhasil diupdate');
    }

    public function destroy(Pesanan $pesanan)
    {
        $pesanan->delete();
        return back()->with('success', 'Pesanan berhasil dihapus');
    }
}
