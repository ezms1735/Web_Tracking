@extends('admin.layout')

@section('title', 'Detail Pengiriman')

@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <!-- Back Button & Title -->
    <div class="flex items-center mb-8">
        <a href="{{ route('admin.pengiriman.index') }}"
           class="mr-4 p-2 rounded-full bg-white shadow hover:shadow-md transition">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Detail Pengiriman #{{ $pengiriman->id }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Informasi Utama -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-6 py-4">
                <h2 class="text-xl font-semibold text-white">Informasi Pengiriman</h2>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="text-sm font-medium text-gray-600">Status Pengiriman</label>
                    <div class="mt-2">
                        @php
                            $status = $pengiriman->status_pengiriman;
                            $badgeClass = match($status) {
                                'selesai'         => 'bg-green-100 text-green-800',
                                'proses'          => 'bg-orange-100 text-orange-800',
                                'belum_dikirim'   => 'bg-gray-100 text-gray-800',
                                default           => 'bg-blue-100 text-blue-800'
                            };
                            $statusText = match($status) {
                                'belum_dikirim' => 'Belum Berangkat',
                                'proses'        => 'Sedang Dikirim',
                                'selesai'       => 'Selesai',
                                default         => ucfirst(str_replace('_', ' ', $status))
                            };
                        @endphp
                        <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold {{ $badgeClass }}">
                            {{ $statusText }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Waktu Mulai</label>
                        <p class="mt-1 text-base font-semibold text-gray-900">
                            {{ $pengiriman->waktu_mulai ? $pengiriman->waktu_mulai->format('d M Y H:i') : '-' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Waktu Selesai</label>
                        <p class="mt-1 text-base font-semibold {{ $pengiriman->waktu_selesai ? 'text-green-700' : 'text-gray-900' }}">
                            {{ $pengiriman->waktu_selesai ? $pengiriman->waktu_selesai->format('d M Y H:i') : '-' }}
                        </p>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Jumlah Barang</label>
                    <p class="mt-1 text-2xl font-bold text-indigo-700">
                        {{ $pengiriman->pesanan->jumlah_pesanan }} pack
                    </p>
                </div>
            </div>
        </div>

        <!-- Informasi Pelanggan & Driver -->
        <div class="space-y-6">
            <!-- Kartu Pelanggan -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-teal-600 to-cyan-700 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">Pelanggan</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Nama Lengkap</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">
                            {{ $pengiriman->pesanan->pelanggan->nama_lengkap }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Alamat Tujuan</label>
                        <p class="mt-1 text-base text-gray-900">
                            {{ $pengiriman->pesanan->pelanggan->alamat ?? 'Alamat belum diisi' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">No. Telepon</label>
                        <p class="mt-1 text-base text-gray-900">
                            {{ $pengiriman->pesanan->pelanggan->nomor_telepon ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kartu Driver -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-amber-600 to-orange-700 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">Driver Penugasan</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Nama Driver</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">
                            {{ $pengiriman->driver->nama_lengkap }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">No. Telepon Driver</label>
                        <div class="mt-1 flex items-center gap-3">
                            <p class="text-base text-gray-900">
                                {{ $pengiriman->driver->nomor_telepon }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection