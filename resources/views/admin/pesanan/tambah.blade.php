@extends('admin.layout')
@section('title', 'Tambah Pesanan')
@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5 flex items-center">
                <a href="{{ route('admin.pesanan.index') }}" class="text-white hover:opacity-80 transition mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-lg font-semibold text-white">Tambah Pesanan Baru</h1>
            </div>
            <!-- Form -->
            <div class="p-6">
                <form action="{{ route('admin.pesanan.store') }}" method="POST" class="space-y-5" x-data="pesananForm()">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Pelanggan</label>
                        <select name="pelanggan_id" @change="pilihPelanggan($event.target.value)" required
                                class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($pelanggan as $p)
                                <option value="{{ $p->id }}"
                                        data-alamat="{{ $p->alamat ?? '-' }}"
                                        data-telepon="{{ $p->nomor_telepon }}">
                                    {{ $p->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div x-show="selectedPelanggan">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Alamat Pelanggan</label>
                        <input type="text" :value="alamat" disabled
                               class="w-full px-3 py-2.5 text-sm bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed">
                    </div>

                    <div x-show="selectedPelanggan">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="text" :value="telepon" disabled
                               class="w-full px-3 py-2.5 text-sm bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Pesanan</label>
                        <input type="number" name="jumlah_pesanan" required min="1"
                               class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <button type="submit"
                            class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-sm font-medium text-white rounded-lg shadow-md hover:shadow-lg transition">
                        Simpan Pesanan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function pesananForm() {
    return {
        selectedPelanggan: false,
        alamat: '',
        telepon: '',
        pilihPelanggan(id) {
            const option = document.querySelector(`option[value="${id}"]`);
            if (option && id !== '') {
                this.alamat = option.dataset.alamat;
                this.telepon = option.dataset.telepon;
                this.selectedPelanggan = true;
            } else {
                this.selectedPelanggan = false;
                this.alamat = '';
                this.telepon = '';
            }
        }
    }
}
</script>
@endsection