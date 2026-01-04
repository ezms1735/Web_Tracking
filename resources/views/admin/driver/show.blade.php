@extends('admin.layout')
@section('title', 'Detail Driver')
@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-5 flex items-center">
                <a href="{{ route('admin.driver.index') }}" class="text-white hover:opacity-80 transition mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-lg font-semibold text-white">Detail Driver</h1>
            </div>

            <!-- Detail Content -->
            <div class="p-6 space-y-5">
                <div>
                    <label class="text-xs font-medium text-gray-600">Nama Lengkap</label>
                    <p class="mt-1 text-base font-semibold text-gray-900">{{ $driver->nama_lengkap }}</p>
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-600">Email</label>
                    <p class="mt-1 text-base text-gray-900 break-all">{{ $driver->email }}</p>
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-600">Nomor Telepon</label>
                    <p class="mt-1 text-base text-gray-900">{{ $driver->nomor_telepon }}</p>
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-600">Status</label>
                    <div class="mt-2">
                        <span class="inline-block px-3 py-1.5 rounded-full text-xs font-medium
                            {{ $driver->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($driver->status) }}
                        </span>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-600">Peran</label>
                    <p class="mt-1 text-base text-gray-900 capitalize">{{ $driver->peran ?? '-' }}</p>
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-600">Password (Hash)</label>
                    <p class="mt-1 text-gray-400 font-mono text-xs break-all bg-gray-100 px-3 py-2 rounded-lg">
                        {{ $driver->password }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Hash bcrypt yang tersimpan di database</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection