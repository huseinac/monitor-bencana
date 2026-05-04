<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
    <title>Monitoring Penanganan Bencana — Sumatera & Aceh</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<!-- MAP -->
<div id="map"></div>

<!-- TOP BAR -->
<div id="topbar">
    <div class="brand">
        <div>
            <div class="brand-title">Monitoring Bencana</div>
            <div class="brand-sub">Sumatera &amp; Aceh — KSP</div>
        </div>
    </div>
    <div class="divider-v"></div>
    <div id="prov-filters">
        <button class="prov-btn active" onclick="filterProv(this,'all')">Semua Provinsi</button>
        <button class="prov-btn" onclick="filterProv(this,'aceh')">Aceh</button>
        <button class="prov-btn" onclick="filterProv(this,'sumatera_utara')">Sumatera Utara</button>
        <button class="prov-btn" onclick="filterProv(this,'sumatera_barat')">Sumatera Barat</button>
    </div>
    <div class="divider-v"></div>
    <div class="status-chip ongoing"><span class="dot"></span>Penanganan Berlangsung</div>
    <div class="ksp">Update: 10 Feb 2026<br>Kementrian Dalam Negeri</div>
</div>

<!-- CATEGORY SIDEBAR -->
<div id="cat-sidebar">
    <div class="cat-btn" data-cat="pemerintahan" onclick="selectCat(this)" onmouseenter="showTip(event,'Fasilitas Pemerintahan')" onmouseleave="hideTip()">
        <span class="cat-icon">🏛️</span>
        <span class="cat-label">Pemerintahan</span>
    </div>
    <div class="cat-btn active" data-cat="publik" onclick="selectCat(this)" onmouseenter="showTip(event,'Layanan Publik')" onmouseleave="hideTip()">
        <span class="cat-icon">🏥</span>
        <span class="cat-label">Publik</span>
    </div>
    <div class="cat-btn" data-cat="akses_darat" onclick="selectCat(this)" onmouseenter="showTip(event,'Akses Darat')" onmouseleave="hideTip()">
        <span class="cat-icon">🚧</span>
        <span class="cat-label">Akses Darat</span>
    </div>
    <div class="cat-btn" data-cat="ekonomi" onclick="selectCat(this)" onmouseenter="showTip(event,'Sektor Ekonomi')" onmouseleave="hideTip()">
        <span class="cat-icon">💰</span>
        <span class="cat-label">Ekonomi</span>
    </div>
    <div class="cat-btn" data-cat="sosial" onclick="selectCat(this)" onmouseenter="showTip(event,'Sektor Sosial')" onmouseleave="hideTip()">
        <span class="cat-icon">🧑</span>
        <span class="cat-label">Sektor Sosial</span>
    </div>
    <div class="cat-btn" data-cat="dasarlain" onclick="selectCat(this)" onmouseenter="showTip(event,'Indikator Dasar Lain')" onmouseleave="hideTip()">
        <span class="cat-icon">🏘️</span>
        <span class="cat-label">Indikator Dasar Lain</span>
    </div>
</div>
<div class="cat-tooltip" id="cat-tip"></div>

<!-- LEGEND -->
<div id="legend">
    <div class="leg-item" onclick="filterProv(document.querySelector('.prov-btn[data-p=aceh]'),'aceh')"><div class="leg-dot" style="background:#4a7c59"></div>Aceh</div>
    <div class="leg-item" onclick="filterProv(document.querySelector('.prov-btn[data-p=sumut]'),'sumatera_utara')"><div class="leg-dot" style="background:#8b4a4a"></div>Sumatera Utara</div>
    <div class="leg-item" onclick="filterProv(document.querySelector('.prov-btn[data-p=sumbar]'),'sumatera_barat')"><div class="leg-dot" style="background:#7a6a3a"></div>Sumatera Barat</div>
</div>

<!-- SUMMARY PANEL -->
<div id="summary-panel">
    <div class="panel-header">
        <div class="panel-title">
            <span id="panel-title-text">Ringkasan Penanganan</span>
            <div class="panel-close" onclick="togglePanel()">✕</div>
        </div>
        <div class="panel-title-sub" id="panel-title-sub">Semua Provinsi · Update 10 Feb 2026</div>
    </div>

    <!-- Overview KPIs -->
    <div class="overview-row">
        <div class="ov-card">
            <div class="ov-value">68%</div>
            <div class="ov-label">Wilayah Pulih</div>
            <div class="ov-tag green">↑ On Track</div>
        </div>
        <div class="ov-card">
            <div class="ov-value">12</div>
            <div class="ov-label">Kab/Kota Belum Selesai</div>
            <div class="ov-tag red">⚠ Kritis</div>
        </div>
        <div class="ov-card">
            <div class="ov-value">74.3K</div>
            <div class="ov-label">Jumlah Pengungsi</div>
            <div class="ov-tag orange">Aktif</div>
        </div>
    </div>

    <div class="panel-scroll" id="panel-scroll">
        <!-- Category sections injected by JS -->
    </div>
</div>

<!-- TOGGLE PANEL BTN (shown when collapsed) -->
<div id="toggle-panel" onclick="togglePanel()" title="Buka Panel">📊</div>

<!-- PROVINCE POPUP -->
<div id="prov-popup">
    <div id="prov-popup-name"><span class="dot" id="prov-dot"></span><span id="prov-name-text">—</span></div>
    <div class="pp-row"><span class="pp-label">Persentase Pulih</span><span class="pp-val" id="pp-pct">—</span></div>
    <div class="pp-bar"><div class="pp-bar-fill" id="pp-bar" style="width:0%"></div></div>
    <div class="pp-row"><span class="pp-label">Kab/Kota Terdampak</span><span class="pp-val" id="pp-kab">—</span></div>
    <div class="pp-row"><span class="pp-label">Pengungsi</span><span class="pp-val" id="pp-peng">—</span></div>
    <div class="pp-row" style="margin-top:6px"><span class="pp-label" style="font-size:10px;color:var(--ink3)" id="pp-status">—</span></div>
</div>

<script>
    // ══════════════════════════════════════════
    // PROVINCE META (non-geometry data)
    // ══════════════════════════════════════════
    const PROVINCE_META = {
        aceh:           { name:'Aceh',           color:'#4a7c59', pct:72, kab:8,  peng:'18.200', status:'Pemulihan berjalan baik, infrastruktur jalan 78% selesai.' },
        sumatera_utara: { name:'Sumatera Utara', color:'#8b4a4a', pct:61, kab:5,  peng:'31.400', status:'Masalah utama: jaringan listrik dan air bersih.' },
        sumatera_barat: { name:'Sumatera Barat', color:'#7a6a3a', pct:49, kab:7,  peng:'24.769', status:'Penanganan masih berlangsung intensif di 7 kab/kota.' },
    };

    // KML file paths — adjust if your Laravel public path differs
    const KML_FILES = {
        aceh:           '/polygons/aceh.kml',
        sumatera_utara: '/polygons/sumatera_utara.kml',
        sumatera_barat: '/polygons/sumatera_barat.kml',
    };

    const CATEGORIES = [
        {
            id:'pemerintahan', name:'Fasilitas Pemerintahan', icon:'🏛️',
            reports:{
                aceh:[
                    { id:'r15', loc:'Kab. Aceh Selatan', status:'done', pct:100, before:'🏚️', after:'🏛️', desc:'Kantor Bupati Aceh Selatan telah selesai diperbaiki dan beroperasi penuh. Dokumen-dokumen penting berhasil diselamatkan dan diarsipkan.', date:'01 Feb 2026' },
                ],
                sumatera_utara:[
                    { id:'r16', loc:'Kec. Pahae Jae', status:'progress', pct:55, before:'🏛️', after:'🏗️', desc:'Kantor Camat Pahae Jae — renovasi atap dan dinding eksterior selesai, interior dan furniture dalam proses pengadaan.', date:'07 Feb 2026' },
                ],
                sumatera_barat:[
                    { id:'r17', loc:'Kab. Solok Selatan', status:'pending', pct:10, before:'🏚️', after:'🚧', desc:'Kantor BPBD dan Dinas PU mengalami kerusakan serius. Pegawai sementara beroperasi dari posko darurat. Proses tender perbaikan sedang berjalan.', date:'09 Feb 2026' },
                ],
            }
        },
        {
            id:'publik', name:'Layanan Publik', icon:'🏥',
            reports:{
                aceh:[
                    { id:'r1', loc:'Kab. Aceh Besar', status:'progress', pct:75, before:'🏥', after:'🏗️', desc:'Rehabilitasi RSUD Zainoel Abidin berjalan. Lantai 2 sudah selesai, lantai 3 dalam proses pemasangan atap dan jendela.', date:'08 Feb 2026' },
                    { id:'r2', loc:'Kec. Peukan Baro', status:'done', pct:100, before:'🏚️', after:'🏥', desc:'Puskesmas Peukan Baro telah selesai direnovasi dan sudah beroperasi normal kembali sejak 2 Februari 2026.', date:'02 Feb 2026' },
                ],
                sumatera_utara:[
                    { id:'r3', loc:'Kab. Tapanuli Utara', status:'pending', pct:20, before:'🏚️', after:'🚧', desc:'Puskesmas Siborong-borong mengalami kerusakan berat. Proses pengadaan material masih berlangsung, estimasi mulai konstruksi minggu depan.', date:'09 Feb 2026' },
                ],
                sumatera_barat:[
                    { id:'r4', loc:'Kab. Padang Pariaman', status:'progress', pct:55, before:'🏥', after:'🏗️', desc:'RSUD Padang Pariaman — wing selatan dalam tahap rekonstruksi. Pasien dialihkan ke fasilitas sementara di Kecamatan Pariaman Timur.', date:'07 Feb 2026' },
                    { id:'r5', loc:'Kec. Sutera', status:'progress', pct:40, before:'🏚️', after:'🔧', desc:'Dua puskesmas pembantu mengalami kerusakan sedang. Atap telah diperbaiki, instalasi listrik masih menunggu teknisi.', date:'09 Feb 2026' },
                ],
            }
        },
        {
            id:'akses_darat', name:'Akses Darat', icon:'🚧',
            reports:{
                aceh:[
                    { id:'r10', loc:'Jalan Nasional Aceh Timur', status:'progress', pct:65, before:'🛤️', after:'🚧', desc:'12 km jalan nasional rusak berat telah diaspal kembali. Sisa 7 km masih dalam proses, target selesai 20 Februari 2026.', date:'09 Feb 2026' },
                    { id:'r11', loc:'Jembatan Krueng Baro', status:'done', pct:100, before:'🌉', after:'🌁', desc:'Jembatan darurat Bailey berhasil dipasang dan sudah dapat dilalui kendaraan roda empat. Kapasitas beban 8 ton.', date:'04 Feb 2026' },
                ],
                sumatera_utara:[
                    { id:'r12', loc:'Kab. Tapanuli Selatan', status:'progress', pct:45, before:'🛤️', after:'🚧', desc:'8 km jalan kabupaten rusak sedang, perbaikan bahu jalan dan pengaspalan sudah 45% selesai.', date:'08 Feb 2026' },
                    { id:'r13', loc:'Jembatan Aek Raisan', status:'pending', pct:5, before:'🏚️', after:'🚧', desc:'Jembatan mengalami kerusakan struktur berat. Tim insinyur sedang melakukan asesmen kelayakan sebelum konstruksi dimulai.', date:'09 Feb 2026' },
                ],
                sumatera_barat:[
                    { id:'r14', loc:'Kab. Pesisir Selatan', status:'progress', pct:50, before:'🛤️', after:'🚧', desc:'84 km jalan pesisir terdampak, 42 km sudah selesai. Ruas Painan–Tapan menjadi prioritas utama untuk akses logistik.', date:'10 Feb 2026' },
                ],
            }
        },
        {
            id:'ekonomi', name:'Sektor Ekonomi', icon:'💰',
            reports:{
                aceh:[
                    { id:'r6', loc:'Kota Banda Aceh', status:'done', pct:100, before:'🏚️', after:'💰', desc:'SDN 1 dan SMPN 3 Banda Aceh telah selesai diperbaiki. Kegiatan belajar mengajar sudah kembali normal per 5 Februari 2026.', date:'05 Feb 2026' },
                    { id:'r7', loc:'Kab. Aceh Tengah', status:'progress', pct:60, before:'💰', after:'🏗️', desc:'SMAN 1 Bebesen — tiga ruang kelas sedang dalam proses rehab. Siswa sementara menggunakan tenda darurat di lapangan sekolah.', date:'08 Feb 2026' },
                ],
                sumatera_utara:[
                    { id:'r8', loc:'Kab. Humbang Hasundutan', status:'pending', pct:15, before:'🏚️', after:'🚧', desc:'SDN Dolok Sanggul rusak berat akibat longsor. Survei kerusakan selesai, menunggu alokasi dana dari BNPB pusat.', date:'09 Feb 2026' },
                ],
                sumatera_barat:[
                    { id:'r9', loc:'Kab. Agam', status:'progress', pct:70, before:'💰', after:'🏗️', desc:'SMPN 2 Lubuk Basung — ruang kelas dan perpustakaan sudah selesai. Toilet dan akses jalan masuk masih dalam perbaikan.', date:'06 Feb 2026' },
                ],
            }
        },
        {
            id:'sosial', name:'Sektor Sosial', icon:'🧑',
            reports:{
                aceh:[
                    { id:'r18', loc:'Kota Sabang', status:'done', pct:100, before:'🚰', after:'🧑', desc:'Instalasi pengolahan air PDAM Sabang telah diperbaiki sepenuhnya. Distribusi air bersih kembali normal ke seluruh 6 kecamatan.', date:'03 Feb 2026' },
                    { id:'r19', loc:'Kab. Aceh Jaya', status:'progress', pct:68, before:'🏚️', after:'🔧', desc:'Pipa distribusi utama sepanjang 4,2 km sedang dipasang ulang. 3 desa masih mengandalkan tangki air mobile dari BNPB.', date:'08 Feb 2026' },
                ],
                sumatera_utara:[
                    { id:'r20', loc:'Kab. Labuhanbatu', status:'progress', pct:48, before:'🚰', after:'🏗️', desc:'SPAM regional mengalami kerusakan pada sistem filtrasi. Komponen pengganti sudah tiba, pemasangan dijadwalkan 12–14 Februari.', date:'09 Feb 2026' },
                ],
                sumatera_barat:[
                    { id:'r21', loc:'Kab. Pasaman Barat', status:'pending', pct:0, before:'🏚️', after:'🚧', desc:'12 desa masih padam listrik dan tanpa air bersih. Tim survei sudah masuk, namun akses jalan masih terputus di 3 titik.', date:'10 Feb 2026' },
                    { id:'r22', loc:'Kota Padang', status:'progress', pct:82, before:'🚰', after:'🧑', desc:'PDAM Kota Padang hampir pulih sepenuhnya. Hanya 2 kelurahan di kecamatan Koto Tangah yang masih menunggu penyambungan jaringan.', date:'09 Feb 2026' },
                ],
            }
        },
        {
            id:'dasarlain', name:'Indikator Dasar Lain', icon:'🏘️',
            reports:{
                aceh:[
                    { id:'r23', loc:'Kab. Aceh Besar', status:'progress', pct:58, before:'🏚️', after:'🏘️', desc:'Program rehab rumah warga: 234 dari 403 rumah rusak sedang telah selesai diperbaiki. Target penyelesaian akhir Februari 2026.', date:'09 Feb 2026' },
                    { id:'r24', loc:'Kec. Lhoknga', status:'done', pct:100, before:'🏕️', after:'🏘️', desc:'Semua 87 KK yang mengungsi di barak sementara sudah kembali ke rumah masing-masing yang telah diperbaiki.', date:'06 Feb 2026' },
                ],
                sumatera_utara:[
                    { id:'r25', loc:'Kab. Tapanuli Utara', status:'progress', pct:35, before:'🏚️', after:'🏗️', desc:'182 rumah rusak berat, 67 sudah masuk tahap konstruksi. Bahan bangunan (bata, genteng, besi) sedang didistribusikan ke lokasi.', date:'08 Feb 2026' },
                ],
                sumatera_barat:[
                    { id:'r26', loc:'Kab. Agam', status:'pending', pct:12, before:'🏕️', after:'🚧', desc:'1.240 KK masih di pengungsian. Lahan relokasi sudah ditetapkan, proses administrasi dan sertifikasi lahan sedang berjalan.', date:'10 Feb 2026' },
                    { id:'r27', loc:'Kab. Padang Pariaman', status:'progress', pct:44, before:'🏚️', after:'🏗️', desc:'Dari 890 rumah terdampak, 392 sudah selesai. Tim Kodam Bukit Barisan membantu percepatan pembangunan hunian sementara.', date:'09 Feb 2026' },
                ],
            }
        },
    ];

    // ══════════════════════════════════════════
    // KML PARSER — converts KML text → GeoJSON geometry
    // ══════════════════════════════════════════

    /**
     * Parse a coordinate string from KML (lon,lat,alt lon,lat,alt ...)
     * into a GeoJSON ring [[lon, lat], ...]
     */
    function parseCoordinates(coordStr) {
        return coordStr.trim().split(/\s+/).map(function(triplet) {
            var parts = triplet.split(',');
            return [parseFloat(parts[0]), parseFloat(parts[1])];
        }).filter(function(c) {
            return !isNaN(c[0]) && !isNaN(c[1]);
        });
    }

    /**
     * Parse a <Polygon> element into a GeoJSON Polygon geometry object.
     * Handles outerBoundaryIs and multiple innerBoundaryIs (holes).
     */
    function parseKmlPolygon(polygonEl) {
        var rings = [];

        var outer = polygonEl.querySelector('outerBoundaryIs LinearRing coordinates');
        if (!outer) return null;
        rings.push(parseCoordinates(outer.textContent));

        var inners = polygonEl.querySelectorAll('innerBoundaryIs LinearRing coordinates');
        inners.forEach(function(inner) {
            rings.push(parseCoordinates(inner.textContent));
        });

        return { type: 'Polygon', coordinates: rings };
    }

    /**
     * Parse a KML string and return a GeoJSON geometry.
     * Supports Polygon and MultiGeometry (→ MultiPolygon).
     */
    function kmlStringToGeoJSON(kmlText) {
        var parser = new DOMParser();
        var doc = parser.parseFromString(kmlText, 'application/xml');

        // Check for parse errors
        var parseError = doc.querySelector('parsererror');
        if (parseError) {
            console.error('KML parse error:', parseError.textContent);
            return null;
        }

        // Collect all <Polygon> elements at top level or inside <MultiGeometry>
        var polygonEls = doc.querySelectorAll('Placemark MultiGeometry Polygon');

        if (polygonEls.length > 1) {
            // MultiPolygon
            var polygons = [];
            polygonEls.forEach(function(el) {
                var poly = parseKmlPolygon(el);
                if (poly) polygons.push(poly.coordinates);
            });
            return { type: 'MultiPolygon', coordinates: polygons };
        } else if (polygonEls.length === 1) {
            // Single polygon inside MultiGeometry
            return parseKmlPolygon(polygonEls[0]);
        } else {
            // Try direct Polygon (no MultiGeometry wrapper)
            var singlePoly = doc.querySelector('Placemark Polygon');
            if (singlePoly) return parseKmlPolygon(singlePoly);
        }

        return null;
    }

    // ══════════════════════════════════════════
    // MAP INIT
    // ══════════════════════════════════════════
    const map = L.map('map', {
        center: [2.5, 98.5], zoom: 7,
        zoomControl: true,
        attributionControl: false
    });
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);

    const geoLayers = {};   // slug → L.GeoJSON layer
    let loadedCount = 0;
    const totalProvinces = Object.keys(KML_FILES).length;

    /**
     * Load a single KML file, parse it, and add to the map.
     * Called for each province slug.
     */
    function loadKmlProvince(slug) {
        var meta = PROVINCE_META[slug];
        var url  = KML_FILES[slug];

        fetch(url)
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status + ' fetching ' + url);
                }
                return response.text();
            })
            .then(function(kmlText) {
                var geometry = kmlStringToGeoJSON(kmlText);
                if (!geometry) {
                    console.error('Failed to parse KML for:', slug);
                    return;
                }

                var geojson = { type: 'Feature', geometry: geometry };

                var layer = L.geoJSON(geojson, {
                    style: {
                        color:       meta.color,
                        weight:      2,
                        opacity:     0.85,
                        fillColor:   meta.color,
                        fillOpacity: 0.18,
                    }
                });

                layer.on('click',     function()  { showProvPopup(slug); });
                layer.on('mouseover', function(e) {
                    e.target.setStyle({ fillOpacity: 0.38, weight: 3 });
                    showProvPopup(slug);
                });
                layer.on('mouseout',  function(e) {
                    e.target.setStyle({ fillOpacity: 0.18, weight: 2 });
                });

                layer.addTo(map);
                geoLayers[slug] = layer;

                loadedCount++;
                if (loadedCount === totalProvinces) {
                    // All provinces loaded — fit map to combined bounds
                    onAllProvincesLoaded();
                }
            })
            .catch(function(err) {
                console.error('Error loading KML for', slug, ':', err);
                loadedCount++;
                if (loadedCount === totalProvinces) {
                    onAllProvincesLoaded();
                }
            });
    }

    function onAllProvincesLoaded() {
        var layers = Object.values(geoLayers);
        if (layers.length === 0) return;
        var group = L.featureGroup(layers);
        map.fitBounds(group.getBounds(), { padding: [60, 60] });
        renderPanel();
    }

    // Kick off loading all provinces
    Object.keys(KML_FILES).forEach(function(slug) {
        loadKmlProvince(slug);
    });

    // ══════════════════════════════════════════
    // PROVINCE POPUP
    // ══════════════════════════════════════════
    let popupTimeout;
    function showProvPopup(slug) {
        clearTimeout(popupTimeout);
        var p = PROVINCE_META[slug];
        $('#prov-dot').css('background', p.color);
        $('#prov-name-text').text(p.name);
        $('#pp-pct').text(p.pct + '%');
        $('#pp-bar').css({
            width: p.pct + '%',
            background: p.pct >= 70 ? '#52a86a' : p.pct >= 50 ? '#c97b22' : '#c0392b'
        });
        $('#pp-kab').text(p.kab + ' Kabupaten/Kota');
        $('#pp-peng').text(p.peng + ' jiwa');
        $('#pp-status').text(p.status);
        $('#prov-popup').addClass('visible');
    }

    map.on('click', function() {
        popupTimeout = setTimeout(function() { $('#prov-popup').removeClass('visible'); }, 200);
    });

    // ══════════════════════════════════════════
    // PROVINCE FILTER
    // ══════════════════════════════════════════
    let activeProv = 'all';
    function filterProv(btn, slug) {
        activeProv = slug;
        $('#prov-filters .prov-btn').removeClass('active');
        if (btn) $(btn).addClass('active');

        var layers = Object.values(geoLayers);
        if (layers.length === 0) return; // still loading

        if (slug === 'all') {
            Object.values(geoLayers).forEach(function(l) {
                l.addTo(map);
                l.setStyle({ fillOpacity: 0.18, weight: 2 });
            });
            var group = L.featureGroup(Object.values(geoLayers));
            map.fitBounds(group.getBounds(), { padding: [60, 60] });
        } else {
            Object.entries(geoLayers).forEach(function(entry) {
                var s = entry[0], l = entry[1];
                if (s === slug) {
                    l.addTo(map);
                    l.setStyle({ fillOpacity: 0.35, weight: 3 });
                } else {
                    l.setStyle({ fillOpacity: 0.08, weight: 1 });
                }
            });
            if (geoLayers[slug]) {
                map.fitBounds(geoLayers[slug].getBounds(), { padding: [80, 80] });
                showProvPopup(slug);
            }
        }
        renderPanel();
    }

    // ══════════════════════════════════════════
    // CATEGORY SELECTION
    // ══════════════════════════════════════════
    let activeCat = 'publik';
    function selectCat(el) {
        $('.cat-btn').removeClass('active');
        $(el).addClass('active');
        activeCat = $(el).data('cat');
        renderPanel();
        setTimeout(function() {
            var sec = document.querySelector('.cat-section[data-cat="' + activeCat + '"]');
            if (sec) sec.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 100);
    }

    // ══════════════════════════════════════════
    // RENDER PANEL
    // ══════════════════════════════════════════
    function barColor(pct) {
        if (pct >= 75) return 'linear-gradient(90deg,#52a86a,#2d6a3a)';
        if (pct >= 40) return 'linear-gradient(90deg,#e8a838,#c97b22)';
        return 'linear-gradient(90deg,#e8534a,#c0392b)';
    }
    function badgeClass(status) {
        return status === 'done' ? 'done' : status === 'progress' ? 'progress' : 'pending';
    }
    function badgeText(status) {
        return status === 'done' ? '✓ Selesai' : status === 'progress' ? '⟳ Berlangsung' : '⚠ Belum Mulai';
    }

    function renderPanel() {
        var scroll = document.getElementById('panel-scroll');
        scroll.innerHTML = '';

        var provFilter = activeProv === 'all'
            ? ['aceh', 'sumatera_utara', 'sumatera_barat']
            : [activeProv];

        CATEGORIES.forEach(function(cat) {
            var reports = [];
            provFilter.forEach(function(p) {
                (cat.reports[p] || []).forEach(function(r) {
                    reports.push(Object.assign({}, r, { prov: p }));
                });
            });
            if (!reports.length) return;

            var avgPct = Math.round(reports.reduce(function(a, r) { return a + r.pct; }, 0) / reports.length);
            var isOpen = cat.id === activeCat;

            var sec = document.createElement('div');
            sec.className = 'cat-section' + (isOpen ? ' open' : '');
            sec.dataset.cat = cat.id;

            sec.innerHTML =
                '<div class="cat-section-header" onclick="toggleCatSection(this)">' +
                '<span class="cat-section-icon">' + cat.icon + '</span>' +
                '<span class="cat-section-name">' + cat.name + '</span>' +
                '<span class="cat-section-pct">' + avgPct + '%</span>' +
                '<span class="cat-section-arrow">▾</span>' +
                '</div>' +
                '<div class="cat-bar"><div class="cat-bar-fill" style="width:' + avgPct + '%;background:' + barColor(avgPct) + '"></div></div>' +
                '<div class="cat-body">' +
                '<div class="report-list" id="rl-' + cat.id + '"></div>' +
                '<button class="add-report-btn" onclick="addReport(\'' + cat.id + '\')">＋ Tambah Laporan</button>' +
                '</div>';

            scroll.appendChild(sec);

            var rl = sec.querySelector('#rl-' + cat.id);
            reports.forEach(function(r) {
                var provName = (PROVINCE_META[r.prov] || {}).name || r.prov;
                var card = document.createElement('div');
                card.className = 'report-card';
                card.id = 'rc-' + r.id;
                card.innerHTML =
                    '<div class="report-card-header" onclick="toggleReport(\'' + r.id + '\')">' +
                    '<div>' +
                    '<div class="report-card-title">' + r.loc + '</div>' +
                    '<div class="report-card-meta">' + provName + ' · ' + r.date + '</div>' +
                    '</div>' +
                    '<div class="report-badge ' + badgeClass(r.status) + '">' + badgeText(r.status) + '</div>' +
                    '</div>' +
                    '<div class="report-expand">' +
                    '<div class="report-progress-row">' +
                    '<div class="rp-bar"><div class="rp-bar-fill" style="width:' + r.pct + '%;background:' + barColor(r.pct) + '"></div></div>' +
                    '<span class="rp-pct">' + r.pct + '%</span>' +
                    '</div>' +
                    '<div class="report-photos">' +
                    '<div class="rp-photo">' + r.before + '<div class="rp-label">SEBELUM</div></div>' +
                    '<div style="display:flex;align-items:center;padding:0 6px;color:var(--ink3);font-size:18px">›</div>' +
                    '<div class="rp-photo">' + r.after + '<div class="rp-label">SESUDAH</div></div>' +
                    '</div>' +
                    '<div class="report-desc">' + r.desc + '</div>' +
                    '<div class="report-date">📅 Dilaporkan: ' + r.date + '</div>' +
                    '</div>';
                rl.appendChild(card);
            });
        });
    }

    function toggleCatSection(header) {
        var sec = header.closest('.cat-section');
        sec.classList.toggle('open');
        activeCat = sec.dataset.cat;
        $('.cat-btn').removeClass('active');
        $('.cat-btn[data-cat="' + activeCat + '"]').addClass('active');
    }

    function toggleReport(id) {
        var card = document.getElementById('rc-' + id);
        card.classList.toggle('expanded');
    }

    function addReport(catId) {
        alert('Fitur tambah laporan untuk kategori: ' + catId + '\n(Form input akan ditampilkan di sini)');
    }

    // ══════════════════════════════════════════
    // PANEL TOGGLE
    // ══════════════════════════════════════════
    function togglePanel() {
        var p = document.getElementById('summary-panel');
        var t = document.getElementById('toggle-panel');
        p.classList.toggle('collapsed');
        t.classList.toggle('show');
    }

    // ══════════════════════════════════════════
    // TOOLTIP
    // ══════════════════════════════════════════
    function showTip(e, text) {
        var t = document.getElementById('cat-tip');
        var rect = e.currentTarget.getBoundingClientRect();
        t.textContent = text;
        t.style.top = (rect.top + rect.height / 2 - 12) + 'px';
        t.style.opacity = '1';
    }
    function hideTip() {
        document.getElementById('cat-tip').style.opacity = '0';
    }
</script>
</body>
</html>
