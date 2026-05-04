// Depends on: buildLocationList, buildMasalahKritisList, buildCatSection

var _allListIndicator  = [];
var _allMasalahKritisIndikator  = [];

function renderIndicators(list, listIndicator, listMasalahKritis) {
    _allListIndicator = listIndicator  || [];
    _allMasalahKritisIndikator = listMasalahKritis || [];

    var $scroll = $('#list_item_indikator').empty();

    var $row = $('<div>').css({ display:'flex', flexDirection:'row', gap:'12px' });

    var $left = $('<div>').css('flex', 1);
    list.forEach(function (item) {
        $left.append(buildCatSection(item));
    });

    // Right: masalah kritis + location list
    // var $right = $('<div>').css({ flex:1, display:'flex', flexDirection:'column', gap:'10px' });
    //
    // var mkNode = buildMasalahKritisList(_allMasalahKritisIndikator, '_indikator');
    // if (mkNode) $right.append($('<div>').attr('id','masalah-kritis-panel').append(mkNode));
    //
    // $right.append(
    //     $('<div>').attr('id','location-list-panel').append(buildLocationList(_allListIndicator, '_indikator'))
    // );

    // $row.append($left).append($right);
    $row.append($left);
    $scroll.append($row);
}

function filterByIndikator(id, isChild) {
    var filtered = _allListIndicator.filter(function (item) {
        if (!item.indikator) return false;
        if (isChild) return String(item.indikator.id) === String(id);
        return String(item.indikator.id) === String(id) ||
            String(item.indikator.parent?.id) === String(id);
    });

    console.log('start clear marker');
    clearMarkers();
    console.log('done clear marker');

    console.log('start draw marker ' + filtered.length);
    drawMarkersBencana(filtered);
    console.log('done draw marker');

    var filteredMK = _allMasalahKritisIndikator.filter(function (item) {
        if (!item.indikator) return false;
        if (isChild) return String(item.indikator.id) === String(id);
        return String(item.indikator.id) === String(id);
    });

    $('#location-list-panel').empty().append(
        buildLocationList(filtered, '_indikator')
    );

    // var $right = $('<div>').css({ flex:1, display:'flex', flexDirection:'column', gap:'10px' });

    $('#masalah-kritis-panel').html('');
    // var mkNode = buildMasalahKritisList(filteredMK, '_indikator');
    // if (mkNode) $right.append($('<div>').attr('id','masalah-kritis-panel').append(mkNode));
}
