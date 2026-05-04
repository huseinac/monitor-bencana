function buildCatSection(item) {
    const tpl  = document.getElementById('tpl-cat-section');
    const card = tpl.content.cloneNode(true).firstElementChild;

    card.dataset.cat  = item.id;
    card.dataset.kode = item.id;

    const icon = card.querySelector('.cat-section-icon img');

    let iconImage = item.icon;
    if (item.persentase < 70 && item.persentase > 30) iconImage = item.icon2;
    if (item.persentase <= 30) iconImage = item.icon3;

    icon.src = ASSET_PATH + iconImage;

    card.querySelector('.cat-section-name').textContent = item.nama;
    card.querySelector('.cat-section-counter').textContent = 'Total : ' + item.total_data + ' | Normal : ' + item.total_normal + ' | ' + ' Mendekati : ' + item.total_mendekati + ' | ' + 'Atensi : ' + item.total_atensi;
    card.querySelector('.cat-section-pct').textContent = item.persentase + '%';
    card.querySelector('.cat-bar-fill').style.width = item.persentase + '%';

    const header = card.querySelector('.cat-section-header');

    // Children
    const body = card.querySelector('.cat-body');
    if (item.children && item.children.length) {
        card.querySelector('.cat-section-arrow').hidden = false;

        item.children.forEach(function (child) {
            body.appendChild(buildCatSectionChild(child));
        });

        header.addEventListener('click', function () {
            card.classList.toggle('open');
            filterByIndikator(item.id, false);
        });
    } else {
        header.addEventListener('click', function () {
            filterByIndikator(item.id, false);
        });
    }

    return card;
}

function buildCatSectionChild(child) {
    const tpl  = document.getElementById('tpl-cat-section');
    const card = tpl.content.cloneNode(true).firstElementChild;

    card.dataset.cat  = child.id;
    card.dataset.kode = child.kode;

    const icon = card.querySelector('.cat-section-icon img');

    let iconImage = child.icon;
    if (child.percentage < 70 && child.percentage > 30) iconImage = child.icon2;
    if (child.percentage <= 30) iconImage = child.icon3;

    icon.src = ASSET_PATH + iconImage;

    const pelaksanaText = (child.list_pelaksana || [])
        .map(function (p) { return p.pelaksana.singkatan; })
        .join(', ');

    const nameEl = card.querySelector('.cat-section-name');
    nameEl.textContent = child.nama;

    // Pelaksana sub-label
    const sub = document.createElement('span');
    sub.style.cssText = 'font-size:12px;color:gray;display:block;';
    sub.textContent   = pelaksanaText;
    nameEl.after(sub);

    card.querySelector('.cat-section-pct').textContent  = child.percentage + '%';
    card.querySelector('.cat-bar-fill').style.width = child.percentage + '%';

    card.querySelector('.cat-section-header').addEventListener('click', function () {
        card.classList.toggle('open');
        filterByIndikator(child.id, true);
    });

    return card;
}
