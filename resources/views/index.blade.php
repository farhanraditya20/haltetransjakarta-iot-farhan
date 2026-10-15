<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Transjakarta IoT</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        #map { height: 100vh; width: 100%; }
    </style>
</head>
<body class="bg-gray-900 text-white m-0 p-0 overflow-hidden">

    <div class="relative w-full h-screen">
        <div id="map"></div>

        <!-- Tombol Filter Koridor -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 z-[1000] flex items-center space-x-2 bg-[#141238]/90 backdrop-blur-md p-2 rounded-full shadow-2xl border border-gray-700">
            <button onclick="filterKoridor('Semua')" class="filter-btn bg-blue-600 text-white px-4 py-1.5 rounded-full text-xs font-semibold transition-all">Semua</button>
            <button onclick="filterKoridor('Koridor 1')" class="filter-btn text-gray-300 px-4 py-1.5 rounded-full text-xs font-semibold hover:bg-white/10 transition-all">Koridor 1</button>
            <button onclick="filterKoridor('Koridor 9')" class="filter-btn text-gray-300 px-4 py-1.5 rounded-full text-xs font-semibold hover:bg-white/10 transition-all">Koridor 9</button>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const haltes = @json($haltes ?? []);

        const map = L.map('map').setView([-6.200000, 106.816666], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        let markerCollection = [];

        haltes.forEach(halte => {
            let kapasitas = halte.kapasitas_maksimal || 100;
            let sekarang = halte.jumlah_sekarang || 0;
            let persentase = (sekarang / kapasitas) * 100;
            
            let warnaDot = persentase <= 50 ? '#22c55e' : (persentase <= 80 ? '#eab308' : '#dc2626');

            let marker = L.circleMarker([halte.latitude, halte.longitude], {
                radius: 9,
                fillColor: warnaDot,
                color: '#ffffff',
                weight: 2,
                fillOpacity: 1
            }).addTo(map);

            // Menggunakan .toUpperCase() agar nama halte konsisten berhuruf kapital
            let namaHalte = halte.nama_halte ? halte.nama_halte.toUpperCase() : 'HALTE';
            marker.bindPopup(`<b>${namaHalte}</b><br>Kapasitas: ${sekarang}/${kapasitas}`);

            markerCollection.push({
                marker: marker,
                koridor: halte.koridor 
            });
        });

        function filterKoridor(pilihKoridor) {
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white');
                btn.classList.add('text-gray-300');
            });
            
            event.target.classList.add('bg-blue-600', 'text-white');
            event.target.classList.remove('text-gray-300');

            markerCollection.forEach(item => {
                let isMatch = false;

                if (pilihKoridor === 'Semua') {
                    isMatch = true;
                } else if (Array.isArray(item.koridor)) {
                    isMatch = item.koridor.includes(pilihKoridor);
                } else {
                    isMatch = item.koridor === pilihKoridor;
                }

                if (isMatch) {
                    if (!map.hasLayer(item.marker)) map.addLayer(item.marker);
                } else {
                    if (map.hasLayer(item.marker)) map.removeLayer(item.marker);
                }
            });
        }
    </script>
</body>
</html>