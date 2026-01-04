// Pastikan DOM sudah siap sebelum init map
document.addEventListener('DOMContentLoaded', function () {

    /*************************************************
     * FIREBASE CONFIG
     *************************************************/
    const firebaseConfig = {
        apiKey: "AIzaSyDxgGDwbLNCZeAyX3inFjsyG9BvM_Nkiag",
        authDomain: "moyakristal-1a81e.firebaseapp.com",
        projectId: "moyakristal-1a81e",
        storageBucket: "moyakristal-1a81e.firebasestorage.app",
        messagingSenderId: "1001114808948",
        appId: "1:1001114808948:web:c48552264371717b3cd721",
        measurementId: "G-ZL01S1SV7J"
    };

    // Inisialisasi Firebase hanya sekali
    if (!firebase.apps.length) {
        firebase.initializeApp(firebaseConfig);
    }

    const db = firebase.database();

    /*************************************************
     * INIT MAP
     *************************************************/
    const map = L.map('map').setView([-7.6161, 111.5234], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const markers = {};

    const driverIcon = L.icon({
        iconUrl: 'https://cdn-icons-png.flaticon.com/512/854/854866.png',
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -35],
    });

    /*************************************************
     * LISTENER REALTIME
     *************************************************/
    db.ref('pengiriman').on('value', (snapshot) => {
        const data = snapshot.val();

        // Hapus marker lama
        Object.values(markers).forEach(marker => map.removeLayer(marker));
        Object.keys(markers).forEach(key => delete markers[key]);

        if (!data) {
            map.setView([-7.6161, 111.5234], 13);
            return;
        }

        let bounds = [];

        Object.keys(data).forEach(key => {
            const item = data[key];

            if (!item.lat || !item.lng || item.status !== 'proses') return;

            const lat = parseFloat(item.lat);
            const lng = parseFloat(item.lng);
            if (isNaN(lat) || isNaN(lng)) return;

            const marker = L.marker([lat, lng], { icon: driverIcon })
                .addTo(map)
                .bindPopup(`
                    <div class="text-sm">
                        <strong>Driver:</strong> ${item.driver || '-'}<br>
                        <strong>Pelanggan:</strong> ${item.pelanggan || '-'}<br>
                        <strong>Status:</strong> <span class="text-blue-600 font-medium">Dalam Perjalanan</span>
                    </div>
                `);

            markers[key] = marker;
            bounds.push([lat, lng]);
        });

        if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [50, 50] });
        }
    });
});