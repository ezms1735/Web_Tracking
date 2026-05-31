@extends('admin.layout')

@section('title', 'Data Pengiriman')

@section('content')

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Data Pengiriman</h1>
        
        <button type="button" onclick="openModalTambah()"
           class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg transition shadow ml-80">
            + Buat Pengiriman
        </button>

        {{-- Form Filter --}}
        <form method="GET" class="flex flex-wrap items-center gap-2 bg-white p-2 rounded-xl border border-gray-200 shadow-sm">
            {{-- Tanggal --}}
            <select name="tanggal"
                    class="bg-transparent border border-gray-300 rounded-lg text-gray-700 text-sm font-medium focus:ring-0 cursor-pointer px-3 py-1.5">
                <option value="">Semua Tanggal</option>
                @for ($i = 1; $i <= 31; $i++)
                    <option value="{{ $i }}" {{ request('tanggal') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>

            {{-- Bulan --}}
            <select name="bulan"
                    class="bg-transparent border border-gray-300 rounded-lg text-gray-700 text-sm font-medium focus:ring-0 cursor-pointer px-3 py-1.5">
                <option value="">Semua Bulan</option>
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create(null, $i)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>

            {{-- Tahun --}}
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
                        <th class="px-6 py-4 text-center">Alamat</th>
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
                        <td class="px-6 py-4 font-medium">{{ $p->pesanan->pelanggan->nama_lengkap ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $p->pesanan->pelanggan->alamat ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $p->driver->nama_lengkap ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $p->driver->nomor_telepon ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">{{ $p->pesanan->jumlah_pesanan ?? 0 }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-3 py-1.5 rounded-full text-xs font-medium
                                {{ $p->status_pengiriman == 'selesai'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($p->status_pengiriman) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">

                                {{-- Detail Modal Trigger (Menggunakan backticks untuk mencegah error baris baru) --}}
                                <button type="button" onclick="openModalShow(
                                        '{{ $p->id }}',
                                        '{{ $p->status_pengiriman }}',
                                        '{{ $p->waktu_mulai ? $p->waktu_mulai->format('d M Y H:i') : '-' }}',
                                        '{{ $p->waktu_selesai ? $p->waktu_selesai->format('d M Y H:i') : '-' }}',
                                        '{{ $p->pesanan->jumlah_pesanan ?? 0 }}',
                                        `{{ addslashes($p->pesanan->pelanggan->nama_lengkap ?? '-') }}`,
                                        `{{ addslashes($p->pesanan->pelanggan->alamat ?? '-') }}`,
                                        '{{ $p->pesanan->pelanggan->nomor_telepon ?? '-' }}',
                                        `{{ addslashes($p->driver->nama_lengkap ?? '-') }}`,
                                        '{{ $p->driver->nomor_telepon ?? '-' }}'
                                    )"
                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                    </svg>
                                    Detail
                                </button>

                                {{-- Edit Modal Trigger --}}
                                <button type="button" onclick="openModalEdit(
                                        '{{ $p->id }}',
                                        '{{ $p->status_pengiriman }}'
                                    )"
                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white bg-amber-500 rounded hover:bg-amber-600 transition">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </button>

                                {{-- Hapus Form --}}
                                <form action="{{ route('admin.pengiriman.destroy', $p->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus pengiriman ini?')">
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
                        <td colspan="7" class="text-center py-10 text-gray-500">
                            Data pengiriman kosong
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pengiriman->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>


    {{-- ================= MODAL DETAIL ================= --}}
    <div id="modalShow" class="fixed inset-0 bg-gray-500/60 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl w-[600px] overflow-hidden shadow-lg">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4 flex items-center gap-3">
                <button type="button" onclick="closeModal('modalShow')" class="hover:opacity-75 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <h2 class="text-lg font-semibold">Detail Pengiriman</h2>
            </div>

            {{-- Content --}}
            <div class="p-6 grid grid-cols-2 gap-4 text-sm">
                {{-- Kolom Kiri: Info Pengiriman --}}
                <div class="space-y-3">
                    <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wide">Informasi Pengiriman</p>
                    <div>
                        <label class="text-gray-500 text-xs uppercase font-medium">Status</label>
                        <div class="mt-1">
                            <span id="show_status_badge" class="px-3 py-1 rounded-full text-xs font-medium"></span>
                        </div>
                    </div>
                    <div>
                        <label class="text-gray-500 text-xs uppercase font-medium">Jumlah Barang</label>
                        <input id="show_jumlah" class="w-full border border-gray-200 rounded-lg px-3 py-2 mt-1 bg-gray-50 text-gray-700" readonly>
                    </div>
                    <div>
                        <label class="text-gray-500 text-xs uppercase font-medium">Waktu Mulai</label>
                        <input id="show_waktu_mulai" class="w-full border border-gray-200 rounded-lg px-3 py-2 mt-1 bg-gray-50 text-gray-700" readonly>
                    </div>
                    <div>
                        <label class="text-gray-500 text-xs uppercase font-medium">Waktu Selesai</label>
                        <input id="show_waktu_selesai" class="w-full border border-gray-200 rounded-lg px-3 py-2 mt-1 bg-gray-50 text-gray-700" readonly>
                    </div>
                </div>

                {{-- Kolom Kanan: Pelanggan & Driver --}}
                <div class="space-y-3">
                    <p class="text-xs font-semibold text-teal-600 uppercase tracking-wide">Pelanggan</p>
                    <div>
                        <label class="text-gray-500 text-xs uppercase font-medium">Nama</label>
                        <input id="show_pelanggan_nama" class="w-full border border-gray-200 rounded-lg px-3 py-2 mt-1 bg-gray-50 text-gray-700" readonly>
                    </div>
                    <div>
                        <label class="text-gray-500 text-xs uppercase font-medium">Alamat</label>
                        <textarea id="show_pelanggan_alamat" rows="2"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 mt-1 bg-gray-50 text-gray-700 resize-none" readonly></textarea>
                    </div>
                    <div>
                        <label class="text-gray-500 text-xs uppercase font-medium">No. Telepon</label>
                        <input id="show_pelanggan_telp" class="w-full border border-gray-200 rounded-lg px-3 py-2 mt-1 bg-gray-50 text-gray-700" readonly>
                    </div>

                    <p class="text-xs font-semibold text-amber-600 uppercase tracking-wide pt-1">Driver</p>
                    <div>
                        <label class="text-gray-500 text-xs uppercase font-medium">Nama Driver</label>
                        <input id="show_driver_nama" class="w-full border border-gray-200 rounded-lg px-3 py-2 mt-1 bg-gray-50 text-gray-700" readonly>
                    </div>
                    <div>
                        <label class="text-gray-500 text-xs uppercase font-medium">No. Telepon Driver</label>
                        <input id="show_driver_telp" class="w-full border border-gray-200 rounded-lg px-3 py-2 mt-1 bg-gray-50 text-gray-700" readonly>
                    </div>
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
        <div class="bg-white rounded-2xl w-[380px] overflow-hidden shadow-lg">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-amber-500 to-amber-400 text-white px-6 py-4 flex items-center gap-3">
                <button type="button" onclick="closeModal('modalEdit')" class="hover:opacity-75 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <h2 class="text-lg font-semibold">Edit Status Pengiriman</h2>
            </div>
            {{-- Form --}}
            <form id="formEdit" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4 text-sm">
                    <div>
                        <label class="text-gray-600 text-xs uppercase font-medium">Status Pengiriman <span class="text-red-500">*</span></label>
                        <select name="status_pengiriman" id="edit_status"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-amber-400">
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
                        Update
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
                <h2 class="text-lg font-semibold">Buat Pengiriman Baru</h2>
            </div>
            
            {{-- Form Alpine (DI PERBARUI BAGIAN @change="pilih...($event)") --}}
            <form action="{{ route('admin.pengiriman.store') }}" method="POST" x-data="pengirimanForm()">
                @csrf
                <div class="p-6 space-y-4 text-sm max-h-[70vh] overflow-y-auto">
                    
                    <!-- Pilih Pesanan -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Pesanan <span class="text-red-500">*</span></label>
                        <select name="pesanan_id" @change="pilihPesanan($event)" required
                                class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Pesanan --</option>
                            @if(isset($pesanan))
                                @foreach($pesanan as $p)
                                    <option value="{{ $p->id }}"
                                            data-pelanggan="{{ $p->pelanggan->nama_lengkap ?? '-' }}"
                                            data-alamat="{{ $p->pelanggan->alamat ?? '-' }}"
                                            data-jumlah="{{ $p->jumlah_pesanan ?? 0 }}">
                                        {{ $p->pelanggan->nama_lengkap ?? '-' }} - {{ $p->jumlah_pesanan ?? 0 }} pack
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div x-show="pesananTerpilih" style="display: none;">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nama Pelanggan</label>
                        <input type="text" :value="namaPelanggan" disabled
                               class="w-full px-3 py-2.5 text-sm bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed">
                    </div>
                    <div x-show="pesananTerpilih" style="display: none;">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Alamat Pelanggan</label>
                        <input type="text" :value="alamatPelanggan" disabled
                               class="w-full px-3 py-2.5 text-sm bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed">
                    </div>
                    <div x-show="pesananTerpilih" style="display: none;">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Pengiriman</label>
                        <input type="text" :value="jumlah + ' pack'" disabled
                               class="w-full px-3 py-2.5 text-sm bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed">
                    </div>

                    <hr class="my-3 border-gray-200">

                    <!-- Pilih Driver -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Driver <span class="text-red-500">*</span></label>
                        <select name="driver_id" @change="pilihDriver($event)" required
                                class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Driver --</option>
                            @if(isset($driver))
                                @foreach($driver as $d)
                                    <option value="{{ $d->id }}"
                                            data-telepon="{{ $d->nomor_telepon ?? '-' }}">
                                        {{ $d->nama_lengkap }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div x-show="driverTerpilih" style="display: none;">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nomor Telepon Driver</label>
                        <input type="text" :value="teleponDriver" disabled
                               class="w-full px-3 py-2.5 text-sm bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed">
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modalTambah')"
                        class="px-5 py-2 text-sm bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition font-medium">
                        Buat Pengiriman
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- ================= SCRIPT ================= --}}
    <script>
        // ---- DETAIL ----
        function openModalShow(id, status, waktuMulai, waktuSelesai, jumlah, pelangganNama, pelangganAlamat, pelangganTelp, driverNama, driverTelp) {
            const badge = document.getElementById('show_status_badge');
            if (status === 'selesai') {
                badge.textContent = 'Selesai';
                badge.className = 'px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700';
            } else {
                badge.textContent = 'Proses';
                badge.className = 'px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700';
            }

            document.getElementById('show_jumlah').value           = jumlah       ? jumlah + ' pack' : '-';
            document.getElementById('show_waktu_mulai').value      = waktuMulai   || '-';
            document.getElementById('show_waktu_selesai').value    = waktuSelesai || '-';
            document.getElementById('show_pelanggan_nama').value   = pelangganNama  || '-';
            document.getElementById('show_pelanggan_alamat').value = pelangganAlamat || '-';
            document.getElementById('show_pelanggan_telp').value   = pelangganTelp  || '-';
            document.getElementById('show_driver_nama').value      = driverNama   || '-';
            document.getElementById('show_driver_telp').value      = driverTelp   || '-';

            openModal('modalShow');
        }

        // ---- EDIT ----
        function openModalEdit(id, status) {
            const baseUrl = "{{ route('admin.pengiriman.update', ':id') }}";
            document.getElementById('formEdit').action = baseUrl.replace(':id', id);
            document.getElementById('edit_status').value = status || 'proses';
            openModal('modalEdit');
        }

        // ---- TAMBAH ----
        function openModalTambah() {
            openModal('modalTambah');
        }

        function pengirimanForm() {
            return {
                pesananTerpilih: false,
                namaPelanggan: '',
                alamatPelanggan: '',
                jumlah: '',

                driverTerpilih: false,
                teleponDriver: '',

                pilihPesanan(event) {
                    const select = event.target;
                    const option = select.options[select.selectedIndex];
                    
                    if (select.value !== '') {
                        this.namaPelanggan   = option.dataset.pelanggan || '-';
                        this.alamatPelanggan = option.dataset.alamat || '-';
                        this.jumlah          = option.dataset.jumlah || '0';
                        this.pesananTerpilih = true;
                    } else {
                        this.pesananTerpilih = false;
                        this.namaPelanggan   = '';
                        this.alamatPelanggan = '';
                        this.jumlah          = '';
                    }
                },

                pilihDriver(event) {
                    const select = event.target;
                    const option = select.options[select.selectedIndex];
                    
                    if (select.value !== '') {
                        this.teleponDriver  = option.dataset.telepon || '-';
                        this.driverTerpilih = true;
                    } else {
                        this.driverTerpilih = false;
                        this.teleponDriver  = '';
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