function buildMasalahKritisCard(data, prefix) {
    const tpl  = document.getElementById('tpl-mk-card');
    const card = tpl.content.cloneNode(true).firstElementChild;
    const id   = 'mk-card-' + prefix + '-' + data.id;

    card.dataset.cardId = id;

    card.querySelector('.mk-card__wilayah').textContent = [
        data.wilayah?.parent?.parent?.nama,
        data.wilayah?.parent?.nama,
        data.wilayah?.nama,
    ].filter(Boolean).join(' - ');

    card.querySelector('.mk-card__indikator').textContent =
        (data.indikator?.parent?.nama ?? '') + ' ' + (data.indikator?.nama ?? '');
    card.querySelector('.mk-card__pelaksana').textContent =
        data.pelaksana?.nama ?? '';

    // Jumlah
    const icon = card.querySelector('.mk-card__icon');
    if (data.indikator?.icon) {
        icon.src = ASSET_PATH + data.indikator.icon;
    } else {
        icon.hidden = true;
    }
    card.querySelector('.mk-card__jumlah-text').textContent =
        (data.jumlah ?? '') + ' ' + (data.satuan ?? '') + ' ' +
        (data.indikator?.parent?.nama ?? '') + ' ' +
        (data.indikator?.nama ?? '') + ' Bermasalah';

    // Keterangan
    const ket = card.querySelector('.mk-card__keterangan');
    if (data.keterangan) {
        ket.textContent = data.keterangan;
        ket.hidden = false;
    }

    // Photos
    function setPhoto(container, src) {
        if (src) {
            container.querySelector('img').src = ASSET_PATH + src;
        } else {
            container.innerHTML = '<div style="display:flex; align-items:center; justify-content:center; height:100%; width:100%; font-size:0.85rem; color:#fff;text-align: center;;">Belum Ada<br>Laporan</div>';
        }
    }

    setPhoto(card.querySelector('.mk-card__before'), data.foto);
    setPhoto(card.querySelector('.mk-card__after'),  data.foto_sesudah);

    // Toggle
    card.addEventListener('click', function () {
        const photos = card.querySelector('.mk-card__photos');
        const arrow  = card.querySelector('.mk-card__arrow');
        const isOpen = !photos.hidden;
        photos.hidden        = isOpen;
        arrow.style.transform = isOpen ? '' : 'rotate(180deg)';
    });

    return card;
}

function buildMasalahKritisList(items, prefix) {
    if (!items || !items.length) return null;

    const wrap  = document.createElement('div');
    wrap.className = 'mk-list-wrap';

    // Header badge
    const header = document.createElement('div');
    header.className = 'mk-list-header';
    header.innerHTML =
        '<span class="mk-list-title">⚠ Masalah Kritis</span>' +
        '<span class="mk-list-count">' + items.length + '</span>';
    wrap.appendChild(header);

    items.forEach(function (data) {
        wrap.appendChild(buildMasalahKritisCard(data, prefix));
    });

    return wrap;
}
