
function close_all_button() {
    $('.cat-action').hide();
    $('.summary-panel').hide();
    $('#cat-sidebar .cat-btn').removeClass('active');
}


function open_button(alt) {
    close_all_button();

    // if (activeKode === '') {
    //     if (alt === 'indikator' || alt === 'pelaksana' || alt === 'pekerjaan') {
    //         let text = '';
    //         if (alt === 'pekerjaan') text = 'Setelah pilih propinsi baru bisa memilih menu Renduk Bencana';
    //         if (alt === 'indikator') text = 'Setelah pilih propinsi baru bisa memilih menu Update Kondisi (indikator)';
    //
    //         swal.fire({
    //             icon: 'warning',
    //             title: 'Harap klik fitur wilayah dan pilih satu provinsi terlebih dahulu',
    //             text: text,
    //         })
    //         return;
    //     }
    // }

    activeCat = alt;
    $('.cat-action').hide();
    $('#cat_action_' + alt).show();
    $('.cat-btn').removeClass('active');
    $('#cat_btn_' + alt).addClass('active');

    clearMarkers();
    if (activeCat === 'indikator' || activeCat === 'pelaksana') {
        get_indikator(activeKode);
    }
    if (activeCat === 'tkd') {
        clearMarkers();
        drawMarkersTkd(listAnggaranDaerah);
    }
    if (activeCat === 'pekerjaan') {
        changeTile(map, 'osm')
        get_pekerjaan(activeKode);
        clearMarkers();
    } else {
        changeTile(map, '3d')
    }
}

function open_panel(alt) {
    $('#panel-' + alt).show();
}

function detail_pekerjaan(id) {
    $.get(BASE_URL + 'map/detail_pekerjaan/' + id, (result) => {
        $('#paket_pekerjaan_detail').show();
        $('#paket_pekerjaan_info').html(result);
    });
}

$(document).on('click', '.panel-close', function () {
    $('.summary-panel').hide();
});

// map.on('click', function () {
//     clearTimeout(popupTimeout);
//     popupTimeout = setTimeout(function () {
//         get_wilayah();
//     }, 200);
// });



// ─── Data fetch ───────────────────────────────────────────────────────────────
// function fetchMapData(params) {
//     params = params || '';
//     return $.getJSON(DATA_MAP_URL + '?' + params)
//         .then(function (res) {
//             var masalahIndikator  = (res.list_masalah_kritis || []).filter(function (i) { return i.indikator_id != null; });
//             var masalahPelaksana  = (res.list_masalah_kritis || []).filter(function (i) { return i.pelaksana_id != null; });
//
//             renderIndicators(res.list_indikator, res.list_sektor_terdampak, masalahIndikator);
//             renderPelaksana(res.list_pelaksana,  res.list_sektor_terdampak, masalahPelaksana);
//             renderPaketPekerjaan(res.list_paket_pekerjaan);
//
//             clearMarkers();
//
//             if (activeCat === 'indikator' || activeCat === 'pelaksana') {
//                 drawMarkersBencana(res.list_sektor_terdampak);
//             }
//             if (activeCat === 'pekerjaan') {
//                 drawMarkersBencanaPekerjaan(res.list_paket_pekerjaan);
//             }
//             if (activeCat === 'tkd') {
//                 drawMarkersTkd(res.list_anggaran_daerah);
//             }
//         });
// }

// ─── Panel helpers ────────────────────────────────────────────────────────────
// function closeAllPanels() {
//     $('[id^="summary-panel"]').hide();
//     $('#cat-sidebar .cat-btn').removeClass('active');
// }

// ─── Event bindings ───────────────────────────────────────────────────────────

// Category sidebar buttons
// $(document).on('click', '#cat-sidebar .cat-btn', function () {
//     closeAllPanels();
//     var alt = $(this).data('alt');
//     activeCat = alt;
//     $('.cat-action').hide();
//     $('#cat_action_' + alt).show();
//     $('.cat-btn').removeClass('active');
//     $('#cat_btn_' + alt).addClass('active');
// });
//
// // Panel close button
//
// // Cat action: search
// $(document).on('click', '.cat-btn2[data-action="search"]', function () {
//     Swal.fire({ icon: 'warning', title: 'Under Construction' });
// });
//
// // Cat action: open data panel
// $(document).on('click', '.cat-btn2[data-action="open-data"]', function () {
//     $('#' + $(this).data('panel')).show();
// });
//
// // Sidebar navigation (panel-scroll2)
// $(document).on('click', '#panel-scroll2 .leg-item[data-action]', function () {
//     // var action = $(this).data('action');
//     // var prov = $(this).data('prov');
//     // var sub = $(this).data('sub');
//     // var value = $(this).data('value');
//
//     // switch (action) {
//     //     case 'filterProv': filterProv(value); break;
//     //     case 'loadSubRegions': loadSubRegions(prov); break;
//     //     case 'loadDistrict':  loadDistrict(prov, sub); break;
//     //     case 'loadKelurahan': loadKelurahan(prov, $(this).data('kab'), sub); break;
//     // }
// });
//
// // Static wilayah items (from Blade)
// $(document).on('click', '#panel-scroll2 .leg-item[data-kode]', function () {
//     // filterProv($(this).data('kode'));
// });
//
// // Map click → reset to all

//
// // ─── Init ─────────────────────────────────────────────────────────────────────
// // fetchMapData('');


