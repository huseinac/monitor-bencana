<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
    <title>Monitoring Penanganan Bencana — Sumatera & Aceh</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script>
        let BASE_URL = "{{ url('/') }}/";
    </script>
    <style>
        .leaflet-top, .leaflet-left {
            left: unset;
            top: unset;
            bottom: 18px !important;
            right: 18px !important;
        }
    </style>
</head>
<body>

<div id="map" style="margin-top: 74px;"></div>

<div id="topbar">
    <div class="brand">
        <a href="{{ route('/') }}" class="brand-logo" style="display: flex; flex-direction: row; gap: 6px;">
            <img src="{{ asset('logo.png') }}" alt=""/>
        </a>
    </div>
    <div class="d-flex flex-row align-items-center" style="gap: 14px">
        <img src="{{ asset('logo2.png') }}" alt="" style="height: 52px;" />
        <img src="{{ asset('icon-info.png') }}" alt="" style="height: 26px; margin-top: 2px">
        <a href="{{ route('login') }}" class="btn btn-secondary rounded-4 py-2 px-4"
           style="background-color:#021e49;border:2px solid #c1daff;border-radius:8px">LOGIN</a>
    </div>
</div>

<div id="cat-sidebar">
    @php
        $catButtons = [
            ['icon' => 'https://img.icons8.com/office/40/country.png',               'alt' => 'country',                 'label' => 'Wilayah',              'panel' => 'summary-panel2'],
            ['icon' => 'https://img.icons8.com/office/40/hashtag-activity-feed.png', 'alt' => 'hashtag-activity-feed',  'label' => 'Indikator',            'panel' => 'summary-panel'],
            ['icon' => 'https://img.icons8.com/office/40/gender-neutral-user.png',   'alt' => 'gender-neutral-user',    'label' => 'K/L<br>Pelaksana',     'panel' => 'summary-panel3'],
            ['icon' => 'https://img.icons8.com/office/40/new-job.png',               'alt' => 'new-job',                'label' => 'Paket<br>Pekerjaan',   'panel' => 'summary-panel4'],
            ['icon' => 'https://img.icons8.com/office/40/donate.png',                'alt' => 'donate',                 'label' => 'TKD',                  'panel' => 'summary-panel5'],
        ]
    @endphp
    @foreach($catButtons as $btn)
        <div class="cat-btn" id="cat_btn_{{ $btn['alt'] }}" data-panel="{{ $btn['panel'] }}" data-alt="{{ $btn['alt'] }}">
            <img width="24" height="24" src="{{ $btn['icon'] }}" alt="{{ $btn['alt'] }}"/>
            <span class="cat-label">{!! $btn['label'] !!}</span>
        </div>
    @endforeach
</div>

<div style="position: fixed;right: 28px;top: 88px;display: flex;flex-direction: column;gap: 12px;">
    @foreach($catButtons as $btn)
        <div id="cat_action_{{ $btn['alt'] }}" class="cat-action" style="display: none;">
            <div style="display: flex;flex-direction: column;gap: 12px">
                <div class="cat-btn2" onclick="open_search('{{ $btn['panel'] }}')">
                    <img width="30" height="30" src="https://img.icons8.com/offices/30/search.png" alt="search"/>
                </div>
                <div class="cat-btn2" onclick="open_data('{{ $btn['panel'] }}')">
                    <img width="30" height="30" src="https://img.icons8.com/offices/30/accept-database.png" alt="accept-database"/>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div style="position:fixed;bottom:8px;left:8px;">
    Catatan : Update Data 20 Maret 2026
</div>

{{-- Reusable panel macro --}}
@php
    $panels = [
        ['id' => 'summary-panel',  'title' => 'Kondisi dan Progress Indikator Pemulihan Pemerintahan dan Kemasyarakatan yang Terdampak Bencana', 'sub' => '', 'scroll_id' => 'panel_scroll'],
        ['id' => 'summary-panel2', 'title' => 'Kondisi dan Progress Indikator Pemulihan Pemerintahan dan Kemasyarakatan yang Terdampak Bencana', 'sub' => '', 'scroll_id' => 'panel-scroll2'],
        ['id' => 'summary-panel3', 'title' => 'Kondisi Kinerja Kementerian Penanggung Jawab Percepatan Penanganan Bencana Sumatera dan Aceh',  'sub' => '', 'scroll_id' => 'panel_scroll3'],
        ['id' => 'summary-panel4', 'title' => 'Ringkasan Paket Pekerjaan Penanganan Bencana Sumatra dan Aceh', 'sub' => 'Ringkasan jenis paket pekerjaan terkait penanganan bencana di Sumatera dan Aceh', 'scroll_id' => 'panel_scroll4'],
        ['id' => 'summary-panel5', 'title' => 'TKD', 'sub' => 'Ringkasan TKD', 'scroll_id' => 'panel_scroll5'],
    ]
@endphp

@foreach($panels as $panel)
    <div id="{{ $panel['id'] }}" style="display:none;">
        <div class="panel-header" style="padding:15px;border-bottom:1px solid #eee;">
            <div class="panel-title" style="display:flex;justify-content:space-between;align-items:center;font-weight:bold;">
                <span style="flex: 11;font-weight: 500;">{{ $panel['title'] }}</span>
                <div class="panel-close" data-panel="{{ $panel['id'] }}" style="cursor:pointer;color:#888;flex: 1;">✕</div>
            </div>
            <div style="font-size:12px;color:#666;">{{ $panel['sub'] }}</div>

            <div id="header_wilayah" class="my-2" style="display: flex; flex-direction: column;">
                <div style="display: flex; flex-direction: row;font-size: 10px;">
                    <div class="px-2 py-1" style="background-color: #DFDFDF; flex: 1;border-right: 2px solid #fff;display: flex;flex-direction: row;justify-content: start;gap: 2px;">
                        <div>Prop :</div>
                        <div class="province_name" style="font-weight: 500;"></div>
                    </div>
                    <div class="px-2 py-1" style="background-color: #DFDFDF; flex: 1;border-right: 2px solid #fff;display: flex;flex-direction: row;justify-content: start;gap: 2px">
                        <div class="kabupaten_name" style="font-weight: 500;"></div>
                    </div>
                    <div class="px-2 py-1" style="background-color: #DFDFDF; flex: 1;border-right: 2px solid #fff;display: flex;flex-direction: row;justify-content: start;gap: 2px">
                        <div>Kec :</div>
                        <div class="kecamatan_name" style="font-weight: 500;"></div>
                    </div>
                </div>
            </div>
            @if($panel['id'] == 'summary-panel2')
                <div id="header_wilayah" class="my-2" style="display: flex; flex-direction: column;">
                    <div style="display: flex; flex-direction: row;font-size: 10px;">
                        <div class="px-2 py-1" style="flex: 1;display: flex;flex-direction: row;justify-content: start;gap: 4px;">
                            <div style="width: 15px;height: 15px;border-radius: 16px;background-color: #00C853"></div>
                            <div>NORMAL : </div>
                            <div id="normal_count" style="font-weight: 500;"></div>
                        </div>
                        <div class="px-2 py-1" style="flex: 2;display: flex;flex-direction: row;justify-content: start;gap: 4px;">
                            <div style="width: 15px;height: 15px;border-radius: 16px;background-color: #2962FF"></div>
                            <div>MENDEKATI NORMAL : </div>
                            <div id="mendekati_count" style="font-weight: 500;"></div>
                        </div>
                        <div class="px-2 py-1" style="flex: 2;display: flex;flex-direction: row;justify-content: start;gap: 4px;">
                            <div style="width: 15px;height: 15px;border-radius: 16px;background-color: #FFD600"></div>
                            <div>ATENSI KHUSUS : </div>
                            <div id="atensi_count" style="font-weight: 500;"></div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
        <div class="panel-scroll" id="{{ $panel['scroll_id'] }}" style="padding:12px;">
            @if($panel['id'] === 'summary-panel2')
                @foreach($list_wilayah as $wilayah)
                    <div class="leg-item" data-kode="{{ $wilayah['kode'] }}" style="cursor:pointer;">
                        <div class="leg-dot" style="background:{{ $wilayah['color'] }}"></div>
                        {{ $wilayah['nama'] }}
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endforeach

<div id="paket_pekerjaan_detail" style="display: none;z-index: 200;position: fixed;top: 88px;right: 28px">
    <div style="height: 85dvh;width: 70vw;position: relative;">
        <div style="cursor:pointer;color:#888;position: fixed; top: 98px; right: 38px;z-index: 300;" onclick="$('#paket_pekerjaan_detail').hide()">✕</div>
        <iframe src="https://demo.gemasolusindo.co.id/simpro_ikn/pengadaan2/uji-paket-pengadaan-1?submenu=dashboard" style="width: 100%;height: 100%;" frameborder="0"></iframe>
    </div>
</div>

<div style="position:fixed;bottom:8px;left:8px;">

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/io.js') }}"></script>
<script>
    // ─── Constants & State ────────────────────────────────────────────────────────
    const PROVINCE_META = @json($list_wilayah);
    const DATA_MAP_URL  = "{{ route('data_map') }}";

    // GeoJSON fetch cache — avoids re-fetching the same polygon on drill-down/back
    const geoCache = new Map();

    const geoLayers  = {};          // Province layers
    const subLayers  = {};          // Kabupaten/Kota
    const subLayers2 = {};          // Kecamatan
    const subLayers3 = {};          // Kelurahan

    let markers = [];
    let markersData = [];

    let total_atensi = 0;
    let total_mendekati = 0;
    let total_normal = 0;

    let activeProv = 'all';
    let activeCat = '';
    let popupTimeout;

    // ─── Map setup ────────────────────────────────────────────────────────────────
    const map = L.map('map', {
        center: [2.5, 98.5], zoom: 5,
        zoomControl: true,
        attributionControl: false,
    });
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);

    // ─── Add Custom Marker ────────────────────────────────────────────────────────────────
    function addCustomMarker(latlng, iconUrl, data = {}, size = [32, 32]) {
        const customIcon = L.icon({
            iconUrl: iconUrl,
            iconSize: size,
            iconAnchor: [size[0] / 2, size[1]],
            popupAnchor: [0, -size[1]]
        });

        const marker = L.marker(latlng, { icon: customIcon, data: data }).addTo(map);

        // Build popup content from data
        const popupContent = `
        <div style="font-family: sans-serif; width: 300px; border-radius: 10px; overflow: hidden;">
            <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 14px 8px; border-bottom:1px solid #eee;">
                <strong style="font-size:17px;">${data.wilayah.parent.parent.nama || ''} - ${data.wilayah.parent.nama || ''} - ${data.wilayah.nama || 'Nama Wilayah'}</strong>
            </div>
            <div style="padding:12px 14px;">
                <div style="font-size:13px; color:#666; margin-bottom:2px;">
                    Persentase Pulih:
                    <strong style="float:right; font-size:16px; color:#111;">${data.persentase || 0}%</strong>
                </div>
                <div style="clear:both"></div>

                <!-- Progress bar -->
                <div style="height:9px; border-radius:5px; background:#e0e0e0; margin:8px 0 12px; overflow:hidden; display:flex;">
                    <div style="background:#5a9a6e; flex:0 0 ${data.persentase || 0}%;"></div>
                </div>

                <!-- Stat: Jalan Rusak -->
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:7px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="#666">
                        <path d="M4 4h16v2H4zm0 4h16v2H4zm0 4h16v2H4zm0 4h16v2H4z"/>
                    </svg>
                    <span style="font-size:13px;"><strong>${data.jumlah || ''}</strong> ${data.indikator.parent != null ? (data.indikator.parent.nama || '') : ''} ${data.indikator.nama || ''} Terdapak Bencana</span>
                </div>

                <!-- Before / After photos -->
                <div style="display:flex; border-radius:8px; overflow:hidden; position:relative; height:100px;">
                    <div style="flex:1; position:relative; overflow:hidden;">
                        <img src="${ASSET_PATH}${data.list_detail[0].foto || ''}" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.background='#bbb'"/>
                        <div style="position:absolute;bottom:5px;left:0;right:0;text-align:center;font-size:10px;font-weight:700;color:#fff;letter-spacing:1px;text-shadow:0 1px 3px rgba(0,0,0,.6)">SEBELUM</div>
                    </div>
                    <div style="position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);z-index:10;">
                        <div style="width:26px;height:26px;background:rgba(255,255,255,.9);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;color:#444;box-shadow:0 1px 5px rgba(0,0,0,.2)">›</div>
                    </div>
                    <div style="flex:1; position:relative; overflow:hidden;">
                        <img src="${ASSET_PATH}${data.list_perbaikan.length > 0 ? (data.list_perbaikan[data.list_perbaikan.length - 1].foto || '') : ''}" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.background='#ccc'"/>
                        <div style="position:absolute;bottom:5px;left:0;right:0;text-align:center;font-size:10px;font-weight:700;color:#fff;letter-spacing:1px;text-shadow:0 1px 3px rgba(0,0,0,.6)">SESUDAH</div>
                    </div>
                </div>

            </div>
        </div>
    `;

        marker.bindPopup(L.popup({ maxWidth: 320, className: 'custom-popup' }).setContent(popupContent));

        marker.on('click', function (e) {
            this.openPopup();
            console.log("Marker Clicked!", this.options.data);
        });

        markersData[data.id] = data;
        return marker;
    }

    function addCustomMarker2(latlng, iconUrl, data = {}, size = [32, 32]) {
        const customIcon = L.icon({
            iconUrl: iconUrl,
            iconSize: size,
            iconAnchor: [size[0] / 2, size[1]],
            popupAnchor: [0, -size[1]]
        });

        const marker = L.marker(latlng, { icon: customIcon, data: data }).addTo(map);

        // Build popup content from data
        const popupContent = `
            <div style="font-family: sans-serif; width: 300px; border-radius: 10px; overflow: hidden;">
                <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 14px 8px; border-bottom:1px solid #eee;">
                    <strong style="font-size:17px;">${data.wilayah.parent.parent.nama || ''} - ${data.wilayah.parent.nama || ''} - ${data.wilayah.nama || 'Nama Wilayah'}</strong>
                </div>
                <div style="padding:12px; border-bottom:1px solid #eee;">
                    <p style="font-size:14px;margin: 0;">${data.nama || ''}</p>
                    <p style="font-size:12px;margin: 0;">${data.keterangan || ''}</p>
                </div>
                <div style="padding:12px 14px;">
                    <div style="font-size:13px; color:#666; margin-bottom:2px;">
                        Nominal Pekerjaan:
                        <strong style="float:right; font-size:14px; color:#111;">Rp. ${add_commas(data.nominal)}</strong>
                    </div>
                    <div style="clear:both"></div>
                </div>
                <button onclick="$('#paket_pekerjaan_detail').show()"
                    style="margin-left: 8px;margin-bottom: 8px; background:#2a4d7a;color:#fff;border:none;border-radius:5px;padding:4px 12px;font-size:12px;cursor:pointer;">
                    detail
                </button>
            </div>
        `;

        marker.bindPopup(L.popup({ maxWidth: 320, className: 'custom-popup' }).setContent(popupContent));

        marker.on('click', function (e) {
            this.openPopup();
            console.log("Marker Clicked!", this.options.data);
        });

        markersData[data.id] = data;
        return marker;
    }

    // ─── Cached GeoJSON fetch ──────────────────────────────────────────────────────
    async function fetchGeo(url) {
        if (geoCache.has(url)) return geoCache.get(url);
        const res = await fetch(url);
        if (!res.ok) throw new Error(`GeoJSON fetch failed: ${url} (${res.status})`);
        const data = await res.json();
        geoCache.set(url, data);
        return data;
    }

    // ─── Data fetch & render ──────────────────────────────────────────────────────
    async function fetchMapData(params = '') {
        const res = await $.getJSON(`${DATA_MAP_URL}?${params}`);
        renderIndicators(res.list_indikator, res.list_sektor_terdampak, (res.list_masalah_kritis || []).filter(i => i.indikator_id != null));
        renderPelaksana(res.list_pelaksana, res.list_sektor_terdampak, (res.list_masalah_kritis || []).filter(i => i.pelaksana_id != null));
        renderPaketPekerjaan(res.list_paket_pekerjaan);

        if (markers) {
            markers.forEach(marker => marker.remove());
            markers = [];
        }
        if (activeCat === 'hashtag-activity-feed' || activeCat === 'gender-neutral-user') {
            drawMarkersBencana(res.list_sektor_terdampak);
        }
        if (activeCat === 'new-job') {
            drawMarkersBencanaPekerjaan(res.list_paket_pekerjaan);
        }
    }

    let ASSET_PATH = "{{ asset('storage') }}/";

    function drawMarkersBencana(list) {
        list.forEach((item) => {
            if (item.list_detail.length > 0) {
                let iconUrl = '{{ asset('storage') }}/' + item.indikator.icon;
                const newMarker = addCustomMarker([parseFloat(item.list_detail[0].latitude), parseFloat(item.list_detail[0].longitude)], iconUrl, item);
                markers.push(newMarker);
            }
        });
    }

    function drawMarkersBencanaPekerjaan(list) {
        list.forEach((item) => {
            let iconUrl = '{{ asset('storage') }}/' + item.indikator.icon;
            const newMarker = addCustomMarker2([parseFloat(item.latitude), parseFloat(item.longitude)], iconUrl, item);
            markers.push(newMarker);
        });
    }

    function renderPaketPekerjaan(list) {
        $('#panel_scroll4').html(`
            <div style="display: flex; flex-direction: row;">
                <div style="flex: 1;">
                    ${list.map(buildPaketPekerjaanSection).join('')}
                </div>
            </div>
        `);
    }

    function displayPekerjaanDetail() {
        $('#paket_pekerjaan_detail').show();
    }

    function buildPaketPekerjaanSection({ id, nama, indikator, nominal}, index) {
        return `<div class="cat-section" data-cat="${id}" data-kode="${id}">
            <div class="cat-section-header" onclick="displayPekerjaanDetail(${id})">
                <span class="cat-section-icon"><img src="${ASSET_PATH + indikator.icon}" alt="" style="height: 100%;width: 100%;" /></span>
                <span class="cat-section-name">${nama}</span>
                <span class="cat-section-pct">Rp ${add_commas(nominal)}</span>
            </div>
            <div class="cat-bar">
                <div class="cat-bar-fill" style="width:${100}%;background:linear-gradient(90deg,#e8a838,#c97b22)"></div>
            </div>
        </div>`;
    }

    let _allListIndicator = [];
    let _allMasalahKritis = [];

    function renderIndicators(list, listIndicator, listMasalahKritis) {
        _allListIndicator = listIndicator || [];
        _allMasalahKritis = listMasalahKritis || [];

        $('#panel_scroll').html(`
        <div style="display: flex; flex-direction: row; gap: 12px;">
            <div style="flex: 1;">
                ${list.map(buildCatSection).join('')}
            </div>
            <div style="flex: 1; display: flex; flex-direction: column; gap: 10px;">
                <div id="masalah-kritis-panel">
                    ${buildMasalahKritisList(_allMasalahKritis, '_indikator')}
                </div>
                <div id="location-list-panel">
                    ${buildLocationList(_allListIndicator, '_indikator')}
                </div>
            </div>
        </div>
    `);
    }

    function buildCatSection({ id, nama, icon, percentage, children = [], list_pelaksana = [] }, index) {
        const childrenHtml = children.map(child => {
            const childPelaksana = (child.list_pelaksana || []).map(p => p.pelaksana.singkatan).join('<br>');
            return `
    <div class="cat-section" data-cat="${child.id}" data-kode="${child.kode}">
        <div class="cat-section-header" style="width:100%;display:flex;flex-direction:row;justify-content:space-between"
            onclick="toggleCatSection(this); filterByIndikator('${child.kode}', '${child.id}', true)">
            <div style="display: flex;flex-direction: row; gap: 4px;">
                <span class="cat-section-icon"><img src="${ASSET_PATH + child.icon}" alt="" style="height: 100%;width: 100%;" /></span>
                <div style="display:flex;flex-direction:column;">
                    <span class="cat-section-name">${child.nama}</span>
                    <span style="font-size:12px;color:gray">${childPelaksana}</span>
                </div>
            </div>
            <span class="cat-section-pct">${child.percentage}%</span>
        </div>
        <div class="cat-bar">
            <div class="cat-bar-fill" style="width:${child.percentage}%;background:linear-gradient(90deg,#e8a838,#c97b22)"></div>
        </div>
    </div>`;
        }).join('');

        const arrowHtml = children.length ? '<span class="cat-section-arrow">▾</span>' : '';

        return `
<div class="cat-section" data-cat="${id}" data-kode="${id}">
    <div class="cat-section-header" onclick="toggleCatSection(this); filterByIndikator('${id}', '${id}', false)">
        <span class="cat-section-icon"><img src="${ASSET_PATH + icon}" alt="" style="height: 100%;width: 100%;" /></span>
        <span class="cat-section-name">${nama}</span>
        <span class="cat-section-pct">${percentage}%</span>
        ${arrowHtml}
    </div>
    <div class="cat-bar">
        <div class="cat-bar-fill" style="width:${percentage}%;background:linear-gradient(90deg,#e8a838,#c97b22)"></div>
    </div>
    <div class="cat-body">${childrenHtml}</div>
</div>`;
    }

    function filterByIndikator(kode, id, isChild) {
        // Highlight active section
        document.querySelectorAll('.cat-section-header').forEach(el => {
            el.style.background = '';
        });

        let filtered;

        if (isChild) {
            // Filter by exact indikator_id (child)
            filtered = _allListIndicator.filter(item => {
                return String(item.indikator?.id) === String(id);
            });
        } else {
            // Parent clicked: include items where indikator matches parent OR any child of this parent
            filtered = _allListIndicator.filter(item => {
                const indikator = item.indikator;
                if (!indikator) return false;

                // Direct match on parent indikator
                if (String(indikator.id) === String(id)) return true;

                // Child of this parent (indikator.parent_kode matches parent kode)
                if (String(indikator.parent?.id) === String(id)) return true;

                return false;
            });
        }

        $('#location-list-panel').html(
            buildLocationList(filtered, '_indikator')
        );
    }

    function buildLocationList(items = [], prefix = '') {
        if (!items || items.length === 0) return '<div style="color:gray;font-size:13px;padding:8px;">Tidak ada data.</div>';

        const cards = items.map((data, i) => {
            const namaWilayah = [
                data.wilayah?.parent?.parent?.nama,
                data.wilayah?.parent?.nama,
                data.wilayah?.nama
            ].filter(Boolean).join(' - ');

            const fotoSebelum = data.list_detail?.[0]?.foto
                ? `${ASSET_PATH}${data.list_detail[0].foto}`
                : '';
            const fotoPerbaikan = data.list_perbaikan?.length
                ? `${ASSET_PATH}${data.list_perbaikan[data.list_perbaikan.length - 1].foto}`
                : '';

            const cardId = `loc-card-${prefix}-${data.id || i}`;

            return `
<div style="background:var(--bs-body-bg, #fff); border:1px solid #e0e0e0; border-radius:10px; margin-bottom:8px; overflow:hidden;cursor:pointer;" onclick="toggleLocPhoto('${cardId}')">

    <!-- Card Header (always visible, click to toggle photo) -->
    <div style="display:flex; flex-direction: column; align-items:center; justify-content:space-between; padding:10px 12px;">

        <div style="display:flex; flex-direction:column; gap:2px; width: 100%;">
            <span style="font-size:11px; font-weight:600; color:#111;">${namaWilayah}</span>
            <span style="font-size:10px; color:gray;">
                ${data.indikator?.parent?.nama || ''} ${data.indikator?.nama || ''}
            </span>
            <span style="font-size:10px; color:dark;">
                ${data.pelaksana?.nama || ''}
            </span>
        </div>

        <div style="display:flex; align-items:center; gap:10px; width: 100%;">
            <!-- Progress pill -->
            <div style="display:flex;flex:1; flex-direction:column; align-items:flex-end; gap:3px;">
                <span style="font-size:11px; font-weight:700; color:#111;">${data.persentase || 0}%</span>
                <div style="width:100%; height:6px; border-radius:3px; background:#e0e0e0; overflow:hidden;">
                    <div style="height:100%; width:${data.persentase || 0}%; background:linear-gradient(90deg,#5a9a6e,#3d7a55);"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jumlah row -->
    <div style="display:flex; align-items:center; gap:6px; padding:0 12px 8px;">
        <img src="${ASSET_PATH}/${data.indikator.icon}" alt="" style="height: 14px;width: 14px;" />
        <span style="font-size:10px; color:#555;">
            <strong>${data.jumlah || ''}</strong>
            ${data.indikator?.parent?.nama || ''} ${data.indikator?.nama || ''} Terdampak Bencana
        </span>
        <span id="${cardId}-arrow" style="font-size:10px; color:#999; transition:transform .2s; display:inline-block;">▾</span>
    </div>

    <!-- Before/After photos (hidden by default) -->
    <div id="${cardId}-photo" style="display:none;">
        <div style="display:flex; border-top:1px solid #eee; position:relative; height:110px;">

            <!-- Before -->
            <div style="flex:1; position:relative; overflow:hidden;">
                ${fotoSebelum
                ? `<img src="${fotoSebelum}" style="width:100%;height:100%;object-fit:cover;"
                            onerror="this.style.background='#bbb'; this.style.display='block'"/>`
                : `<div style="width:100%;height:100%;background:#ccc;"></div>`
            }
                <div style="position:absolute;bottom:5px;left:0;right:0;text-align:center;
                    font-size:10px;font-weight:700;color:#fff;letter-spacing:1px;
                    text-shadow:0 1px 3px rgba(0,0,0,.6)">SEBELUM</div>
            </div>

            <!-- Divider -->
            <div style="position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);z-index:10;">
                <div style="width:26px;height:26px;background:rgba(255,255,255,.9);border-radius:50%;
                    display:flex;align-items:center;justify-content:center;font-size:14px;color:#444;
                    box-shadow:0 1px 5px rgba(0,0,0,.2)">›</div>
            </div>

            <!-- After -->
            <div style="flex:1; position:relative; overflow:hidden;">
                ${fotoPerbaikan
                ? `<img src="${fotoPerbaikan}" style="width:100%;height:100%;object-fit:cover;"
                            onerror="this.style.background='#ccc'; this.style.display='block'"/>`
                : `<div style="width:100%;height:100%;background:#ddd;"></div>`
            }
                <div style="position:absolute;bottom:5px;left:0;right:0;text-align:center;
                    font-size:10px;font-weight:700;color:#fff;letter-spacing:1px;
                    text-shadow:0 1px 3px rgba(0,0,0,.6)">SESUDAH</div>
            </div>

        </div>
    </div>

</div>`;
        }).join('');

        return cards;
    }

    function buildMasalahKritisList(items = [], prefix = '') {
        if (!items || items.length === 0) return '';

        const cards = items.map((data, i) => {
            const namaWilayah = [
                data.wilayah?.parent?.parent?.nama,
                data.wilayah?.parent?.nama,
                data.wilayah?.nama
            ].filter(Boolean).join(' - ');

            const cardId = `mk-card-${prefix}-${data.id || i}`;
            const fotoSebelum = data.foto ? `${ASSET_PATH}${data.foto}` : '';
            const fotoSesudah = data.foto_sesudah ? `${ASSET_PATH}${data.foto_sesudah}` : '';

            return `
<div style="background:var(--bs-body-bg, #fff); border:1px solid #f5c6c6; border-left: 3px solid #dc3545; border-radius:10px; margin-bottom:8px; overflow:hidden; cursor:pointer;"
     onclick="toggleMKPhoto('${cardId}')">

    <!-- Card Header -->
    <div style="display:flex; flex-direction:column; gap:4px; padding:10px 12px;">
        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
            <div style="display:flex; flex-direction:column; gap:2px;">
                <span style="font-size:11px; font-weight:600; color:#111;">${namaWilayah}</span>
                <span style="font-size:10px; color:gray;">
                    ${data.indikator?.parent?.nama || ''} ${data.indikator?.nama || ''}
                </span>
                <span style="font-size:10px; color:#555;">${data.pelaksana?.nama || ''}</span>
            </div>
        </div>

        <!-- Jumlah row -->
        <div style="display:flex; align-items:center; gap:6px;">
            ${data.indikator?.icon
                ? `<img src="${ASSET_PATH}/${data.indikator.icon}" alt="" style="height:14px;width:14px;" />`
                : ''}
            <span style="font-size:10px; color:#555;">
                <strong>${data.jumlah || ''} ${data.satuan || ''}</strong>
                ${data.indikator?.parent?.nama || ''} ${data.indikator?.nama || ''} Bermasalah
            </span>
            <span id="${cardId}-arrow" style="font-size:10px; color:#999; transition:transform .2s; display:inline-block; margin-left:auto;">▾</span>
        </div>

        <!-- Keterangan -->
        ${data.keterangan ? `
        <div style="font-size:10px; color:#777; background:#fff8f8; border-radius:6px; padding:4px 8px; border:1px solid #f5c6c6;">
            ${data.keterangan}
        </div>` : ''}
    </div>

    <!-- Before/After photos (hidden by default) -->
    <div id="${cardId}-photo" style="display:none;">
        <div style="display:flex; border-top:1px solid #eee; position:relative; height:110px;">

            <!-- Sebelum -->
            <div style="flex:1; position:relative; overflow:hidden;">
                ${fotoSebelum
                ? `<img src="${fotoSebelum}" style="width:100%;height:100%;object-fit:cover;"
                           onerror="this.style.background='#bbb';this.style.display='block'"/>`
                : `<div style="width:100%;height:100%;background:#ccc;"></div>`}
                <div style="position:absolute;bottom:5px;left:0;right:0;text-align:center;
                    font-size:10px;font-weight:700;color:#fff;letter-spacing:1px;
                    text-shadow:0 1px 3px rgba(0,0,0,.6)">SEBELUM</div>
            </div>

            <!-- Divider -->
            <div style="position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);z-index:10;">
                <div style="width:26px;height:26px;background:rgba(255,255,255,.9);border-radius:50%;
                    display:flex;align-items:center;justify-content:center;font-size:14px;color:#444;
                    box-shadow:0 1px 5px rgba(0,0,0,.2)">›</div>
            </div>

            <!-- Sesudah -->
            <div style="flex:1; position:relative; overflow:hidden;">
                ${fotoSesudah
                ? `<img src="${fotoSesudah}" style="width:100%;height:100%;object-fit:cover;"
                           onerror="this.style.background='#ccc';this.style.display='block'"/>`
                : `<div style="width:100%;height:100%;background:#ddd;"></div>`}
                <div style="position:absolute;bottom:5px;left:0;right:0;text-align:center;
                    font-size:10px;font-weight:700;color:#fff;letter-spacing:1px;
                    text-shadow:0 1px 3px rgba(0,0,0,.6)">SESUDAH</div>
            </div>
        </div>
    </div>
</div>`;
        }).join('');

        return `
<div style="background:#fff5f5; border:1px solid #f5c6c6; border-radius:10px; padding:10px; margin-bottom:4px;">
    <div style="display:flex; align-items:center; gap:6px; margin-bottom:8px;">
        <span style="font-size:12px; font-weight:700; color:#dc3545;">⚠ Masalah Kritis</span>
        <span style="font-size:11px; color:#dc3545; background:#fce8e8; border-radius:10px; padding:1px 8px; font-weight:600;">
            ${items.length}
        </span>
    </div>
    ${cards}
</div>`;
    }

    function toggleMKPhoto(cardId) {
        const photo = document.getElementById(`${cardId}-photo`);
        const arrow = document.getElementById(`${cardId}-arrow`);
        if (!photo) return;
        const isOpen = photo.style.display !== 'none';
        photo.style.display = isOpen ? 'none' : 'block';
        if (arrow) arrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
    }

    function toggleLocPhoto(cardId) {
        console.log(cardId);
        const photo = document.getElementById(`${cardId}-photo`);
        const arrow = document.getElementById(`${cardId}-arrow`);
        if (!photo) return;

        const isOpen = photo.style.display !== 'none';
        photo.style.display = isOpen ? 'none' : 'block';
        if (arrow) arrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
    }

    let _allListIndicatorPelaksana = [];

    function renderPelaksana(list, listIndicator, listMasalahKritis) {
        _allListIndicatorPelaksana = listIndicator || [];

        $('#panel_scroll3').html(`
        <div style="display: flex; flex-direction: row; gap: 12px;">
            <div style="flex: 1;">
                ${list.map((p, i) => `
                <div class="cat-section" data-cat="pel_${p.id}">
                    <div class="cat-section-header" onclick="toggleCatSection(this); filterIndikatorPelaksanaPanel('${p.id}')">
                        <span class="cat-section-name">${i + 1}. ${p.nama}</span>
                        <span class="cat-section-pct">${p.percentage}%</span>
                    </div>
                    <div class="cat-bar">
                        <div class="cat-bar-fill" style="width:${p.percentage}%;background:linear-gradient(90deg,#e8a838,#c97b22)"></div>
                    </div>
                </div>`).join('')}
            </div>
            <div style="flex: 1; display: flex; flex-direction: column; gap: 10px;">
                <div id="masalah-kritis-pelaksana-panel">
                    ${buildMasalahKritisList(listMasalahKritis, '_pelaksana')}
                </div>
                <div style="flex: 1;" id="location-list-panel-pelaksana">
                    ${buildLocationList(_allListIndicatorPelaksana, '_pelaksana')}
                </div>
            </div>

        </div>
    `);
    }

    function filterIndikatorPelaksanaPanel(pelaksanaId) {
        const filtered = _allListIndicatorPelaksana.filter(item =>
            String(item.pelaksana?.id) === String(pelaksanaId)
        );

        $('#location-list-panel-pelaksana').html(
            buildLocationList(filtered, '_pelaksana')
        );
    }

    // ─── GeoJSON helpers ──────────────────────────────────────────────────────────
    function extractGeometry(data) {
        if (data.type === 'FeatureCollection') {
            return data.features.length === 1 ? data.features[0].geometry : null;
        }
        return data.type === 'Feature' ? data.geometry : data;
    }

    function addLabel(layer, name) {
        const center = layer.getBounds().getCenter();
        layer._labelMarker = L.marker(center, {
            icon: L.divIcon({
                className: '',
                html: `<div class="sub-label">${name.toUpperCase()}</div>`,
                iconSize: null,
                iconAnchor: [0, 0],
            }),
            interactive: false,
            zIndexOffset: 200,
        }).addTo(map);
    }

    // ─── Layer clearing ───────────────────────────────────────────────────────────
    function clearLayerGroup(group) {
        Object.keys(group).forEach(key => {
            group[key].forEach(layer => {
                if (layer._labelMarker) map.removeLayer(layer._labelMarker);
                map.removeLayer(layer);
            });
            delete group[key];
        });
    }

    function clearAllSubLayers() {
        clearLayerGroup(subLayers);
        clearLayerGroup(subLayers2);
        clearLayerGroup(subLayers3);
    }

    // ─── Generic sub-region loader ────────────────────────────────────────────────

    async function loadSubRegionSet(opts) {
        const { subMeta, layerStore, slugKey, parentColor, dataParam, onLayerClick, buildSidebarHtml } = opts;
        if (!subMeta || !Object.keys(subMeta).length) return;



        fetchMapData(dataParam);

        layerStore[slugKey] = [];
        let activeLayer = null;

        $('#panel-scroll2').html(buildSidebarHtml());

        total_atensi = 0;
        total_mendekati = 0;
        total_normal = 0;

        const loads = Object.values(subMeta).map(async sub => {
            if (sub.percentage <= 30) total_atensi++;
            if (sub.percentage >= 31 && sub.percentage <= 70) total_mendekati++;
            if (sub.percentage >= 71) total_normal++;
            $('#atensi_count').text(total_atensi);
            $('#mendekati_count').text(total_mendekati);
            $('#normal_count').text(total_normal);

            try {
                const geojsonData = await fetchGeo('/' + sub.polygon);
                const geometry    = extractGeometry(geojsonData);
                const color       = sub.color || parentColor;

                const layer = L.geoJSON(geometry || geojsonData, {
                    style: { color, weight: 1.5, opacity: 0.9, fillColor: color, fillOpacity: 0.3 },
                });

                layer._subData = sub;
                addLabel(layer, sub.nama);

                layer.on('click', e => {
                    L.DomEvent.stopPropagation(e);
                    activeLayer = layer;

                    layerStore[slugKey].forEach(l => {
                        l.setStyle({ fillOpacity: 0.04, weight: 1, opacity: 0.2 });
                        if (l._labelMarker) map.removeLayer(l._labelMarker);
                    });
                    layer.setStyle({ fillOpacity: 0.5, weight: 2.5, opacity: 1 });
                    if (layer._labelMarker) layer._labelMarker.addTo(map);

                    map.flyToBounds(layer.getBounds(), { padding: [30, 30], duration: 0.6, easeLinearity: 0.4 });
                    onLayerClick(sub, layer);
                });

                layer.on('mouseover', e => {
                    if (activeLayer !== layer) e.target.setStyle({ fillOpacity: activeLayer ? 0.25 : 0.5, weight: 2 });
                });
                layer.on('mouseout', e => {
                    if (activeLayer !== layer) e.target.setStyle({
                        fillOpacity: activeLayer ? 0.04 : 0.3,
                        weight:      activeLayer ? 1    : 1.5,
                        opacity:     activeLayer ? 0.2  : 0.9,
                    });
                });

                layer.addTo(map);
                layerStore[slugKey].push(layer);
            } catch (err) {
                console.warn('GeoJSON load failed:', sub.polygon, err);
            }
        });

        await Promise.all(loads);
    }

    // ─── Level loaders ────────────────────────────────────────────────────────────
    function loadSubRegions(provSlug) {
        clearLayerGroup(subLayers);
        clearLayerGroup(subLayers2);
        clearLayerGroup(subLayers3);

        const meta = PROVINCE_META[provSlug];
        $('.province_name').text(meta.nama);
        $('.province_name').text(meta.nama);
        $('.kabupaten_name').text('');
        $('.kecamatan_name').text('');

        if (!meta?.sub || !Object.keys(meta.sub).length) return;

        Object.values(geoLayers).forEach(l => map.removeLayer(l));

        loadSubRegionSet({
            subMeta:    meta.sub,
            layerStore: subLayers,
            slugKey:    provSlug,
            parentColor: meta.color,
            dataParam:  `provinsi_id=${meta.id}`,
            onLayerClick: sub => loadDistrict(provSlug, sub.kode),
            buildSidebarHtml: () =>
                `<div class="leg-item back-btn" data-action="filterProv" data-value="all" style="padding-bottom:5px;cursor:pointer;">← Semua Provinsi</div>` +
                Object.values(meta.sub).map(sub =>
                    `<div class="leg-item" data-action="loadDistrict" data-prov="${provSlug}" data-sub="${sub.kode}" style="cursor:pointer;">
                    <div class="leg-dot" style="background:${sub.color}"></div>${sub.nama}
                </div>`
                ).join(''),
        });
    }

    function loadDistrict(provSlug, kabSlug) {
        clearLayerGroup(subLayers2);
        clearLayerGroup(subLayers3);

        const meta = PROVINCE_META[provSlug]?.sub?.[kabSlug];
        if (!meta?.sub || !Object.keys(meta.sub).length) return;

        $('.kabupaten_name').text(meta.nama);
        $('.kecamatan_name').text('');

        if (subLayers[provSlug]) {
            subLayers[provSlug].forEach(l => {
                l.setStyle({ fillOpacity: 0.04, weight: 1, opacity: 0.2 });
                if (l._labelMarker) map.removeLayer(l._labelMarker);
            });
        }

        loadSubRegionSet({
            subMeta:    meta.sub,
            layerStore: subLayers2,
            slugKey:    kabSlug,
            parentColor: meta.color,
            dataParam:  `kabupaten_id=${meta.id}`,
            onLayerClick: sub => loadKelurahan(provSlug, kabSlug, sub.kode),
            buildSidebarHtml: () =>
                `<div class="leg-item" data-action="loadSubRegions" data-prov="${provSlug}" style="padding-bottom:5px;cursor:pointer;">← Kembali ke Kabupaten</div>` +
                Object.values(meta.sub).map(sub =>
                    `<div class="leg-item" data-action="loadDistrict" data-prov="${provSlug}" data-sub="${sub.kode}" style="cursor:pointer;">
                    <div class="leg-dot" style="background:${sub.color}"></div>${sub.nama}
                </div>`
                ).join(''),
        });
    }

    function loadKelurahan(provSlug, kabSlug, kecSlug) {
        clearLayerGroup(subLayers3);

        const meta = PROVINCE_META[provSlug]?.sub?.[kabSlug]?.sub?.[kecSlug];
        if (!meta?.sub || !Object.keys(meta.sub).length) return;

        $('.kecamatan_name').text(meta.nama);

        if (subLayers2[kabSlug]) {
            subLayers2[kabSlug].forEach(l => {
                l.setStyle({ fillOpacity: 0.04, weight: 1, opacity: 0.2 });
                if (l._labelMarker) map.removeLayer(l._labelMarker);
            });
        }

        loadSubRegionSet({
            subMeta:    meta.sub,
            layerStore: subLayers3,
            slugKey:    kecSlug,
            parentColor: meta.color,
            dataParam:  `kecamatan_id=${meta.id}`,
            onLayerClick: () => {},    // no deeper level
            buildSidebarHtml: () =>
                `<div class="leg-item" data-action="loadDistrict" data-prov="${provSlug}" data-sub="${kabSlug}" style="padding-bottom:5px;cursor:pointer;">← Kembali ke Kecamatan</div>` +
                Object.values(meta.sub).map(sub =>
                    `<div class="leg-item" style="cursor:pointer;">
                    <div class="leg-dot" style="background:${sub.color}"></div>${sub.nama}
                </div>`
                ).join(''),
        });
    }

    // ─── Province loaders ─────────────────────────────────────────────────────────
    function loadGeoJSONProvince(slug) {
        const meta = PROVINCE_META[slug];
        if (meta.percentage <= 30) total_atensi++;
        if (meta.percentage >= 31 && meta.percentage <= 70) total_mendekati++;
        if (meta.percentage >= 71) total_normal++;
        $('#atensi_count').text(total_atensi);
        $('#mendekati_count').text(total_mendekati);
        $('#normal_count').text(total_normal);

        fetchGeo('/' + meta.polygon)
            .then(data => {
                const layer = L.geoJSON(data, {
                    style: { color: meta.color, weight: 2, opacity: 0.85, fillColor: meta.color, fillOpacity: 0.18 },
                });

                layer.on('click',     e => { L.DomEvent.stopPropagation(e); filterProv(slug); });
                layer.on('mouseover', e => { if (activeProv !== slug) e.target.setStyle({ fillOpacity: 0.32, weight: 3 }); });
                layer.on('mouseout',  e => { if (activeProv !== slug) e.target.setStyle({ fillOpacity: 0.18, weight: 2 }); });

                layer.addTo(map);
                geoLayers[slug] = layer;

                const allLoaded = Object.keys(PROVINCE_META).every(s => geoLayers[s]);
                if (allLoaded) {
                    map.flyToBounds(L.featureGroup(Object.values(geoLayers)).getBounds(), { padding: [20, 20], duration: 0.6, easeLinearity: 0.4 });
                }
            })
            .catch(err => console.error('Province GeoJSON error:', slug, err));
    }

    Object.keys(PROVINCE_META).forEach(loadGeoJSONProvince);

    // ─── Filter / zoom ────────────────────────────────────────────────────────────
    function filterProv(slug) {
        activeProv = slug;

        if (slug === 'all') {
            clearAllSubLayers();
            fetchMapData('');

            const $scroll = $('#panel-scroll2').empty();

            total_atensi = 0;
            total_mendekati = 0;
            total_normal = 0;

            Object.values(PROVINCE_META).forEach(p => {
                if (p.percentage <= 30) total_atensi++;
                if (p.percentage >= 31 && p.percentage <= 70) total_mendekati++;
                if (p.percentage >= 71) total_normal++;
                $('#atensi_count').text(total_atensi);
                $('#mendekati_count').text(total_mendekati);
                $('#normal_count').text(total_normal);

                $scroll.append(
                    `<div class="leg-item" data-action="filterProv" data-value="${p.kode}" style="cursor:pointer;">
                    <div class="leg-dot" style="background:${p.color}"></div>${p.nama}
                </div>`
                );
            });

            Object.entries(geoLayers).forEach(([, l]) => {
                l.addTo(map).setStyle({ fillOpacity: 0.18, weight: 2, opacity: 0.85 });
            });
            map.flyToBounds(L.featureGroup(Object.values(geoLayers)).getBounds(), { padding: [20, 20], duration: 0.6, easeLinearity: 0.4 });
            $('#prov-popup').removeClass('visible');
            return;
        }

        if (geoLayers[slug]) {
            map.flyToBounds(geoLayers[slug].getBounds(), { padding: [20, 20], duration: 0.6, easeLinearity: 0.4 });
        }
        loadSubRegions(slug);
    }

    // ─── Map click reset ──────────────────────────────────────────────────────────
    map.on('click', () => {
        popupTimeout = setTimeout(() => $('#prov-popup').removeClass('visible'), 200);
        filterProv('all');
    });

    // ─── Panel management ─────────────────────────────────────────────────────────
    function closeAllPanels() {
        document.querySelectorAll('[id^="summary-panel"]').forEach(el => (el.style.display = 'none'));
        document.querySelectorAll('#cat-sidebar .cat-btn').forEach(btn => btn.classList.remove('active'));
    }

    function toggleSidePanel(panelId, btn) {
        const panel = document.getElementById(panelId);
        if (!panel) return;
        const wasOpen = panel.style.display !== 'none';
        if (!wasOpen) {
            panel.style.display = 'block';
        }
    }

    function open_search(panelId) {
        swal.fire({
            icon: 'warning',
            title: 'Under Construction'
        })
    }

    function open_data(panelId) {
        toggleSidePanel(panelId, this);
    }

    // ─── Delegated event bindings (replaces all inline onclick) ──────────────────
    $(document).on('click', '#cat-sidebar .cat-btn', function () {
        closeAllPanels();

        const alt = $(this).data('alt');

        if (alt === 'donate') {
            swal.fire({
                icon: 'warning',
                title: 'Under Construction'
            })
        } else {
            activeCat = alt;
            $('.cat-action').hide();
            $('#cat_action_' + alt).show();
            $('.cat-btn').attr('class', 'cat-btn');
            $('#cat_btn_' + alt).attr('class', 'cat-btn active');

            filterProv('all');
        }
    });

    $(document).on('click', '.panel-close', function () {
        const panelId = $(this).data('panel');
        const panel   = document.getElementById(panelId);
        if (panel) panel.style.display = 'none';
        $(this).closest('[id^="summary-panel"]').find('.cat-btn').removeClass('active');
        closeAllPanels();
    });

    // Delegated navigation in panel-scroll2 (replaces all onclick="loadDistrict(...)")
    $(document).on('click', '#panel-scroll2 .leg-item[data-action]', function () {
        const action = $(this).data('action');
        const prov   = $(this).data('prov');
        const sub    = $(this).data('sub');
        const value  = $(this).data('value');

        switch (action) {
            case 'filterProv':    filterProv(value); break;
            case 'loadSubRegions': loadSubRegions(prov); break;
            case 'loadDistrict':  loadDistrict(prov, sub); break;
            case 'loadKelurahan': loadKelurahan(prov, $(this).data('kab'), sub); break;
        }
    });

    // Wilayah panel items (static, from Blade)
    $(document).on('click', '#panel-scroll2 .leg-item[data-kode]', function () {
        filterProv($(this).data('kode'));
    });

    // Cat section expand/collapse
    function toggleCatSection(header) {
        header.closest('.cat-section').classList.toggle('open');
    }

    // ─── Bootstrap data fetch ─────────────────────────────────────────────────────
    fetchMapData('');
</script>
</body>
</html>
