@extends('admin.layout')
@section('title', 'Data Pelanggan')
@section('content')
    <!-- Konten Utama: Header + Tabel -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Data Pelanggan</h1>
        <a href="{{ route('admin.pelanggan.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg transition shadow">
            + Tambah Pelanggan
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
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">No. Telepon</th>
                        <th class="px-6 py-4">Alamat</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pelanggan as $p)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium">{{ $p->nama_lengkap }}</td>
                        <td class="px-6 py-4">{{ $p->email }}</td>
                        <td class="px-6 py-4">{{ $p->nomor_telepon }}</td>
                        <td class="px-6 py-4">{{ $p->alamat ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                {{ $p->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Detail -->
                                <a href="{{ route('admin.pelanggan.show', $p->id) }}"
                                   class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                    </svg>
                                    Detail
                                </a>
                                <!-- Edit -->
                                <a href="{{ route('admin.pelanggan.edit', $p->id) }}"
                                   class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white bg-amber-500 rounded hover:bg-amber-600 transition">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                                <!-- Hapus -->
                                <form action="{{ route('admin.pelanggan.destroy', $p->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus pelanggan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700 transition">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-500">
                            Data pelanggan kosong
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection