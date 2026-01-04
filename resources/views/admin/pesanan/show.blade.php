@extends('admin.layout')
@section('title', 'Detail Pesanan')
@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-5 flex items-center">
                <a href="{{ route('admin.pesanan.index') }}" class="text-white hover:opacity-80 transition mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-lg font-semibold text-white">Detail Pesanan</h1>
            </div>
            <!-- Detail Content -->
            <div class="p-6 space-y-5">
                <div>
                    <label class="text-xs font-medium text-gray-600">Pelanggan</label>
                    <p class="mt-1 text-base font-semibold text-gray-900">{{ $pesanan->pelanggan->nama_lengkap }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600">Jumlah Pesanan</label>
                    <p class="mt-1 text-base text-gray-900">{{ $pesanan->jumlah_pesanan }} pack</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600">Status Pesanan</label>
                    <div class="mt-2">
                        <span class="inline-block px-3 py-1.5 rounded-full text-xs font-medium
                            {{ $pesanan->status_pesanan == 'selesai' ? 'bg-green-100 text-green-800' :
                               ($pesanan->status_pesanan == 'proses' ? 'bg-yellow-100 text-yellow-800' :
                               'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst(str_replace('_', ' ', $pesanan->status_pesanan)) }}
                        </span>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600">Tanggal Dibuat</label>
                    <p class="mt-1 text-base text-gray-900">{{ $pesanan->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection