<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pengguna::where('peran', 'pelanggan')->get();
        return view('admin.pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        return view('admin.pelanggan.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'  => 'required',
            'email'         => 'required|email|unique:pengguna,email',
            'nomor_telepon' => 'required',
            'alamat'        => 'required',
            'password'      => 'required|min:6',
        ]);

        Pengguna::create([
            'nama_lengkap'  => $request->nama_lengkap,
            'email'         => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'alamat'        => $request->alamat,
            'password'      => Hash::make($request->password),
            'peran'         => 'pelanggan',
            'status'        => 'aktif',
        ]);

        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function show(Pengguna $pelanggan)
    {
        return view('admin.pelanggan.show', compact('pelanggan'));
    }

    public function edit(Pengguna $pelanggan)
    {
        return view('admin.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pengguna $pelanggan)
    {
        $request->validate([
            'nama_lengkap'  => 'required',
            'email'         => 'required|email|unique:pengguna,email,' . $pelanggan->id,
            'nomor_telepon' => 'required',
            'alamat'        => 'required',
            'password'      => 'nullable|min:6',
            'status'        => 'required',
        ]);

        $pelanggan->nama_lengkap  = $request->nama_lengkap;
        $pelanggan->email         = $request->email;
        $pelanggan->nomor_telepon = $request->nomor_telepon;
        $pelanggan->alamat        = $request->alamat;
        $pelanggan->status        = $request->status;

        if ($request->filled('password')) {
            $pelanggan->password = Hash::make($request->password);
        }

        $pelanggan->save();

        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Pelanggan berhasil diupdate');
    }

    public function destroy(Pengguna $pelanggan)
    {
        $pelanggan->delete();
        return back()->with('success', 'Pelanggan berhasil dihapus');
    }

    public function nonaktif(Pengguna $pelanggan)
    {
        $pelanggan->update(['status' => 'nonaktif']);
        return back()->with('success', 'Pelanggan berhasil dinonaktifkan');
    }
}
