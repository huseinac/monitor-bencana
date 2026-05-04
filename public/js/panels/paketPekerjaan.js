var _allPekerjaan = [];

function renderPaketPekerjaan(list, list_pekerjaan = []) {
    _allPekerjaan = list_pekerjaan || [];

    let $scroll = $('#list_item_pekerjaan').empty();
    let $row = $('<div>').css({ display:'flex', flexDirection:'row', gap:'12px' });

    let $left = $('<div>').css('flex', 1);
    list.forEach(function (p, i) {
        let $section = $('<div>').addClass('cat-section').attr('data-cat', 'pel_' + p.id);

        let $header = $('<div>').addClass('cat-section-header');
        $header.append($('<span>').addClass('cat-section-name').text((i + 1) + '. ' + p.nama));
        $header.append($('<span>').addClass('cat-section-pct').text(p.persentase + '% | '+ p.list_pekerjaan.length +' Pekerjaan'));
        $header.on('click', function () {
            filterPekerjaan(p.id);
        });

        // let $counter =

        let $bar = $('<div>').addClass('cat-bar').append($('<div>').addClass('cat-bar-fill').css('width', p.persentase + '%'));

        $section.append($header).append($bar);
        $left.append($section);
    });

    let $right = $('<div id="list_paket_pekerjaan">').css({ flex:1, display:'flex', flexDirection:'column', gap:'10px' });
    _allPekerjaan.forEach((item) => $right.append(buildPaketPekerjaanSection(item)));

    $row.append($left).append($right);
    $scroll.append($row);
}

function filterPekerjaan(pelaksanaId) {
    let filtered = _allPekerjaan.filter(function (item) {
        return String(item.pelaksana.id) === String(pelaksanaId);
    });
    let $right = $('#list_paket_pekerjaan').empty();
    filtered.forEach((item) => $right.append(buildPaketPekerjaanSection(item)));
}
