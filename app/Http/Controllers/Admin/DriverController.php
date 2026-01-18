<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function index()
    {
        $driver = Pengguna::where('peran', 'driver')->get();
        return view('admin.driver.index', compact('driver'));
    }

    public function create()
    {
        return view('admin.driver.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'  => 'required',
            'email'         => 'required|email|unique:pengguna,email',
            'nomor_telepon' => 'required',
            'password'      => 'required|min:6',
        ]);

        Pengguna::create([
            'nama_lengkap'  => $request->nama_lengkap,
            'email'         => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'password'      => Hash::make($request->password),
            'peran'         => 'driver',
            'status'        => 'aktif',
        ]);

        return redirect()->route('admin.driver.index')
            ->with('success', 'Driver berhasil ditambahkan');
    }

    public function show(Pengguna $driver)
    {
        return view('admin.driver.show', compact('driver'));
    }

    public function edit(Pengguna $driver)
    {
        return view('admin.driver.edit', compact('driver'));
    }

    public function update(Request $request, Pengguna $driver)
    {
        $request->validate([
            'nama_lengkap'  => 'required',
            'email'         => 'required|email|unique:pengguna,email,' . $driver->id,
            'nomor_telepon' => 'required',
            'password'      => 'nullable|min:6',
            'status'        => 'required',
        ]);

        $driver->nama_lengkap  = $request->nama_lengkap;
        $driver->email         = $request->email;
        $driver->nomor_telepon = $request->nomor_telepon;
        $driver->status        = $request->status;

        if ($request->filled('password')) {
            $driver->password = Hash::make($request->password);
        }

        $driver->save();

        return redirect()->route('admin.driver.index')
            ->with('success', 'Driver berhasil diupdate');
    }

    public function destroy(Pengguna $driver)
    {
        $driver->delete();
        return back()->with('success', 'Driver berhasil dihapus');
    }

    public function nonaktif(Pengguna $driver)
    {
        $driver->update(['status' => 'nonaktif']);
        return back()->with('success', 'Driver berhasil dinonaktifkan');
    }

    public function editModal($id)
{
    $driver = Driver::findOrFail($id);
    return view('admin.driver.partials.edit-modal', compact('driver'));
}

public function detailModal($id)
{
    $driver = Driver::findOrFail($id);
    return view('admin.driver.partials.detail-modal', compact('driver'));
}
public function pesanan(Request $request)
{
    $driver = $request->user();
    $pesanan = $driver->pesananSebagaiDriver()->with('pelanggan')->get();

    return response()->json([
        'success' => true,
        'pesanan' => $pesanan
    ]);
}


}
