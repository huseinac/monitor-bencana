let activeKode = '';

let polygonLayers = {};

var GEO_DB_NAME = 'geo_cache_db';
var GEO_STORE_NAME = 'polygons';
var GEO_TTL_MS = 30 * 24 * 60 * 60 * 1000;
var geoCache = new Map();
var _geoDB = null;

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


function get_wilayah(kode = '', nama = '') {
    activeKode = kode;

    Swal.fire({
        title: 'Loading',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => Swal.showLoading()
    });

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

function fetchGeo(url) {
    if (geoCache.has(url)) return Promise.resolve(geoCache.get(url));

    // 2. IndexedDB hit (persisted, survives reload, 30-day TTL)
    return geoIDBGet(url).then(function (cached) {
        if (cached) {
            geoCache.set(url, cached);
            return cached;
        }
        // 3. Network fetch → persist in both layers
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
    });
}

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


get_wilayah();
