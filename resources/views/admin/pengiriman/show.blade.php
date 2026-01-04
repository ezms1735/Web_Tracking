@extends('admin.layout')
@section('title', 'Detail Pengiriman')
@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-5 flex items-center">
                <a href="{{ route('admin.pengiriman.index') }}" class="text-white hover:opacity-80 transition mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-lg font-semibold text-white">Detail Pengiriman</h1>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="text-xs font-medium text-gray-600">Nama Pelanggan</label>
                    <p class="mt-1 text-base font-semibold text-gray-900">{{ $pengiriman->pesanan->pelanggan->nama_lengkap }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600">Alamat Tujuan</label>
                    <p class="mt-1 text-base text-gray-900">{{ $pengiriman->pesanan->pelanggan->alamat ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600">Driver</label>
                    <p class="mt-1 text-base text-gray-900">{{ $pengiriman->driver->nama_lengkap }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600">No. Telepon Driver</label>
                    <p class="mt-1 text-base text-gray-900">{{ $pengiriman->driver->nomor_telepon }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600">Jumlah Barang</label>
                    <p class="mt-1 text-base text-gray-900">{{ $pengiriman->pesanan->jumlah_pesanan }} pack</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600">Status Pengiriman</label>
                    <div class="mt-2">
                        <span class="inline-block px-3 py-1.5 rounded-full text-xs font-medium
                            {{ $pengiriman->status_pengiriman == 'selesai' ? 'bg-green-100 text-green-800' :
                               ($pengiriman->status_pengiriman == 'dalam perjalanan' ? 'bg-blue-100 text-blue-800' :
                               ($pengiriman->status_pengiriman == 'proses' ? 'bg-yellow-100 text-yellow-800' :
                               ($pengiriman->status_pengiriman == 'belum_dikirim' ? 'bg-gray-200 text-gray-800' :
                               'bg-red-100 text-red-800'))) }}">
                            {{ ucfirst(str_replace('_', ' ', $pengiriman->status_pengiriman)) }}
                        </span>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600">Waktu Mulai</label>
                    <p class="mt-1 text-base text-gray-900">{{ $pengiriman->waktu_mulai ? \Carbon\Carbon::parse($pengiriman->waktu_mulai)->format('d M Y H:i') : '-' }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600">Waktu Selesai</label>
                    <p class="mt-1 text-base text-gray-900">{{ $pengiriman->waktu_selesai ? \Carbon\Carbon::parse($pengiriman->waktu_selesai)->format('d M Y H:i') : '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection