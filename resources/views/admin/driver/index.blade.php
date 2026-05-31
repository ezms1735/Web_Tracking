@extends('admin.layout')

@section('title', 'Data Driver')

@section('content')

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Data Driver</h1>
        <button onclick="openModalTambah()"
           class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg transition shadow">
            + Tambah Driver
        </button>
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
                        <th class="px-6 py-4 text-center">Email</th>
                        <th class="px-6 py-4">No. Telepon</th>
                        <th class="px-6 py-4 text-center">Password</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($driver as $d)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium">{{ $d->nama_lengkap }}</td>
                        <td class="px-6 py-4">{{ $d->email }}</td>
                        <td class="px-6 py-4">{{ $d->nomor_telepon }}</td>
                        <td class="px-6 py-4 text-center text-gray-400">********</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                {{ $d->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($d->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">

                                <!-- Detail -->
                                <button onclick="openModalShow(
                                        '{{ $d->id }}',
                                        '{{ $d->nama_lengkap }}',
                                        '{{ $d->email }}',
                                        '{{ $d->nomor_telepon }}',
                                        '{{ $d->status }}'
                                    )"
                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                    </svg>
                                    Detail
                                </button>

                                <!-- Edit -->
                                <button onclick="openModalEdit(
                                        '{{ $d->id }}',
                                        '{{ $d->nama_lengkap }}',
                                        '{{ $d->email }}',
                                        '{{ $d->nomor_telepon }}',
                                        '{{ $d->status }}'
                                    )"
                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white bg-amber-500 rounded hover:bg-amber-600 transition">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </button>

                                <!-- Hapus -->
                                <form action="{{ route('admin.driver.destroy', $d->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Hapus driver ini?')">
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
                            Data driver kosong
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    {{-- ================= MODAL DETAIL ================= --}}
    <div id="modalShow" class="fixed inset-0 bg-gray-500/60 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl w-[480px] overflow-hidden shadow-lg" onclick="event.stopPropagation()">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-400 text-white px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button onclick="closeModal('modalShow')" class="hover:opacity-75 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <h2 class="text-lg font-semibold">Detail Driver</h2>
                </div>
            </div>

            {{-- Content --}}
            <div class="p-6 space-y-4 text-sm">

                <div>
                    <label class="text-gray-500 text-xs uppercase font-medium">Nama Lengkap</label>
                    <input id="show_nama" class="w-full border border-gray-200 rounded-lg px-3 py-2 mt-1 bg-gray-50 text-gray-700" readonly>
                </div>

                <div>
                    <label class="text-gray-500 text-xs uppercase font-medium">Email</label>
                    <input id="show_email" class="w-full border border-gray-200 rounded-lg px-3 py-2 mt-1 bg-gray-50 text-gray-700" readonly>
                </div>

                <div>
                    <label class="text-gray-500 text-xs uppercase font-medium">No. Telepon</label>
                    <input id="show_telepon" class="w-full border border-gray-200 rounded-lg px-3 py-2 mt-1 bg-gray-50 text-gray-700" readonly>
                </div>

                <div>
                    <label class="text-gray-500 text-xs uppercase font-medium">Status</label>
                    <div class="mt-1">
                        <span id="show_status_badge" class="px-3 py-1 rounded-full text-xs font-medium"></span>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="px-6 pb-5 flex justify-end">
                <button onclick="closeModal('modalShow')"
                    class="px-5 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                    Tutup
                </button>
            </div>

        </div>
    </div>


    {{-- ================= MODAL EDIT ================= --}}
    <div id="modalEdit" class="fixed inset-0 bg-gray-500/60 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl w-[480px] overflow-hidden shadow-lg" onclick="event.stopPropagation()">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-amber-500 to-amber-400 text-white px-6 py-4 flex items-center gap-3">
                <button onclick="closeModal('modalEdit')" class="hover:opacity-75 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <h2 class="text-lg font-semibold">Edit Driver</h2>
            </div>

            {{-- Form --}}
            <form id="formEdit" method="POST" action="">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-4 text-sm">

                    <div>
                        <label class="text-gray-600 text-xs uppercase font-medium">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" id="edit_nama"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-amber-400"
                            required>
                    </div>

                    <div>
                        <label class="text-gray-600 text-xs uppercase font-medium">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="edit_email"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-amber-400"
                            required>
                    </div>

                    <div>
                        <label class="text-gray-600 text-xs uppercase font-medium">No. Telepon <span class="text-red-500">*</span></label>
                        <input type="text" name="nomor_telepon" id="edit_telepon"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-amber-400"
                            required>
                    </div>

                    <div>
                        <label class="text-gray-600 text-xs uppercase font-medium">Password Baru <span class="text-gray-400">(kosongkan jika tidak diubah)</span></label>
                        <input type="password" name="password" id="edit_password"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-amber-400">
                    </div>

                    <div>
                        <label class="text-gray-600 text-xs uppercase font-medium">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="edit_status"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-amber-400">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
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
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>


    {{-- ================= MODAL TAMBAH ================= --}}
    <div id="modalTambah" class="fixed inset-0 bg-gray-500/60 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl w-[480px] overflow-hidden shadow-lg" onclick="event.stopPropagation()">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 text-white px-6 py-4 flex items-center gap-3">
                <button onclick="closeModal('modalTambah')" class="hover:opacity-75 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <h2 class="text-lg font-semibold">Tambah Driver</h2>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('admin.driver.store') }}">
                @csrf

                <div class="p-6 space-y-4 text-sm">

                    <div>
                        <label class="text-gray-600 text-xs uppercase font-medium">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Masukkan nama lengkap"
                            required>
                    </div>

                    <div>
                        <label class="text-gray-600 text-xs uppercase font-medium">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Masukkan email"
                            required>
                    </div>

                    <div>
                        <label class="text-gray-600 text-xs uppercase font-medium">No. Telepon <span class="text-red-500">*</span></label>
                        <input type="text" name="nomor_telepon"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Masukkan nomor telepon"
                            required>
                    </div>

                    <div>
                        <label class="text-gray-600 text-xs uppercase font-medium">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Masukkan password"
                            required>
                    </div>

                    <div>
                        <label class="text-gray-600 text-xs uppercase font-medium">Status <span class="text-red-500">*</span></label>
                        <select name="status"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
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
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>


    {{-- ================= SCRIPT ================= --}}
    <script>
        // ---- DETAIL ----
        function openModalShow(id, nama, email, telepon, status) {
            document.getElementById('show_nama').value    = nama    || '-';
            document.getElementById('show_email').value   = email   || '-';
            document.getElementById('show_telepon').value = telepon || '-';

            const badge = document.getElementById('show_status_badge');
            if (status === 'aktif') {
                badge.textContent = 'Aktif';
                badge.className = 'px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700';
            } else {
                badge.textContent = 'Nonaktif';
                badge.className = 'px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700';
            }

            openModal('modalShow');
        }

        // ---- EDIT ----
        function openModalEdit(id, nama, email, telepon, status) {
            const baseUrl = "{{ route('admin.driver.update', ':id') }}";
            document.getElementById('formEdit').action = baseUrl.replace(':id', id);

            document.getElementById('edit_nama').value     = nama    || '';
            document.getElementById('edit_email').value    = email   || '';
            document.getElementById('edit_telepon').value  = telepon || '';
            document.getElementById('edit_password').value = '';
            document.getElementById('edit_status').value   = status  || 'aktif';

            openModal('modalEdit');
        }

        // ---- TAMBAH ----
        function openModalTambah() {
            openModal('modalTambah');
        }

        // ---- HELPERS ----
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