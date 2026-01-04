@extends('admin.layout')
@section('title', 'Edit Pengiriman')
@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-5 flex items-center">
                <a href="{{ route('admin.pengiriman.index') }}" class="text-white hover:opacity-80 transition mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-lg font-semibold text-white">Edit Pengiriman</h1>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.pengiriman.update', $pengiriman->id) }}" method="POST" class="space-y-5">
                    @csrf @method('PUT')

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status Pengiriman</label>
                        <select name="status_pengiriman"
                                class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option value="belum_dikirim" {{ $pengiriman->status_pengiriman == 'belum_dikirim' ? 'selected' : '' }}>Belum Dikirim</option>
                            <option value="proses" {{ $pengiriman->status_pengiriman == 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="selesai" {{ $pengiriman->status_pengiriman == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <button type="submit"
                            class="w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-sm font-medium text-white rounded-lg shadow-md hover:shadow-lg transition">
                        Update Pengiriman
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection