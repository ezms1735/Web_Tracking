@extends('admin.layout')

@section('title', 'Pemantauan Pengiriman')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Pemantauan Pengiriman Hari Ini</h1>

    {{-- PETA KHUSUS MADIUN, MAGETAN, PONOROGO --}}
    <div id="map" class="w-full h-96 rounded-lg shadow-lg mb-8"></div>

    @if($pengirimanHariIni->isEmpty())
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-8 text-center">
            <p class="text-blue-800 text-lg">
                Belum ada pengiriman hari ini.
            </p>
            <p class="text-blue-600 mt-2">
                Semua driver sedang istirahat atau belum ada tugas baru.
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" style="align-items: start;">
            @foreach($pengirimanHariIni as $driver_id => $grupPengiriman)
                @php
                    $driver = $grupPengiriman->first()->driver;
                    $totalTugasHariIni = $grupPengiriman->count();
                    $belumSelesai = $grupPengiriman->whereNull('waktu_selesai')->count();
                    $sudahSelesai = $totalTugasHariIni - $belumSelesai;
                    $statusUmum = $grupPengiriman->whereNull('waktu_selesai')->pluck('status_pengiriman')->unique()->implode(', ');
                @endphp

                <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-200">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-4 text-white">
                        <div class="flex items-center gap-3">
                            <div class="text-3xl">🚚</div>
                            <div class="flex-grow min-w-0">
                                <h3 class="text-lg font-bold truncate">{{ $driver->nama_lengkap ?? 'Driver Tidak Diketahui' }}</h3>
                                <p class="text-xs opacity-90">{{ $driver->nomor_telepon ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 text-sm">
                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Total Tugas Hari Ini</span>
                                <span class="font-bold text-base">{{ $totalTugasHariIni }} pengiriman</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Status Saat Ini</span>
                                <div class="text-right">
                                    <span class="block text-xs text-gray-500">{{ $belumSelesai }} sedang berjalan</span>
                                    <span class="block text-xs text-green-600 font-medium">{{ $sudahSelesai }} sudah selesai</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Aktivitas Terakhir</span>
                                <span class="text-xs text-gray-700">
                                    {{ $grupPengiriman->first()->waktu_mulai->format('H:i') }} - sekarang
                                </span>
                            </div>
                        </div>

                        <details class="border-t border-gray-200 pt-3">
                            <summary class="cursor-pointer text-blue-600 text-sm font-medium hover:underline list-none flex justify-between items-center">
                                Lihat Detail Tugas ({{ $totalTugasHariIni }})
                                <span class="text-xs transition-transform duration-200 details-arrow">▼</span>
                            </summary>
                            <div class="mt-3 space-y-3">
                                @foreach($grupPengiriman as $pengiriman)
                                    @php
                                        $statusTugas = $pengiriman->waktu_selesai ? 'Selesai' : ($pengiriman->status_pengiriman == 'proses' ? 'Sedang Dikirim' : 'Belum Berangkat');
                                        $warnaBorder = $pengiriman->waktu_selesai ? 'border-green-500' : 'border-blue-500';
                                    @endphp
                                    <div class="bg-gray-50 rounded-md p-3 border-l-4 {{ $warnaBorder }}">
                                        <div class="font-medium text-sm flex justify-between">
                                            <span>{{ $pengiriman->pesanan->pelanggan->nama_lengkap ?? 'Pelanggan' }}</span>
                                            <span class="text-xs font-medium {{ $pengiriman->waktu_selesai ? 'text-green-700' : 'text-orange-700' }}">
                                                {{ $statusTugas }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-600 mt-1">
                                            {{ $pengiriman->pesanan->jumlah_pesanan }} pack
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </details>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<script>
    const firebaseConfig = {
        apiKey: "AIzaSyDxgGDwbLNCZeAyX3inFjsyG9BvM_Nkiag",
        authDomain: "moyakristal-1a81e.firebaseapp.com",
        databaseURL: "https://moyakristal-1a81e-default-rtdb.asia-southeast1.firebasedatabase.app",
        projectId: "moyakristal-1a81e",
        storageBucket: "moyakristal-1a81e.firebasestorage.app",
        messagingSenderId: "1001114808948",
        appId: "1:1001114808948:web:c48552264371717b3cd721"
    };

    firebase.initializeApp(firebaseConfig);
    const db = firebase.database();

    var map = L.map('map').setView([-7.629, 111.52], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
    }).addTo(map);

    var driverMarkers = {};
    var driverInfo = {};

    var initialData = @json($markers);

    initialData.forEach(function(item) {
        var driverId = item.id || String(item.nama).replace(/\D/g, '') || 'unknown';

        if (item.lat && item.lng) {
            var marker = L.marker([item.lat, item.lng])
                .addTo(map)
                .bindPopup(`
                    <b>${item.nama}</b><br>
                    Sedang mengantar ${item.jumlah} tugas<br>
                    <small>Loading realtime...</small>
                `);

            driverMarkers[driverId] = marker;
            driverInfo[driverId] = {
                nama: item.nama,
                jumlah: item.jumlah
            };
        }
    });

    if (Object.keys(driverMarkers).length > 0) {
        var group = L.featureGroup(Object.values(driverMarkers));
        map.fitBounds(group.getBounds().pad(0.4));
    }

    const driversRef = db.ref('drivers');

    driversRef.on('value', (snapshot) => {
        const drivers = snapshot.val() || {};

        Object.keys(drivers).forEach(driverId => {
            const data = drivers[driverId];
            if (!data?.latitude || !data?.longitude) return;

            const lat = parseFloat(data.latitude);
            const lng = parseFloat(data.longitude);
            if (isNaN(lat) || isNaN(lng)) return;

            const info = driverInfo[driverId] || { nama: `Driver ${driverId}`, jumlah: '?' };
            const timeStr = data.updated_at 
                ? new Date(data.updated_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute:'2-digit'})
                : 'unknown';

            const popup = `
                <b>${info.nama}</b><br>
                Sedang mengantar ${info.jumlah} tugas<br>
                <small>Update: ${timeStr}</small>
            `;

            if (driverMarkers[driverId]) {
                driverMarkers[driverId].setLatLng([lat, lng]);
                driverMarkers[driverId].setPopupContent(popup);
            } else {
                const marker = L.marker([lat, lng])
                    .addTo(map)
                    .bindPopup(popup);
                driverMarkers[driverId] = marker;
                driverInfo[driverId] = info;
            }
        });

        // Hapus marker yang sudah tidak ada di Firebase (opsional)
        Object.keys(driverMarkers).forEach(id => {
            if (!drivers[id]) {
                map.removeLayer(driverMarkers[id]);
                delete driverMarkers[id];
                delete driverInfo[id];
            }
        });
    }, (error) => {
        console.error('Firebase realtime error:', error);
    });
</script>

<style>
    details[open] .details-arrow {
        transform: rotate(180deg);
    }
</style>
@endsection