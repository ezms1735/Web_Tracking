@extends('admin.layout')

@section('title', 'Data Pesanan')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Data Pesanan</h1>
        
        <button onclick="openModalTambah()"
           class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg transition shadow ml-90">
            + Tambah Pesanan
        </button>

        <!-- Form Filter -->
        <form method="GET" class="flex flex-wrap items-center gap-2 bg-white p-2 rounded-xl border border-gray-200 shadow-sm">
            <!-- Tanggal -->
            <select name="tanggal" 
                    class="bg-transparent border border-gray-300 rounded-lg text-gray-700 text-sm font-medium focus:ring-0 cursor-pointer px-3 py-1.5">
                <option value="">Semua Tanggal</option>
                @for ($i = 1; $i <= 31; $i++)
                    <option value="{{ $i }}" {{ request('tanggal') == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>
            
            <!-- Bulan -->
            <select name="bulan" 
                    class="bg-transparent border border-gray-300 rounded-lg text-gray-700 text-sm font-medium focus:ring-0 cursor-pointer px-3 py-1.5">
                <option value="">Semua Bulan</option>
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create(null, $i)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>

            <!-- Tahun -->
            <select name="tahun" 
                    class="bg-transparent border border-gray-300 rounded-lg text-gray-700 text-sm font-medium focus:ring-0 cursor-pointer px-3 py-1.5">
                <option value="">Semua Tahun</option>
                @if(isset($daftarTahun))
                    @foreach ($daftarTahun as $th)
                        <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>{{ $th }}</option>
                    @endforeach
                @endif
            </select>

            <button type="submit" 
                    class="bg-gray-800 hover:bg-black text-white px-4 py-1.5 rounded-lg font-medium transition text-sm">
                Filter
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">No. Telepon</th>
                        <th class="px-6 py-4 text-center">Alamat</th>
                        <th class="px-6 py-4 text-center">Jumlah</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pesanan as $p)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium">
                            {{ $p->pelanggan->nama_lengkap ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $p->pelanggan->nomor_telepon ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $p->pelanggan->alamat ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            {{ $p->jumlah_pesanan }} pack
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                {{ $p->status_pesanan == 'selesai' ? 'bg-green-100 text-green-700' :
                                   ($p->status_pesanan == 'proses' ? 'bg-yellow-100 text-yellow-700' :
                                   'bg-gray-100 text-gray-700') }}">
                                {{ ucfirst(str_replace('_', ' ', $p->status_pesanan)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                
                                <!-- Tombol Detail Modal -->
                                <button type="button" onclick="openModalShow(
                                        `{{ addslashes($p->pelanggan->nama_lengkap ?? '-') }}`,
                                        '{{ $p->jumlah_pesanan }}',
                                        '{{ $p->status_pesanan }}',
                                        '{{ $p->created_at->format('d M Y H:i') }}'
                                    )"
                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                    </svg>
                                    Detail
                                </button>

                                <!-- Tombol Edit Modal -->
                                <button type="button" onclick="openModalEdit(
                                        '{{ $p->id }}',
                                        `{{ addslashes($p->pelanggan->nama_lengkap ?? '-') }}`,
                                        '{{ $p->jumlah_pesanan }}',
                                        '{{ $p->status_pesanan }}'
                                    )"
                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white bg-amber-500 rounded hover:bg-amber-600 transition">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </button>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('admin.pesanan.destroy', $p->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus pesanan ini?')">
                                    @csrf @method('DELETE')
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
                            Data pesanan kosong
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pesanan->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>

    {{-- ================= MODAL DETAIL ================= --}}
    <div id="modalShow" class="fixed inset-0 bg-gray-500/60 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl w-[480px] overflow-hidden shadow-lg">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-6 py-4 flex items-center gap-3">
                <button type="button" onclick="closeModal('modalShow')" class="hover:opacity-75 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <h2 class="text-lg font-semibold">Detail Pesanan</h2>
            </div>
            {{-- Content --}}
            <div class="p-6 space-y-5">
                <div>
                    <label class="text-xs font-medium text-gray-600">Pelanggan</label>
                    <p id="show_nama" class="mt-1 text-base font-semibold text-gray-900"></p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600">Jumlah Pesanan</label>
                    <p id="show_jumlah" class="mt-1 text-base text-gray-900"></p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600">Status Pesanan</label>
                    <div class="mt-2">
                        <span id="show_status_badge" class="inline-block px-3 py-1.5 rounded-full text-xs font-medium"></span>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600">Tanggal Dibuat</label>
                    <p id="show_tanggal" class="mt-1 text-base text-gray-900"></p>
                </div>
            </div>
            {{-- Footer --}}
            <div class="px-6 pb-5 flex justify-end">
                <button type="button" onclick="closeModal('modalShow')"
                    class="px-5 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>


    {{-- ================= MODAL EDIT ================= --}}
    <div id="modalEdit" class="fixed inset-0 bg-gray-500/60 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl w-[480px] overflow-hidden shadow-lg">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-amber-500 to-amber-600 text-white px-6 py-4 flex items-center gap-3">
                <button type="button" onclick="closeModal('modalEdit')" class="hover:opacity-75 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <h2 class="text-lg font-semibold">Edit Pesanan</h2>
            </div>
            {{-- Form --}}
            <form id="formEdit" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4 text-sm">
                    <div>
                        <label class="text-gray-600 text-xs uppercase font-medium">Pelanggan</label>
                        <input type="text" id="edit_nama" disabled
                               class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 mt-1 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="text-gray-600 text-xs uppercase font-medium">Jumlah Pesanan <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_pesanan" id="edit_jumlah" required min="1"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-amber-400">
                    </div>
                    <div>
                        <label class="text-gray-600 text-xs uppercase font-medium">Status Pesanan <span class="text-red-500">*</span></label>
                        <select name="status_pesanan" id="edit_status"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-amber-400">
                            <option value="menunggu">Menunggu</option>
                            <option value="proses">Proses</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                {{-- Footer --}}
                <div class="px-6 pb-5 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modalEdit')"
                        class="px-5 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 text-sm bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition font-medium">
                        Update Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- ================= MODAL TAMBAH ================= --}}
    <div id="modalTambah" class="fixed inset-0 bg-gray-500/60 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl w-[480px] overflow-hidden shadow-lg">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 flex items-center gap-3">
                <button type="button" onclick="closeModal('modalTambah')" class="hover:opacity-75 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <h2 class="text-lg font-semibold">Tambah Pesanan Baru</h2>
            </div>
            
            {{-- Form Alpine --}}
            <form action="{{ route('admin.pesanan.store') }}" method="POST" x-data="pesananForm()">
                @csrf
                <div class="p-6 space-y-4 text-sm">
                    <div>
                        <label class="text-gray-600 text-xs uppercase font-medium">Pelanggan <span class="text-red-500">*</span></label>
                        <select name="pelanggan_id" @change="pilihPelanggan($event)" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Pelanggan --</option>
                            @if(isset($pelanggan))
                                @foreach($pelanggan as $p)
                                    <option value="{{ $p->id }}"
                                            data-alamat="{{ $p->alamat ?? '-' }}"
                                            data-telepon="{{ $p->nomor_telepon }}">
                                        {{ $p->nama_lengkap }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div x-show="selectedPelanggan" style="display: none;">
                        <label class="text-gray-600 text-xs uppercase font-medium">Alamat Pelanggan</label>
                        <input type="text" :value="alamat" disabled
                               class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 mt-1 cursor-not-allowed">
                    </div>

                    <div x-show="selectedPelanggan" style="display: none;">
                        <label class="text-gray-600 text-xs uppercase font-medium">Nomor Telepon</label>
                        <input type="text" :value="telepon" disabled
                               class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 mt-1 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="text-gray-600 text-xs uppercase font-medium">Jumlah Pesanan <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_pesanan" required min="1"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                {{-- Footer --}}
                <div class="px-6 pb-5 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modalTambah')"
                        class="px-5 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-medium">
                        Simpan Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- ================= SCRIPT ================= --}}
    <script>
        // ---- DETAIL ----
        function openModalShow(nama, jumlah, status, tanggal) {
            document.getElementById('show_nama').textContent    = nama || '-';
            document.getElementById('show_jumlah').textContent  = jumlah + ' pack';
            document.getElementById('show_tanggal').textContent = tanggal || '-';

            const badge = document.getElementById('show_status_badge');
            let formattedStatus = status.replace('_', ' ');
            formattedStatus = formattedStatus.charAt(0).toUpperCase() + formattedStatus.slice(1);
            badge.textContent = formattedStatus;

            if (status === 'selesai') {
                badge.className = 'inline-block px-3 py-1.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
            } else if (status === 'proses') {
                badge.className = 'inline-block px-3 py-1.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800';
            } else {
                badge.className = 'inline-block px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
            }

            openModal('modalShow');
        }

        // ---- EDIT ----
        function openModalEdit(id, nama, jumlah, status) {
            const baseUrl = "{{ route('admin.pesanan.update', ':id') }}";
            document.getElementById('formEdit').action = baseUrl.replace(':id', id);

            document.getElementById('edit_nama').value   = nama || '';
            document.getElementById('edit_jumlah').value = jumlah || '';
            document.getElementById('edit_status').value = status || 'menunggu';

            openModal('modalEdit');
        }

        // ---- TAMBAH ----
        function openModalTambah() {
            openModal('modalTambah');
        }

        function pesananForm() {
            return {
                selectedPelanggan: false,
                alamat: '',
                telepon: '',
                pilihPelanggan(event) {
                    const select = event.target;
                    const option = select.options[select.selectedIndex];
                    
                    if (select.value !== '') {
                        this.alamat = option.dataset.alamat || '-';
                        this.telepon = option.dataset.telepon || '-';
                        this.selectedPelanggan = true;
                    } else {
                        this.selectedPelanggan = false;
                        this.alamat = '';
                        this.telepon = '';
                    }
                }
            }
        }

        function openModal(id) {
            const el = document.getElementById(id);
            el.classList.remove('hidden');
            el.classList.add('flex');
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            el.classList.add('hidden');
            el.classList.remove('flex');
        }

        window.addEventListener('click', function(e) {
            ['modalShow', 'modalEdit', 'modalTambah'].forEach(function(id) {
                const el = document.getElementById(id);
                if (e.target === el) closeModal(id);
            });
        });
    </script>
@endsection