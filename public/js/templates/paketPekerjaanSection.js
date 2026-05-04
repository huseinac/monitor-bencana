function buildPaketPekerjaanSection(item) {
    const tpl  = document.getElementById('tpl-cat-section');
    const card = tpl.content.cloneNode(true).firstElementChild;

    card.dataset.cat  = item.id;
    card.dataset.kode = item.id;

    card.querySelector('.cat-section-icon img').src = ASSET_PATH + item.indikator.icon;
    card.querySelector('.cat-section-name').textContent = item.nama;
    card.querySelector('.cat-section-amount').textContent  = 'Rp ' + add_commas(item.nominal);
    card.querySelector('.cat-section-pct').textContent  = item.persentase + '%';
    card.querySelector('.cat-bar-fill').style.width = item.persentase + '%';
    card.querySelector('.cat-bar-fill').style.background = 'linear-gradient(90deg,#e8a838,#c97b22)';

    card.querySelector('.cat-section-header').addEventListener('click', function () {
        triggerMarkerClick(item.id);
        // detail_pekerjaan(item.id);
    });

    return card;
}
