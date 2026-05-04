// Depends on: ASSET_PATH (global)

function buildLocationCard(data, prefix) {
    const tpl  = document.getElementById('tpl-loc-card');
    const card = tpl.content.cloneNode(true).firstElementChild;
    const id   = 'loc-card-' + prefix + '-' + data.id;

    card.dataset.cardId = id;

    card.querySelector('.loc-card__wilayah').textContent = [
        data.wilayah?.parent?.parent?.nama,
        data.wilayah?.parent?.nama,
        data.wilayah?.nama,
    ].filter(Boolean).join(' - ');

    card.querySelector('.loc-card__indikator').textContent = (data.indikator?.nama ?? '');
    let nama_pelaksana = data.indikator.list_pelaksana.map(val => val.pelaksana.singkatan).join(', ');
    card.querySelector('.loc-card__pelaksana').textContent = nama_pelaksana;

    let status = data.status;
    let pct = 100;
    let icon = data.indikator.icon;
    if (status === 'Mendekati') {
        pct = 70;
        icon = data.indikator.icon2;
    }
    if (status === 'Atensi') {
        pct = 30;
        icon = data.indikator.icon3;
    }
    card.querySelector('.loc-card__pct').textContent = pct + '%';
    card.querySelector('.loc-card__bar-fill').style.width = pct + '%';


    card.querySelector('.loc-card__icon').src = ASSET_PATH + icon;
    card.querySelector('.loc-card__keterangan-text').innerHTML =
        'Nama Lokasi : ' + (data.nama_lokasi ?? '') + '<br>' +
        'Keterangan : ' + (data.keterangan ?? '');

    function setPhoto(container, src) {
        if (src) {
            container.querySelector('img').src = ASSET_PATH + src;
        } else {
            container.innerHTML = '<div style="display:flex; align-items:center; justify-content:center; height:100%; width:100%;text-align: center; font-size:0.85rem; color:#fff;">Belum Ada<br>Laporan</div>';
        }
    }

    setPhoto(card.querySelector('.loc-card__before'), data.foto_sebelum);
    setPhoto(card.querySelector('.loc-card__after'),  data.foto_sesudah);

    card.addEventListener('click', function () {
        const photos = card.querySelector('.loc-card__photos');
        const arrow  = card.querySelector('.loc-card__arrow');
        const isOpen = !photos.hidden;
        photos.hidden = isOpen;
        arrow.style.transform = isOpen ? '' : 'rotate(180deg)';

        triggerMarkerClick(data.id);
    });

    return card;
}

function buildLocationList(items, prefix) {
    const wrap = document.createElement('div');

    if (!items || !items.length) {
        wrap.innerHTML = '<div class="no-data">Tidak ada data.</div>';
        return wrap;
    }

    items.forEach(function (data) {
        wrap.appendChild(buildLocationCard(data, prefix));
    });

    return wrap;
}
