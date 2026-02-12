@extends('admin.layout')

@section('title', 'Laporan')

@use('Illuminate\Support\Carbon')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Laporan Pengiriman</h1>
        
        <form method="GET" class="flex items-center gap-2 bg-white p-1.5 rounded-xl border border-gray-200 shadow-sm">
            <select name="bulan" 
                    class="bg-transparent border-none text-gray-700 text-sm font-medium focus:ring-0 cursor-pointer px-3">
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                        {{ Carbon::create(null, $i)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>

            <div class="h-6 w-px bg-gray-200"></div>

            <select name="tahun" 
                    class="bg-transparent border-none text-gray-700 text-sm font-medium focus:ring-0 cursor-pointer px-3">
                @foreach ($daftarTahun as $th)
                    <option value="{{ $th }}" {{ $tahun == $th ? 'selected' : '' }}>{{ $th }}</option>
                @endforeach
            </select>

            <button type="submit" 
                    class="bg-gray-800 hover:bg-black text-white px-4 py-2 rounded-lg font-medium transition text-sm">
                Tampilkan
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Driver</th>
                        <th class="px-6 py-4 text-center font-semibold">Total Pelanggan</th>
                        <th class="px-6 py-4 text-center font-semibold">Total Pengiriman</th>
                        <th class="px-6 py-4 text-center font-semibold">Waktu Terakhir</th>
                        <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse($laporanPerDriver ?? [] as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium">
                            {{ $item->nama }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-semibold">{{ $item->total_pelanggan }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-semibold">{{ $item->total_pengiriman }}</span>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-500 italic">
                            {{ $item->waktu_terakhir ? Carbon::parse($item->waktu_terakhir)->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Tombol Detail --}}
                                <a href="#" 
                                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </a>
                                {{-- Tombol Unduh --}}
                                <a href="{{ route('admin.laporan.download') }}?bulan={{ $bulan }}&tahun={{ $tahun }}" 
                                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-purple-600 rounded hover:bg-purple-700 transition">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Unduh
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center text-gray-500 text-base">
                            Belum ada data pengiriman selesai pada periode ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection