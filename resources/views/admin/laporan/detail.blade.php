@extends('admin.layout')

@section('title', 'Detail Pengiriman')

@section('content')
<div class="flex justify-between items-center mb-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('admin.laporan.index') }}" 
           class="flex items-center text-black-600 hover:text-black transition">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="w-5 h-5" 
                 fill="none" 
                 viewBox="0 0 24 24" 
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        <h1 class="text-2xl font-bold text-black-800">
            Detail Laporan - {{ $pengiriman->first()->driver->nama_lengkap ?? '-' }}
        </h1>
    </div>

    <div></div>
</div>

<div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            
            <thead class="bg-gray-50 text-black-700 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4 text-center">Nama Pelanggan</th>
                    <th class="px-6 py-4 text-center">Alamat</th>
                    <th class="px-6 py-4 text-center">Jumlah Pesanan</th>
                    <th class="px-6 py-4 text-center">Jumlah Terkirim</th>
                    <th class="px-6 py-4 text-center">Bukti Pengiriman</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 text-black-700">
                @forelse($pengiriman as $p)
                <tr class="hover:bg-gray-50">

                    <td class="px-6 py-4 font-bold">
                        {{ $p->pesanan?->pelanggan?->nama_lengkap ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $p->pesanan?->pelanggan?->alamat ?? '-' }}
                    </td>

                    <td class="px-6 py-4 text-center">
                        {{ $p->pesanan?->jumlah_pesanan ?? '-' }}
                    </td>

                    <td class="px-6 py-4 text-center">
                        {{ $p->jumlah_terkirim }}
                    </td>

                    <td class="px-6 py-4 text-center">
                        @if($p->bukti_foto)
                            <img src="{{ asset('storage/' . $p->bukti_foto) }}" 
                                 class="w-24 h-24 object-cover rounded-lg border">
                        @else
                            <div class="w-24 h-24 bg-gray-200 flex items-center justify-center rounded">
                                -
                            </div>
                        @endif
                    </td>

                    {{-- TOMBOL DETAIL --}}
                    <td class="px-6 py-4 text-center">
                        <button 
                            onclick="openModal(
                                '{{ $p->pesanan?->pelanggan?->nama_lengkap }}',
                                '{{ $p->pesanan?->pelanggan?->nomor_telepon }}',
                                '{{ $p->pesanan?->pelanggan?->alamat }}',
                                '{{ $p->pesanan?->jumlah_pesanan }}',
                                '{{ $p->jumlah_terkirim }}',
                                '{{ $p->waktu_mulai }}',
                                '{{ $p->waktu_selesai }}',
                                '{{ $p->bukti_foto ? asset('storage/'.$p->bukti_foto) : '' }}'
                            )"
                            class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">
                            Detail
                        </button>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-10 text-gray-500">
                        Tidak ada data
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>

{{-- ================= MODAL ================= --}}
<div id="modalDetail" class="fixed inset-0 bg-gray-500/60 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl w-[750px] overflow-hidden shadow-lg">
        
        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-purple-600 to-blue-500 text-white px-6 py-4 flex items-center gap-3">
            <button onclick="closeModal()">←</button>
            <h2 class="text-lg font-semibold">Detail Pesanan</h2>
        </div>

        {{-- CONTENT --}}
        <div class="p-6 grid grid-cols-3 gap-4">

            {{-- KIRI --}}
            <div class="col-span-2 space-y-3 text-sm">
                
                <div>
                    <label class="text-gray-600">Pelanggan</label>
                    <input id="m_nama" class="w-full border rounded px-2 py-1.5" readonly>
                </div>

                <div>
                    <label class="text-gray-600">Nomor Telepon</label>
                    <input id="m_telp" class="w-full border rounded px-2 py-1.5" readonly>
                </div>

                <div>
                    <label class="text-gray-600">Alamat</label>
                    <textarea id="m_alamat" rows="3" 
                        class="w-full border rounded px-2 py-1.5 resize-none" readonly></textarea>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-gray-600">Jumlah Pesanan</label>
                        <input id="m_pesanan" class="w-full border rounded px-2 py-1.5" readonly>
                    </div>

                    <div>
                        <label class="text-gray-600">Jumlah Terkirim</label>
                        <input id="m_terkirim" class="w-full border rounded px-2 py-1.5" readonly>
                    </div>
                </div>

                <div>
                    <label class="text-gray-600">Waktu Pengiriman</label>
                    <input id="m_waktu" class="w-full border rounded px-2 py-1.5" readonly>
                </div>
            </div>

            {{-- KANAN --}}
            <div class="col-span-1">
                <label class="text-sm text-gray-600">Bukti</label>
                <div class="mt-2 border rounded-lg overflow-hidden">
                    <img id="m_bukti" class="w-full h-48 object-cover">
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ================= SCRIPT ================= --}}
<script>
function openModal(nama, telp, alamat, pesanan, terkirim, mulai, selesai, bukti) {
    document.getElementById('modalDetail').classList.remove('hidden');
    document.getElementById('modalDetail').classList.add('flex');

    document.getElementById('m_nama').value = nama || '-';
    document.getElementById('m_telp').value = telp || '-';
    document.getElementById('m_alamat').value = alamat || '-';
    document.getElementById('m_pesanan').value = pesanan || '-';
    document.getElementById('m_terkirim').value = terkirim || '-';
    document.getElementById('m_waktu').value = (mulai && selesai) ? mulai + ' - ' + selesai : '-';
    document.getElementById('m_bukti').src = bukti || '';
}

function closeModal() {
    document.getElementById('modalDetail').classList.add('hidden');
}

window.onclick = function(e) {
    const modal = document.getElementById('modalDetail');
    if (e.target === modal) {
        modal.classList.add('hidden');
    }
}
</script>

@endsection