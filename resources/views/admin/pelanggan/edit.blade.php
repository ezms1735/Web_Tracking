@extends('admin.layout')
@section('title', 'Edit Pelanggan')
@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-5 flex items-center">
                <a href="{{ route('admin.pelanggan.index') }}" class="text-white hover:opacity-80 transition mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-lg font-semibold text-white">Edit Pelanggan</h1>
            </div>
            <!-- Form -->
            <div class="p-6">
                <form action="{{ route('admin.pelanggan.update', $pelanggan->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $pelanggan->nama_lengkap) }}" required
                               class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $pelanggan->email) }}" required
                               class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon', $pelanggan->nomor_telepon) }}" required
                               class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="alamat" rows="3"
                                  class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="password"
                               class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1.5">Kosongkan jika tidak ingin mengubah password.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <select name="status"
                                class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            <option value="aktif" {{ $pelanggan->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ $pelanggan->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <button type="submit"
                            class="w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-sm font-medium text-white rounded-lg shadow-md hover:shadow-lg transition">
                        Update Pelanggan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection