
function close_all_button() {
    $('.cat-action').hide();
    $('.summary-panel').hide();
    $('#cat-sidebar .cat-btn').removeClass('active');
}


function open_button(alt) {
    close_all_button();


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
        get_anggaran(activeKode);
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
