var _allListIndicatorPelaksana = [];
var _allMasalahKritisPelaksana  = [];

function renderPelaksana(list, listIndicator, listMasalahKritis) {
    _allListIndicatorPelaksana = listIndicator || [];

    var $scroll = $('#list_item_pelaksana').empty();
    var $row = $('<div>').css({ display:'flex', flexDirection:'row', gap:'12px' });

    // Left: pelaksana list
    var $left = $('<div>').css('flex', 1);
    list.forEach(function (p, i) {
        var $section = $('<div>').addClass('cat-section').attr('data-cat', 'pel_' + p.id);

        var $header = $('<div>').addClass('cat-section-header');
        $header.append($('<span>').addClass('cat-section-name').text((i + 1) + '. ' + p.nama));
        $header.append($('<span>').addClass('cat-section-pct').text(p.persentase + '%'));
        $header.on('click', function () {
            $section.toggleClass('open');
            filterIndikatorPelaksanaPanel(p.id);
        });

        var $bar = $('<div>').addClass('cat-bar').append($('<div>').addClass('cat-bar-fill').css('width', p.persentase + '%'));

        $section.append($header).append($bar);
        $left.append($section);
    });

    // Right: masalah kritis + location list
    var $right = $('<div>').css({ flex:1, display:'flex', flexDirection:'column', gap:'10px' });

    var mkNode = buildMasalahKritisList(listMasalahKritis, '_pelaksana');
    if (mkNode) {
        $right.append($('<div>').attr('id', 'masalah-kritis-pelaksana-panel').append(mkNode));
    }
    $right.append(
        $('<div>').attr('id', 'location-list-panel-pelaksana')
            .append(buildLocationList(_allListIndicatorPelaksana, '_pelaksana'))
    );

    $row.append($left).append($right);
    $scroll.append($row);
}

function filterIndikatorPelaksanaPanel(pelaksanaId) {
    var filtered = _allListIndicatorPelaksana.filter(function (item) {
        return item.indikator?.list_pelaksana?.some(lp => String(lp.pelaksana_id) === String(pelaksanaId));
    });

    $('#location-list-panel-pelaksana').empty().append(
        buildLocationList(filtered, '_pelaksana')
    );

    clearMarkers();
    drawMarkersBencana(filtered);

    var filteredMK = _allMasalahKritisPelaksana.filter(function (item) {
        if (!item.indikator) return false;
        if (isChild) return String(item.indikator.id) === String(id);
        return String(item.indikator.id) === String(id);
    });

    $('#location-list-panel').empty().append(
        buildLocationList(filtered, '_indikator')
    );

    $('#masalah-kritis-panel').html('');
    var mkNode = buildMasalahKritisList(filteredMK, '_indikator');
    if (mkNode) $right.append($('<div>').attr('id','masalah-kritis-panel').append(mkNode));
}
