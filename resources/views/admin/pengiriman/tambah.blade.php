@extends('admin.layout')
@section('title', 'Tambah Pengiriman')
@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5 flex items-center">
                <a href="{{ route('admin.pengiriman.index') }}" class="text-white hover:opacity-80 transition mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-lg font-semibold text-white">Buat Pengiriman Baru</h1>
            </div>
            <!-- Form -->
            <div class="p-6">
                <form action="{{ route('admin.pengiriman.store') }}" method="POST" class="space-y-5" x-data="pengirimanForm()">
                    @csrf

                    <!-- Pilih Pesanan -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Pesanan</label>
                        <select name="pesanan_id" @change="pilihPesanan($event.target.value)" required
                                class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Pesanan --</option>
                            @foreach($pesanan as $p)
                                <option value="{{ $p->id }}"
                                        data-pelanggan="{{ $p->pelanggan->nama_lengkap }}"
                                        data-alamat="{{ $p->pelanggan->alamat ?? '-' }}"
                                        data-jumlah="{{ $p->jumlah_pesanan }}">
                                    {{ $p->pelanggan->nama_lengkap }} - {{ $p->jumlah_pesanan }} pack
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Info Pesanan (readonly) -->
                    <div x-show="pesananTerpilih">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nama Pelanggan</label>
                        <input type="text" :value="namaPelanggan" disabled
                               class="w-full px-3 py-2.5 text-sm bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed">
                    </div>
                    <div x-show="pesananTerpilih">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Alamat Pelanggan</label>
                        <input type="text" :value="alamatPelanggan" disabled
                               class="w-full px-3 py-2.5 text-sm bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed">
                    </div>
                    <div x-show="pesananTerpilih">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Pengiriman</label>
                        <input type="text" :value="jumlah + ' pack'" disabled
                               class="w-full px-3 py-2.5 text-sm bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed">
                    </div>

                    <!-- Pilih Driver -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Driver</label>
                        <select name="driver_id" @change="pilihDriver($event.target.value)" required
                                class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Driver --</option>
                            @foreach($driver as $d)
                                <option value="{{ $d->id }}"
                                        data-telepon="{{ $d->nomor_telepon }}">
                                    {{ $d->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nomor Driver (readonly) -->
                    <div x-show="driverTerpilih">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nomor Telepon Driver</label>
                        <input type="text" :value="teleponDriver" disabled
                               class="w-full px-3 py-2.5 text-sm bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed">
                    </div>

                    <button type="submit"
                            class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-sm font-medium text-white rounded-lg shadow-md hover:shadow-lg transition">
                        Buat Pengiriman
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function pengirimanForm() {
    return {
        pesananTerpilih: false,
        alamatPelanggan: '',
        jumlah: '',
        driverTerpilih: false,
        teleponDriver: '',

        pilihPesanan(id) {
            const option = document.querySelector(`select[name="pesanan_id"] option[value="${id}"]`);
            if (option && id !== '') {
                this.namaPelanggan = option.dataset.pelanggan;
                this.alamatPelanggan = option.dataset.alamat;
                this.jumlah = option.dataset.jumlah;
                this.pesananTerpilih = true;
            } else {
                this.pesananTerpilih = false;
            }
        },

        pilihDriver(id) {
            const option = document.querySelector(`select[name="driver_id"] option[value="${id}"]`);
            if (option && id !== '') {
                this.teleponDriver = option.dataset.telepon;
                this.driverTerpilih = true;
            } else {
                this.driverTerpilih = false;
                this.teleponDriver = '';
            }
        }
    }
}
</script>
@endsection