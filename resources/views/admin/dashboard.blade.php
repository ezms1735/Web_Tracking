@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')

{{-- UCAPAN --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold">Selamat Datang, Admin 👋</h1>
</div>

{{-- STATISTIK --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <div class="bg-white p-5 rounded shadow">
        <p class="text-gray-500 text-sm">Total Pelanggan</p>
        <p class="text-3xl font-bold">{{ $totalPelanggan ?? 0 }}</p>
    </div>

    <div class="bg-white p-5 rounded shadow">
        <p class="text-gray-500 text-sm">Total Driver</p>
        <p class="text-3xl font-bold">{{ $totalDriver ?? 0 }}</p>
    </div>

    <div class="bg-white p-5 rounded shadow">
        <p class="text-gray-500 text-sm">Pesanan Belum Diproses</p>
        <p class="text-3xl font-bold text-red-600">{{ $pesananBelumDiproses ?? 0 }}</p>
    </div>

    <div class="bg-white p-5 rounded shadow">
        <p class="text-gray-500 text-sm">Pesanan Diproses</p>
        <p class="text-3xl font-bold text-green-600">{{ $pesananDiproses ?? 0 }}</p>
    </div>

</div>

<!-- {{-- MAPS --}}
<div class="bg-white rounded shadow mb-8">
    <div class="p-4 border-b font-semibold">
        Peta Pemantauan Pengiriman
    </div>

    <div id="map" class="h-96 w-full"></div>
</div> -->

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- PESANAN BARU --}}
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-red-50 to-white">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                Pesanan Baru
            </h3>
        </div>

        <div class="p-6 space-y-4 max-h-96 overflow-y-auto">
            @forelse ($pesananBaru as $item)
                <div class="p-4 bg-red-50 rounded-lg hover:bg-red-100 transition duration-200 border border-red-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <p class="font-medium text-gray-800 text-sm">{{ $item->pelanggan->nama_lengkap }}</p>
                                <span class="px-2.5 py-1 text-xs font-medium text-red-800 bg-red-200 rounded-full">
                                    Menunggu Konfirmasi
                                </span>
                            </div>

                            <div class="text-xs text-gray-600 space-y-1">
                                <p>📞 {{ $item->pelanggan->nomor_telepon }}</p>
                                <p>🏠 {{ $item->pelanggan->alamat }}</p>
                                <p>📦 Jumlah: <span class="font-medium">{{ $item->jumlah_pesanan }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-400 py-8">Belum ada pesanan baru</p>
            @endforelse
        </div>
    </div>

    {{-- PESANAN DIPROSES --}}
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-white">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <span class="w-3 h-3 bg-amber-500 rounded-full"></span>
                Pesanan Diproses
            </h3>
        </div>

        <div class="p-6 space-y-4 max-h-96 overflow-y-auto">
            @forelse ($pesananProses as $item)
                <div class="p-4 bg-amber-50 rounded-lg hover:bg-amber-100 transition duration-200 border border-amber-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <p class="font-medium text-gray-800 text-sm">{{ $item->pelanggan->nama_lengkap }}</p>
                                <span class="px-2.5 py-1 text-xs font-medium text-amber-800 bg-amber-200 rounded-full">
                                    Sedang Diproses
                                </span>
                            </div>

                            <div class="text-xs text-gray-600 space-y-1">
                                <p>🏠 {{ $item->pelanggan->alamat }}</p>
                                <p>🚛 Driver: <span class="font-medium">{{ $item->pengiriman->driver->nama_lengkap ?? '-' }}</span></p>
                                <p>📦 Jumlah: <span class="font-medium">{{ $item->jumlah_pesanan }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-400 py-8">Belum ada pesanan yang diproses</p>
            @endforelse
        </div>
    </div>

</div>

@endsection

@section('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-database-compat.js"></script>

<script src="{{ asset('js/map.js') }}"></script>
@endsection
