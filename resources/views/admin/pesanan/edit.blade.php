@extends('admin.layout')
@section('title', 'Edit Pesanan')
@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-5 flex items-center">
                <a href="{{ route('admin.pesanan.index') }}" class="text-white hover:opacity-80 transition mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-lg font-semibold text-white">Edit Pesanan</h1>
            </div>
            <!-- Form -->
            <div class="p-6">
                <form action="{{ route('admin.pesanan.update', $pesanan->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Pelanggan</label>
                        <input type="text" value="{{ $pesanan->pelanggan->nama_lengkap }}" disabled
                               class="w-full px-3 py-2.5 text-sm bg-gray-100 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Pesanan</label>
                        <input type="number" name="jumlah_pesanan" value="{{ old('jumlah_pesanan', $pesanan->jumlah_pesanan) }}" required min="1"
                               class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status Pesanan</label>
                        <select name="status_pesanan"
                                class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            <option value="menunggu" {{ $pesanan->status_pesanan == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="proses" {{ $pesanan->status_pesanan == 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="selesai" {{ $pesanan->status_pesanan == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <button type="submit"
                            class="w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-sm font-medium text-white rounded-lg shadow-md hover:shadow-lg transition">
                        Update Pesanan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection