// ─── VARS ────────────────────────────────────────────────────────────────────
let polygonLayers = {};
let activeCat = '';
let activeKode = '';
let activeProvinsi = null, activeKabupaten = null, activeKecamatan = null;
let total_atensi = 0, total_mendekati = 0, total_normal = 0;
let markers = [], markersData = [];
let listAnggaranDaerah = [];
let listPaketPekerjaan = [];

// ─── Map init ─────────────────────────────────────────────────────────────────
let map = new maplibregl.Map({
    container: 'map',
    style: {
        'version': 8,
        'sources': {
            'raster-tiles': {
                'type': 'raster',
                'tiles': [
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'
                ],
                'tileSize': 256,
                'attribution': '&copy; OpenStreetMap contributors'
            }
        },
        'layers': [
            {
                'id': 'simple-tiles',
                'type': 'raster',
                'source': 'raster-tiles',
                'minzoom': 0,
                'maxzoom': 22
            }
        ]
    },
    center: [98.5, 2.5],
    zoom: 5
});
map.addControl(new maplibregl.NavigationControl(), 'bottom-right');

const TILE_SOURCES = {
    'osm': {
        tiles: ['https://tile.openstreetmap.org/{z}/{x}/{y}.png'],
        tileSize: 256,
        attribution: '&copy; OpenStreetMap contributors'
    },
    '3d': {
        tiles: [
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'
        ],
        tileSize: 256,
        attribution: '&copy; Esri &mdash; Source: Esri, USGS, NOAA'
    }
};

function changeTile(map, type) {
    const source = TILE_SOURCES[type];
    if (!source) {
        console.warn(`Unknown tile type: "${type}". Available: ${Object.keys(TILE_SOURCES).join(', ')}`);
        return;
    }

    map.getSource('raster-tiles').setTiles(source.tiles);
}

// ─── Fetch Wilayah ─────────────────────────────────────────────────────────────────
function get_wilayah(kode = '', nama = '') {
    Swal.fire({
        title: 'Loading',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => Swal.showLoading()
    });
    activeKode = kode;

    clearMarkers();
    clear_all_polygon();

    if (kode === '') {
        activeProvinsi = null;
        activeKabupaten = null;
        activeKecamatan = null;
    }

    if (kode.split('.').length === 1) {
        let nama = '';
        if (kode.toString() === "11") nama = "Aceh";
        if (kode.toString() === "12") nama = "Sumatera Utara";
        if (kode.toString() === "13") nama = "Sumatera Barat";

        $('.provinsi_option').val(nama);
        // $('.provinsi_option').each(function() {
        //     $(this).find('option[data-id="' + nama + '"]').prop('selected', true);
        // });
    }

    if (kode.split('.').length === 1) {
        activeProvinsi = { kode, nama };
    }
    if (kode.split('.').length === 2) {
        activeKabupaten = { kode, nama };
        $('.kabupaten_name').html(nama);
    }
    if (kode.split('.').length === 3) {
        activeKecamatan = { kode, nama };
        $('.kecamatan_name').html(nama);
    }

    $('#list_item_wilayah').html('');
    $.get(BASE_URL + 'map/get_wilayah?kode=' + kode, (result) => {
        total_atensi = 0;
        total_mendekati = 0;
        total_normal = 0;
        $.each(result, (i, val) => {
            display_polygon_wilayah(val, result.length);
        });
    });
}

function display_polygon_wilayah(item, totalLayer) {
    if (item.persentase <= 30) total_atensi++;
    if (item.persentase >= 31 && item.persentase <= 70) total_mendekati++;
    if (item.persentase >= 71) total_normal++;
    $('#atensi_count').text(total_atensi);
    $('#mendekati_count').text(total_mendekati);
    $('#normal_count').text(total_normal);

    if (item.kode.split('.').length < 3) {
        $('#list_item_wilayah').append(`<div class="leg-item" onclick="get_wilayah('${item.kode}', '${item.nama}')" style="cursor:pointer;"><div class="leg-dot" style="background:${item.warna}"></div>${item.nama}</div>`);
    } else {
        $('#list_item_wilayah').append(`<div class="leg-item" style="cursor:pointer;"><div class="leg-dot" style="background:${item.warna}"></div>${item.nama}</div>`);
    }

    fetchGeo(BASE_URL + item.polygon)
        .then(function (data) {
            // Safety check: If the user navigated elsewhere, don't add these layers
            if (activeKode !== '' && !item.kode.startsWith(activeKode) && item.kode !== activeKode) {
                return;
            }
            // Additional safety: if the map was cleared but a slow request finished
            if (map.getSource('source-' + item.kode)) return;

            const sourceId = 'source-' + item.kode;
            const fillLayerId = 'fill-' + item.kode;
            const lineLayerId = 'line-' + item.kode;
            const symbolLayerId = 'label-' + item.kode;

            map.addSource(sourceId, {
                'type': 'geojson',
                'data': data
            });

            map.addLayer({
                'id': fillLayerId,
                'type': 'fill',
                'source': sourceId,
                'layout': {},
                'paint': {
                    'fill-color': item.warna,
                    'fill-opacity': 0.08
                }
            });

            map.addLayer({
                'id': lineLayerId,
                'type': 'line',
                'source': sourceId,
                'layout': {},
                'paint': {
                    'line-color': item.warna,
                    'line-width': 2,
                    'line-opacity': 0.85
                }
            });

            // Add label using Marker (safer for raster styles without glyphs)
            // Calculate center from coordinates for the label
            let labelCenter = [0, 0];
            let coordCount = 0;
            if (data.type === 'FeatureCollection') {
                data.features.forEach(f => {
                    if (f.geometry.type === 'Polygon') {
                        f.geometry.coordinates[0].forEach(c => {
                            labelCenter[0] += c[0];
                            labelCenter[1] += c[1];
                            coordCount++;
                        });
                    } else if (f.geometry.type === 'MultiPolygon') {
                        f.geometry.coordinates.forEach(p => p[0].forEach(c => {
                            labelCenter[0] += c[0];
                            labelCenter[1] += c[1];
                            coordCount++;
                        }));
                    }
                });
            }
            if (coordCount > 0) {
                labelCenter[0] /= coordCount;
                labelCenter[1] /= coordCount;

                const labelEl = document.createElement('div');
                labelEl.className = 'sub-label';
                labelEl.innerHTML = (item.nama || '').toUpperCase();
                labelEl.style.color = '#000';
                labelEl.style.fontWeight = 'bold';
                labelEl.style.textShadow = '1 1 2px #fff';
                labelEl.style.pointerEvents = 'none';
                labelEl.style.whiteSpace = 'nowrap';
                labelEl.style.fontSize = '12px';
                labelEl.style.fontFamily = 'DM Sans, sans-serif';

                const labelMarker = new maplibregl.Marker({
                    element: labelEl
                })
                    .setLngLat(labelCenter)
                    .addTo(map);

                polygonLayers[item.kode] = {
                    sourceId, fillLayerId, lineLayerId, labelMarker
                };
            } else {
                polygonLayers[item.kode] = {
                    sourceId, fillLayerId, lineLayerId
                };
            }

            // Click event for the fill layer
            map.on('click', fillLayerId, function (e) {
                if (item.kode.split('.').length <= 2) {
                    get_wilayah(item.kode, item.nama);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak ada sub dibawah kelurahan / desa',
                    });
                }
            });

            // Hover effects
            map.on('mouseenter', fillLayerId, function () {
                map.getCanvas().style.cursor = 'pointer';
                map.setPaintProperty(fillLayerId, 'fill-opacity', 0.32);
                map.setPaintProperty(lineLayerId, 'line-width', 3);
            });

            map.on('mouseleave', fillLayerId, function () {
                map.getCanvas().style.cursor = '';
                map.setPaintProperty(fillLayerId, 'fill-opacity', 0.18);
                map.setPaintProperty(lineLayerId, 'line-width', 2);
            });

            if (totalLayer === Object.keys(polygonLayers).length) {
                // Bounds calculation in MapLibre is a bit different
                const coordinates = [];
                Object.values(polygonLayers).forEach(layerObj => {
                    const feat = map.getSource(layerObj.sourceId)._data;
                    if (feat.type === 'FeatureCollection') {
                        feat.features.forEach(f => {
                            if (f.geometry.type === 'Polygon') {
                                f.geometry.coordinates[0].forEach(c => coordinates.push(c));
                            } else if (f.geometry.type === 'MultiPolygon') {
                                f.geometry.coordinates.forEach(p => p[0].forEach(c => coordinates.push(c)));
                            }
                        });
                    }
                });

                if (coordinates.length > 0) {
                    const bounds = coordinates.reduce((bounds, coord) => {
                        return bounds.extend(coord);
                    }, new maplibregl.LngLatBounds(coordinates[0], coordinates[0]));
                    map.fitBounds(bounds, { padding: 10 });
                }

                Swal.close();
            }
        })
        .catch(function (err) { Swal.close(); console.error('GeoJSON error:', item.polygon, err); });
}

function clear_all_polygon() {
    // 1. Remove markers first
    Object.keys(polygonLayers).forEach(function (key) {
        const layers = polygonLayers[key];
        if (layers && layers.labelMarker) {
            layers.labelMarker.remove();
        }
    });
    polygonLayers = {};

    // 2. Remove layers matching our pattern
    const style = map.getStyle();
    if (style && style.layers) {
        style.layers.forEach(function (layer) {
            if (layer.id.startsWith('fill-') || layer.id.startsWith('line-') || layer.id.startsWith('label-')) {
                map.removeLayer(layer.id);
            }
        });
    }

    // 3. Remove sources matching our pattern
    if (style && style.sources) {
        Object.keys(style.sources).forEach(function (sourceId) {
            if (sourceId.startsWith('source-')) {
                map.removeSource(sourceId);
            }
        });
    }
}

function select_provinsi(alt) {
    let id = $('#provinsi_' + alt).find('option:selected').attr('data-id');
    let kode = $('#provinsi_' + alt).find('option:selected').val();


    $('.provinsi_option').each(function () {
        $(this).find('option[data-id="' + id + '"]').prop('selected', true);
    });

    get_wilayah(id);
}

function display_provinsi() {
    if (activeProvinsi !== null) {
        get_wilayah(activeProvinsi.kode, activeProvinsi.nama);
        activeKabupaten = null;
        activeKecamatan = null
        $('.kabupaten_name').html('');
        $('.kecamatan_name').html('');
    }
}

function display_kabupaten() {
    if (activeKabupaten !== null) {
        get_wilayah(activeKabupaten.kode, activeKabupaten.nama);
        activeKecamatan = null;
        $('.kecamatan_name').html('');
        get_anggaran(activeKabupaten.kode);
    }
}

function display_kecamatan() {
    if (activeKecamatan !== null) get_wilayah(activeKecamatan.kode, activeKecamatan.nama);
}

function get_indikator(kode) {
    Swal.fire({
        title: 'Loading',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => Swal.showLoading()
    });
    console.log(BASE_URL + 'map/get_indikator?wilayah_kode=' + kode);
    $.get(BASE_URL + 'map/get_indikator?wilayah_kode=' + kode, (result) => {
        let list_indikator = result.list_indikator;
        let list_pelaksana = result.list_pelaksana;
        let list_masalah_kritis = result.list_masalah_kritis;
        let list_sektor_terdampak = list_indikator.flatMap(item => item.list_sektor_terdampak);
        renderIndicators(list_indikator, list_sektor_terdampak, list_masalah_kritis);
        renderPelaksana(list_pelaksana, list_sektor_terdampak, list_masalah_kritis);
        if (activeCat === 'indikator' || activeCat === 'pelaksana') {
            drawMarkersBencana(list_sektor_terdampak);
            Swal.close();
        }
    });
}

function get_pekerjaan(kode) {
    Swal.fire({
        title: 'Loading',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => Swal.showLoading()
    });

    $.get(BASE_URL + 'map/get_pekerjaan?wilayah_kode=' + kode, (result) => {
        let list_pelaksana = result;
        let list_pekerjaan = list_pelaksana.flatMap(item => item.list_pekerjaan);

        listPaketPekerjaan = list_pekerjaan;
        renderPaketPekerjaan(list_pelaksana, list_pekerjaan);
        if (activeCat === 'pekerjaan') {
            drawMarkersBencanaPekerjaan(list_pekerjaan);
            Swal.close();
        }
    });
}

function get_anggaran(kode) {
    $.get(BASE_URL + 'map/get_anggaran?wilayah_kode=' + kode, (result) => {
        listAnggaranDaerah = result;

        if (activeCat === 'tkd') {
            clearMarkers();
            drawMarkersTkd(listAnggaranDaerah);
        }
    });
}

//
// // ─── Cache Layer (localStorage) ─────────────────────────────────────────────
// const CACHE_PREFIX = 'map_cache::';
// const CACHE_TTL_MS = 240 * 60 * 1000; // 240 minutes, adjust as needed
//
// function getCached(key) {
//     try {
//         const raw = localStorage.getItem(CACHE_PREFIX + key);
//         if (!raw) return null;
//
//         const { data, expiredAt } = JSON.parse(raw);
//         if (Date.now() > expiredAt) {
//             localStorage.removeItem(CACHE_PREFIX + key);
//             return null;
//         }
//         return data;
//     } catch {
//         return null;
//     }
// }
//
// function setCache(key, data) {
//     try {
//         const payload = {
//             data,
//             expiredAt: Date.now() + CACHE_TTL_MS
//         };
//         localStorage.setItem(CACHE_PREFIX + key, JSON.stringify(payload));
//     } catch (e) {
//         // localStorage might be full, fail silently
//         console.warn('Cache write failed:', e);
//     }
// }
//
// function clearCache() {
//     Object.keys(localStorage)
//         .filter(k => k.startsWith(CACHE_PREFIX))
//         .forEach(k => localStorage.removeItem(k));
// }
//
// function cacheKey(endpoint, kode) {
//     return `${endpoint}::${kode}`;
// }
//
// // ─── Functions ────────────────────────────────────────────────────────────────
// function get_indikator(kode) {
//     const key = cacheKey('indikator', kode);
//     const cached = getCached(key);
//
//     if (cached) {
//         _process_indikator(cached);
//         return;
//     }
//
//     Swal.fire({
//         title: 'Loading',
//         allowOutsideClick: false,
//         showConfirmButton: false,
//         didOpen: () => Swal.showLoading()
//     });
//
//     $.get(BASE_URL + 'map/get_indikator?wilayah_kode=' + kode, (result) => {
//         setCache(key, result);
//         _process_indikator(result);
//     });
// }
//
// function _process_indikator(result) {
//     let list_indikator = result.list_indikator;
//     let list_pelaksana = result.list_pelaksana;
//     let list_masalah_kritis = result.list_masalah_kritis;
//     let list_sektor_terdampak = list_indikator.flatMap(item => item.list_sektor_terdampak);
//
//     renderIndicators(list_indikator, list_sektor_terdampak, list_masalah_kritis);
//     renderPelaksana(list_pelaksana, list_sektor_terdampak, list_masalah_kritis);
//
//     if (activeCat === 'indikator' || activeCat === 'pelaksana') {
//         drawMarkersBencana(list_sektor_terdampak);
//         Swal.close();
//     }
// }
//
// function get_pekerjaan(kode) {
//     const key = cacheKey('pekerjaan', kode);
//     const cached = getCached(key);
//
//     if (cached) {
//         _process_pekerjaan(cached);
//         return;
//     }
//
//     Swal.fire({
//         title: 'Loading',
//         allowOutsideClick: false,
//         showConfirmButton: false,
//         didOpen: () => Swal.showLoading()
//     });
//
//     $.get(BASE_URL + 'map/get_pekerjaan?wilayah_kode=' + kode, (result) => {
//         setCache(key, result);
//         _process_pekerjaan(result);
//     });
// }
//
// function _process_pekerjaan(result) {
//     let list_pelaksana = result;
//     let list_pekerjaan = list_pelaksana.flatMap(item => item.list_pekerjaan);
//
//     listPaketPekerjaan = list_pekerjaan;
//     renderPaketPekerjaan(list_pelaksana, list_pekerjaan);
//
//     if (activeCat === 'pekerjaan') {
//         drawMarkersBencanaPekerjaan(list_pekerjaan);
//         Swal.close();
//     }
// }
//
// function get_anggaran(kode) {
//     const key = cacheKey('anggaran', kode);
//     const cached = getCached(key);
//
//     if (cached) {
//         _process_anggaran(cached);
//         return;
//     }
//
//     $.get(BASE_URL + 'map/get_anggaran?wilayah_kode=' + kode, (result) => {
//         setCache(key, result);
//         _process_anggaran(result);
//     });
// }
//
// function _process_anggaran(result) {
//     listAnggaranDaerah = result;
//
//     if (activeCat === 'tkd') {
//         clearMarkers();
//         drawMarkersTkd(listAnggaranDaerah);
//     }
// }


// ─── Markers ──────────────────────────────────────────────────────────────────
function addCustomMarker(latlng, iconUrl, data, type = 'bencana') {
    const el = document.createElement('div');
    el.className = 'marker';
    el.style.backgroundImage = `url(${iconUrl})`;
    el.style.width = '32px';
    el.style.height = '32px';
    el.style.backgroundSize = '100%';
    el.style.cursor = 'pointer';

    let content = '';
    if (type === 'bencana') content = buildMarkerPopupBencana(data);
    if (type === 'pekerjaan') content = buildMarkerPopupPekerjaan(data);
    if (type === 'anggaran') content = buildMarkerPopupAnggaran(data);

    const popup = new maplibregl.Popup({ offset: 25, maxWidth: '400px', className: 'custom-popup' })
        .setHTML(content);

    const marker = new maplibregl.Marker({ element: el })
        .setLngLat([latlng[1], latlng[0]])
        .setPopup(popup)
        .addTo(map);

    el.addEventListener('click', (e) => {
        e.stopPropagation();
        marker.togglePopup();
    });

    markers[data.id] = marker;
    markersData[data.id] = data;
    return marker;
}

function triggerMarkerClick(id) {
    if (markers[id]) {
        var marker = markers[id];
        map.jumpTo({ center: marker.getLngLat(), zoom: 14 });
        marker.togglePopup();
    }
}

function clearMarkers() {
    Object.keys(markers).forEach(function (key) {
        markers[key].remove();
    });
    markers = {};
}

function drawMarkersBencana(list) {
    list.forEach(function (item) {
        let status = item.status;
        let iconImage = item.indikator.icon;
        if (status === 'Mendekati') iconImage = item.indikator.icon2;
        if (status === 'Atensi') iconImage = item.indikator.icon3;
        let iconUrl = ASSET_PATH + iconImage;
        // if (!item.latitude.toString().includes(',')) {
        try {
            addCustomMarker([parseFloat(item.latitude), parseFloat(item.longitude)], iconUrl, item, 'bencana');
        } catch (e) {

        }
        // }
    });
}

function drawMarkersBencanaPekerjaan(list) {
    list.forEach(function (item) {

        let iconImage = item.indikator.icon3;
        var iconUrl = ASSET_PATH + iconImage;
        try {
            addCustomMarker([parseFloat(item.latitude), parseFloat(item.longitude)], iconUrl, item, 'pekerjaan');
        } catch (e) {

        }
    });
}

function drawMarkersTkd(list) {
    list.forEach(function (item) {
        var iconUrl = 'https://geopas.satgasprr.go.id/images/icons/building.png';
        try {
            addCustomMarker([parseFloat(item.latitude), parseFloat(item.longitude)], iconUrl, item, 'anggaran');
        } catch (e) {

        }
    });

    // Clear and set the header
    $('#list_item_tkd').html(`
    <table class="table table-row-bordered">
        <thead>
            <tr class="fw-bold fs-6 text-gray-800">
                <th>No</th>
                <th>Pemerintah Daerah</th>
                <th style="text-align: right">TKD 2026</th>
                <th style="text-align: right">Penyesuaian TKD 2026</th>
                <th style="text-align: right">Total TKD 2026 Setelah Penyesuaian</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    `);

    list.forEach((item, index) => {
        // Calculate total
        const tkd2026 = parseFloat(item.anggaran_2026) || 0;
        const penyesuaian = parseFloat(item.penyesuaian) || 0;
        const total = tkd2026 + penyesuaian;
        var nomor = (item.is_provinsi == 1) ? '' : (index + 1);

        // Get the region name from the 'wilayah' relationship
        const namaDaerah = item.wilayah ? item.wilayah.nama : 'Tidak Diketahui';

        $('#list_item_tkd tbody').append(`
        <tr>
            <td>${nomor}</td>
            <td class="`+((item.is_provinsi == 1) ? 'text-warning' : '')+`">${namaDaerah}</td>
            <td class="`+((item.is_provinsi == 1) ? 'text-warning' : '')+`" style="text-align: right; font-weight: bold;">Rp ${add_commas(tkd2026)}</td>
            <td class="`+((item.is_provinsi == 1) ? 'text-warning' : '')+`" style="text-align: right; font-weight: bold;">Rp ${add_commas(penyesuaian)}</td>
            <td class="`+((item.is_provinsi == 1) ? 'text-warning' : '')+`" style="text-align: right; font-weight: bold;">Rp ${add_commas(total)}</td>
        </tr>
    `);
    });
}


// ─── GeoJSON helpers ──────────────────────────────────────────────────────────
// ─── GeoJSON cache (IndexedDB, 30-day expiry) ─────────────────────────────────
var GEO_DB_NAME = 'geo_cache_db';
var GEO_STORE_NAME = 'polygons';
var GEO_TTL_MS = 30 * 24 * 60 * 60 * 1000;
var geoCache = new Map();   // in-memory layer (session-level, instant)
var _geoDB = null;        // cached IDBDatabase handle

function geoOpenDB() {
    if (_geoDB) return Promise.resolve(_geoDB);
    return new Promise(function (resolve, reject) {
        var req = indexedDB.open(GEO_DB_NAME, 1);
        req.onupgradeneeded = function (e) {
            e.target.result.createObjectStore(GEO_STORE_NAME, { keyPath: 'url' });
        };
        req.onsuccess = function (e) { _geoDB = e.target.result; resolve(_geoDB); };
        req.onerror = function (e) { reject(e.target.error); };
    });
}

function geoIDBGet(url) {
    return geoOpenDB().then(function (db) {
        return new Promise(function (resolve, reject) {
            var req = db.transaction(GEO_STORE_NAME, 'readonly')
                .objectStore(GEO_STORE_NAME)
                .get(url);
            req.onsuccess = function (e) {
                var record = e.target.result;
                if (!record) { resolve(null); return; }
                if (Date.now() > record.expires) {
                    geoIDBDelete(url);         // evict stale entry
                    resolve(null); return;
                }
                resolve(record.data);
            };
            req.onerror = function (e) { reject(e.target.error); };
        });
    });
}

function geoIDBSet(url, data) {
    return geoOpenDB().then(function (db) {
        return new Promise(function (resolve, reject) {
            var req = db.transaction(GEO_STORE_NAME, 'readwrite')
                .objectStore(GEO_STORE_NAME)
                .put({ url: url, data: data, expires: Date.now() + GEO_TTL_MS });
            req.onsuccess = function () { resolve(); };
            req.onerror = function (e) { reject(e.target.error); };
        });
    }).catch(function (err) {
        console.warn('GeoJSON IDB write failed:', url, err);
    });
}

function geoIDBDelete(url) {
    return geoOpenDB().then(function (db) {
        return new Promise(function (resolve) {
            db.transaction(GEO_STORE_NAME, 'readwrite')
                .objectStore(GEO_STORE_NAME)
                .delete(url)
                .onsuccess = resolve;
        });
    }).catch(function () { });
}

function fetchGeo(url) {


    if (geoCache.has(url)) return Promise.resolve(geoCache.get(url));

    // 2. IndexedDB hit (persisted, survives reload, 30-day TTL)
    return geoIDBGet(url).then(function (cached) {
        if (cached) {
            geoCache.set(url, cached);
            return cached;
        }
        // 3. Network fetch → persist in both layers
        try {
            return fetch(url)
                .then(function (res) {
                    if (!res.ok) throw new Error('GeoJSON fetch failed: ' + url);
                    return res.json();
                })
                .then(function (data) {
                    geoCache.set(url, data);
                    geoIDBSet(url, data);   // fire-and-forget, non-blocking
                    return data;
                });
        } catch (e) {
            
        }
    });
}

// ─── Cache management helpers ─────────────────────────────────────────────────
function geoEvictExpired() {
    geoOpenDB().then(function (db) {
        var store = db.transaction(GEO_STORE_NAME, 'readwrite').objectStore(GEO_STORE_NAME);
        store.openCursor().onsuccess = function (e) {
            var cursor = e.target.result;
            if (!cursor) return;
            if (Date.now() > cursor.value.expires) cursor.delete();
            cursor.continue();
        };
    }).catch(function () { });
}

// Evict stale entries on every page load
geoEvictExpired();

//             $('#mendekati_count').text(total_mendekati);
//             $('#normal_count').text(total_normal);
//
//             $scroll.append(
//                 '<div class="leg-item" data-action="filterProv" data-value="' + p.kode + '" style="cursor:pointer;">' +
//                 '<div class="leg-dot" style="background:' + p.color + '"></div>' + p.nama + '</div>'
//             );
//         });
//
//         Object.entries(geoLayers).forEach(function (entry) {
//             entry[1].addTo(map).setStyle({ fillOpacity: 0.18, weight: 2, opacity: 0.85 });
//         });
//         map.flyToBounds(
//             L.featureGroup(Object.values(geoLayers)).getBounds(),
//             { padding: [20, 20], duration: 0.6, easeLinearity: 0.4 }
//         );
//         return;
//     }
//
//     if (geoLayers[slug]) {
//         map.flyToBounds(geoLayers[slug].getBounds(), { padding: [20, 20], duration: 0.6, easeLinearity: 0.4 });
//     }
//     loadSubRegions(slug);
// }

// Load all provinces on init
get_wilayah();


