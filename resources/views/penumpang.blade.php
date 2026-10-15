<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live MAP TJ - IoT Command Center</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Open Sauce Sans Font -->
    <link href="https://fonts.cdnfonts.com/css/open-sauce-sans" rel="stylesheet">
    <style>
        body { font-family: 'Open Sauce Sans', sans-serif; }
        #map { height: 100%; width: 100%; z-index: 1; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0b0d2d; }
        ::-webkit-scrollbar-thumb { background: #374151; }

        /* Pengaturan Transisi Sidebar */
        #sidebarPanel {
            transition: all 0.3s ease-in-out;
        }
        
        /* Kondisi saat Sidebar Ditutup */
        #sidebarPanel.closed {
            transform: translateX(100%);
            width: 0 !important;
            padding: 0 !important;
            overflow: hidden;
            border-left: none;
        }
    </style>
</head>
<body class="bg-[#1e1b4b] h-screen w-screen overflow-hidden flex flex-col lg:flex-row m-0 p-0 relative">

    <!-- BAGIAN KIRI: PETA UTAMA -->
    <div class="flex-1 relative h-full">
        <div id="map"></div>
        
        <!-- Logo Floating Overlay -->
        <div class="absolute top-6 left-6 z-[1000] pointer-events-none flex items-start" style="filter: drop-shadow(0px 0px 10px rgba(255,255,255,0.9)) drop-shadow(1px 2px 3px rgba(0,0,0,0.2));">
            <div class="text-[#1e1b4b] font-bold text-6xl md:text-7xl tracking-tight leading-none">Live</div>
            <div class="flex flex-col ml-1">
                <div class="text-[#f97316] font-bold text-6xl md:text-7xl tracking-tight leading-none border-b-[4px] border-[#f97316] pb-1">MAP</div>
                <div class="text-[#1e1b4b] font-semibold text-sm md:text-base mt-1 tracking-normal">TransJakarta</div>
            </div>
        </div>

        <!-- Tombol Lokasi Saya -->
        <button id="btnMyLocation" title="Lokasi Saya" class="absolute top-36 left-6 z-[1000] bg-[#0a0a2a] p-3 rounded-full shadow-2xl border border-gray-700 hover:bg-[#1e1b4b] transition-all focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#86e2f5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v2m0 12v2m8-8h-2m-12 0H4m16 0a8 8 0 11-16 0 8 8 0 0116 0z" />
            </svg>
        </button>

        <!-- Tombol Kompas Kecil -->
        <button id="btnCompass" title="Reset Kompas" class="absolute top-52 left-6 z-[1000] bg-[#0a0a2a] p-2.5 rounded-full shadow-2xl border border-gray-700 hover:bg-[#1e1b4b] transition-all focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#86e2f5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9-7-9-7-9 7 9 7z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 12v7" />
            </svg>
        </button>

        <!-- BAGIAN TENGAH BAWAH: Tombol Search Bus Stop & Feedback -->
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-[1000] flex flex-row items-center space-x-3">
            <button id="btnOpenSidebar" class="bg-[#141238] text-gray-300 hover:text-white px-6 py-3.5 rounded-full shadow-2xl border border-gray-700 hover:bg-[#1e1b4b] transition-all focus:outline-none flex items-center space-x-3 w-56 justify-between">
                <span class="text-sm font-semibold tracking-wide text-gray-300">Search Bus Stop</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#86e2f5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>

            <button id="btnFeedback" onclick="alert('Fitur Feedback segera hadir!')" class="bg-[#141238] text-gray-300 hover:text-white px-6 py-3.5 rounded-full shadow-2xl border border-gray-700 hover:bg-[#1e1b4b] transition-all focus:outline-none flex items-center space-x-3 w-56 justify-between">
                <span class="text-sm font-semibold tracking-wide text-gray-300">Feedback</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#86e2f5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
            </button>
        </div>
    </div>

    <!-- BAGIAN KANAN: SIDEBAR PANEL -->
    <div id="sidebarPanel" class="w-full lg:w-[420px] bg-[#141238] h-full p-8 flex flex-col justify-center shadow-[-10px_0_20px_rgba(0,0,0,0.3)] z-[1000] relative border-l border-gray-800">
        
        <!-- Tombol Tutup Sidebar -->
        <button id="btnCloseSidebar" title="Tutup Panel" class="absolute top-6 left-6 text-gray-400 hover:text-white transition-colors focus:outline-none z-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Placeholder State Kosong -->
        <div id="emptyState" class="border-2 border-dashed border-[#2e2b5e] rounded-2xl p-6 mb-10 flex flex-col items-center justify-center h-[260px] text-center text-[#86e2f5]/60 transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
            </svg>
            <p class="font-semibold text-lg">Pilih titik halte di peta untuk melihat detail kepadatan</p>
        </div>

        <!-- White Info Card -->
        <div id="infoCard" class="relative bg-white text-gray-900 rounded-2xl p-6 shadow-xl transition-all duration-300 mb-10 hidden min-h-[260px] flex flex-col justify-center">
            
            <button id="btnCloseInfo" class="absolute top-4 right-4 text-gray-400 hover:text-gray-900 transition-colors focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h2 id="infoNama" class="text-3xl font-black mb-6 tracking-tight text-center px-4">Pilih Halte</h2>
            <div class="space-y-4 text-center font-medium text-gray-700">
                <p id="infoTerisi" class="text-xl">Terisi: - / -</p>
                <div>
                    <p class="text-xl mb-2">Kepadatan:</p>
                    <div id="infoPersen" class="text-6xl font-black transition-colors duration-300">-</div>
                </div>
            </div>
        </div>

        <!-- Bagian Bawah: Kolom Pencarian -->
        <div class="relative w-full mt-4">
            <input type="text" id="searchInput" autocomplete="off" placeholder="Search Bus Stop" 
                   class="w-full bg-[#0a0a2a] text-white text-center text-lg font-semibold py-4 px-6 rounded-full border-transparent focus:border-transparent focus:ring-0 focus:outline-none placeholder-[#6b7280] transition-all duration-300"
                   style="box-shadow: 0 0 15px rgba(255, 255, 255, 0.6); border: none !important; outline: none !important;">
            
            <ul id="searchResults" class="absolute bottom-full mb-4 w-full bg-[#0a0a2a] text-white border border-gray-700 rounded-2xl hidden shadow-2xl max-h-48 overflow-y-auto z-[1001]">
            </ul>
        </div>

    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var map = L.map('map', { zoomControl: true }).setView([-6.2000, 106.8166], 12);
        map.zoomControl.setPosition('topright');

        L.tileLayer('https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            attribution: '© Google Maps'
        }).addTo(map);

        const haltes = @json($haltes);
        const markerList = {};

        const emptyState = document.getElementById('emptyState');
        const infoCard = document.getElementById('infoCard');
        const infoNama = document.getElementById('infoNama');
        const infoTerisi = document.getElementById('infoTerisi');
        const infoPersen = document.getElementById('infoPersen');
        const btnCloseInfo = document.getElementById('btnCloseInfo');
        
        const sidebarPanel = document.getElementById('sidebarPanel');
        const btnOpenSidebar = document.getElementById('btnOpenSidebar');
        const btnCloseSidebar = document.getElementById('btnCloseSidebar');
        const btnCompass = document.getElementById('btnCompass');

        btnOpenSidebar.addEventListener('click', function() {
            sidebarPanel.classList.remove('closed');
            setTimeout(() => { map.invalidateSize(); }, 300);
            setTimeout(() => { document.getElementById('searchInput').focus(); }, 300);
        });

        btnCloseSidebar.addEventListener('click', function() {
            sidebarPanel.classList.add('closed');
            setTimeout(() => { map.invalidateSize(); }, 300);
        });

        btnCompass.addEventListener('click', function() {
            map.flyTo([-6.2000, 106.8166], 12, { duration: 1.2 });
            btnCompass.classList.add('animate-pulse');
            setTimeout(() => btnCompass.classList.remove('animate-pulse'), 1000);
        });

        btnCloseInfo.addEventListener('click', function() {
            infoCard.classList.add('hidden');
            emptyState.classList.remove('hidden');
        });

        function tampilkanInfoHalte(halte) {
            emptyState.classList.add('hidden');
            infoCard.classList.remove('hidden');

            let kapasitas = halte.kapasitas || 250;
            let current = halte.jumlah_sekarang || 0;
            let persentase = Math.round((current / kapasitas) * 100);
            let warnaHex = persentase <= 50 ? '#16a34a' : (persentase <= 80 ? '#ca8a04' : '#dc2626');

            infoNama.textContent = halte.nama;
            infoTerisi.innerHTML = `Terisi: <b>${current}</b> / ${kapasitas}`;
            infoPersen.textContent = `${persentase}%`;
            infoPersen.style.color = warnaHex;

            if (sidebarPanel.classList.contains('closed')) {
                sidebarPanel.classList.remove('closed');
                setTimeout(() => { map.invalidateSize(); }, 300);
            }
        }

        haltes.forEach(halte => {
            let lat = parseFloat(halte.lat);
            let lng = parseFloat(halte.lng);
            if (isNaN(lat) || isNaN(lng)) return;

            let kapasitas = halte.kapasitas || 250;
            let current = halte.jumlah_sekarang || 0;
            let persentase = (current / kapasitas) * 100;
            let warnaDot = persentase <= 50 ? '#22c55e' : (persentase <= 80 ? '#eab308' : '#dc2626');

            let marker = L.circleMarker([lat, lng], {
                radius: 7,
                fillColor: warnaDot,
                color: '#ffffff',
                weight: 1.5,
                opacity: 1,
                fillOpacity: 1
            }).addTo(map);

            marker.on('click', function() {
                tampilkanInfoHalte(halte);
                map.flyTo([lat, lng], 15, { duration: 1.2 });
            });

            markerList[halte.nama.toLowerCase()] = {
                marker: marker,
                halteData: halte
            };
        });

        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');

        searchInput.addEventListener('input', function() {
            const keyword = this.value.toLowerCase();
            searchResults.innerHTML = '';
            if (!keyword) { searchResults.classList.add('hidden'); return; }

            const filteredHaltes = haltes.filter(h => h.nama.toLowerCase().includes(keyword));
            if (filteredHaltes.length > 0) {
                searchResults.classList.remove('hidden');
                filteredHaltes.forEach(h => {
                    const li = document.createElement('li');
                    li.className = 'p-3 hover:bg-[#1e1b4b] cursor-pointer border-b border-gray-800 last:border-b-0 transition-colors text-sm';
                    li.textContent = h.nama;
                    li.onclick = () => {
                        const target = markerList[h.nama.toLowerCase()];
                        if (target) {
                            map.flyTo([target.halteData.lat, target.halteData.lng], 16, { duration: 1.5 });
                            tampilkanInfoHalte(target.halteData);
                        }
                        searchInput.value = h.nama;
                        searchResults.classList.add('hidden');
                    };
                    searchResults.appendChild(li);
                });
            } else {
                searchResults.classList.add('hidden');
            }
        });

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });

        let userMarker = null;
        const btnMyLocation = document.getElementById('btnMyLocation');

        btnMyLocation.addEventListener('click', function() {
            map.locate({setView: true, maxZoom: 16});
            btnMyLocation.classList.add('animate-pulse');
            setTimeout(() => btnMyLocation.classList.remove('animate-pulse'), 1000);
        });

        map.on('locationfound', function(e) {
            if (userMarker) { map.removeLayer(userMarker); }
            userMarker = L.circleMarker(e.latlng, {
                radius: 9, fillColor: "#3b82f6", color: "#ffffff", weight: 3, opacity: 1, fillOpacity: 1
            }).addTo(map);
            userMarker.bindPopup('<div style="text-align:center; color:#1e1b4b;"><b>📍 Posisi Kamu</b></div>').openPopup();
        });

        map.on('locationerror', function() {
            alert("Gagal mendeteksi lokasi.");
        });

        setInterval(() => {
            haltes.forEach(halte => {
                let kapasitas = halte.kapasitas || 250;
                let perubahan = Math.floor(Math.random() * 26) - 10;
                let hasilBaru = halte.jumlah_sekarang + perubahan;
                if (hasilBaru < 0) hasilBaru = 0;
                if (hasilBaru > kapasitas) hasilBaru = kapasitas;
                halte.jumlah_sekarang = hasilBaru;

                let persentase = (halte.jumlah_sekarang / kapasitas) * 100;
                let warnaDot = persentase <= 50 ? '#22c55e' : (persentase <= 80 ? '#eab308' : '#dc2626');

                const target = markerList[halte.nama.toLowerCase()];
                if (target) {
                    target.marker.setStyle({ fillColor: warnaDot });
                }
            });
        }, 5000);
    </script>
</body>
</html>