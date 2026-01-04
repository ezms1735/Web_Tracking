@extends('admin.layout')
@section('title', 'Data Pengiriman')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Data Pengiriman</h1>
        <a href="{{ route('admin.pengiriman.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg transition shadow">
            + Buat Pengiriman
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Alamat Pelanggan</th>
                        <th class="px-6 py-4">Driver</th>
                        <th class="px-6 py-4">No. Driver</th>
                        <th class="px-6 py-4">Jumlah</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pengiriman as $p)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium">
                            {{ $p->pesanan->pelanggan->nama_lengkap }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $p->pesanan->pelanggan->alamat }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $p->driver->nama_lengkap }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $p->driver->nomor_telepon }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $p->pesanan->jumlah_pesanan }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-3 py-1.5 rounded-full text-xs font-medium whitespace-nowrap
                                {{ $p->status_pengiriman == 'selesai' ? 'bg-green-100 text-green-700' :
                                   ($p->status_pengiriman == 'dalam perjalanan' ? 'bg-blue-100 text-blue-700' :
                                   ($p->status_pengiriman == 'proses' ? 'bg-yellow-100 text-yellow-700' :
                                   ($p->status_pengiriman == 'belum_dikirim' ? 'bg-gray-200 text-gray-800' :
                                   'bg-red-100 text-red-700'))) }}">
                                {{ ucfirst(str_replace('_', ' ', $p->status_pengiriman)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.pengiriman.show', $p->id) }}"
                                   class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                    </svg>
                                    Detail
                                </a>
                                <a href="{{ route('admin.pengiriman.edit', $p->id) }}"
                                   class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white bg-amber-500 rounded hover:bg-amber-600 transition">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-gray-500">
                            Data pengiriman kosong
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection