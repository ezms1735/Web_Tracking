@extends('admin.layout')

@section('title', 'Laporan Pengiriman Selesai')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">📊 Laporan Pengiriman Selesai</h1>

    <!-- Form Filter Bulan & Tahun -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select name="bulan" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $i)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select name="tahun" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach($daftarTahun as $thn)
                        <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700">
                    Tampilkan
                </button>
            </div>
        </form>
    </div>

    <!-- Ringkasan Bulan Ini -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-lg p-8 mb-10 text-white">
        <h2 class="text-2xl font-bold mb-6">
            Ringkasan {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div>
                <p class="text-5xl font-bold">{{ number_format($totalPackBulanIni) }}</p>
                <p class="text-lg opacity-90 mt-3">Total Pack Terantar</p>
            </div>
            <div>
                <p class="text-5xl font-bold">{{ $totalDriverBulanIni }}</p>
                <p class="text-lg opacity-90 mt-3">Driver Bertugas</p>
            </div>
            <div>
                <p class="text-5xl font-bold">{{ $totalTransaksiBulanIni }}</p>
                <p class="text-lg opacity-90 mt-3">Transaksi Selesai</p>
            </div>
        </div>
    </div>

    <!-- Tombol Download Excel -->
    <div class="mb-6 text-right">
        <a href="{{ route('admin.laporan.download', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
           class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">
            📥 Download Excel
        </a>
    </div>

    @if($laporan->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center">
            <p class="text-yellow-800 text-lg">Belum ada pengiriman yang selesai pada periode ini.</p>
        </div>
    @else
        @foreach($laporan as $tanggal => $daftarPengiriman)
            <div class="mb-12">
                <h3 class="text-2xl font-bold text-indigo-700 mb-6">
                    📅 {{ $tanggal }} 
                    <span class="text-lg font-normal text-gray-600">
                        ({{ $daftarPengiriman->count() }} pengiriman)
                    </span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($daftarPengiriman as $pengiriman)
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-green-600 to-emerald-700 p-4 text-white">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="text-3xl">✅</div>
                                        <div>
                                            <h4 class="text-lg font-bold">
                                                {{ $pengiriman->driver->nama_lengkap ?? 'Driver Tidak Diketahui' }}
                                            </h4>
                                            <p class="text-xs opacity-90">
                                                {{ $pengiriman->waktu_selesai->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-5">
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Pelanggan</p>
                                        <p class="font-semibold">
                                            {{ $pengiriman->pesanan->pelanggan->nama_lengkap ?? 'Pelanggan Tidak Diketahui' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Jumlah Antar</p>
                                        <p class="font-bold text-2xl text-green-700">
                                            {{ $pengiriman->pesanan->jumlah_pesanan }} pack
                                        </p>
                                    </div>

                                    @if($pengiriman->bukti_foto)
                                        <div>
                                            <p class="text-sm text-gray-600 mb-2">Bukti Pengiriman</p>
                                            <img src="{{ asset('storage/' . $pengiriman->bukti_foto) }}"
                                                 alt="Bukti foto"
                                                 class="w-full rounded-lg shadow border">
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 italic">Tidak ada bukti foto</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection