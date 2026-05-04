{{-- Location Card --}}
<template id="tpl-loc-card">
    <div class="loc-card">
        <div class="loc-card__header">
            <div class="loc-card__info">
                <span class="loc-card__wilayah"></span>
                <span class="loc-card__indikator"></span>
                <span class="loc-card__pelaksana"></span>
            </div>
            <div class="loc-card__progress">
                <span class="loc-card__pct"></span>
                <div class="loc-card__bar">
                    <div class="loc-card__bar-fill"></div>
                </div>
            </div>
        </div>
        <div class="loc-card__jumlah">
            <img class="loc-card__icon" src="" alt="" />
            <span class="loc-card__keterangan-text"></span>
            <span class="loc-card__arrow">▾</span>
        </div>
        <div class="loc-card__photos" hidden>
            <div class="loc-card__before">
                <img src="" alt="" />
                <span>SETELAH BENCANA</span>
            </div>
            <div class="loc-card__divider">
                <div class="loc-card__divider-icon">›</div>
            </div>
            <div class="loc-card__after">
                <img src="" alt="" />
                <span>SAAT INI</span>
            </div>
        </div>
    </div>
</template>

{{-- Masalah Kritis Card --}}
<template id="tpl-mk-card">
    <div class="mk-card">
        <div class="mk-card__header">
            <span class="mk-card__wilayah"></span>
            <span class="mk-card__indikator"></span>
            <span class="mk-card__pelaksana"></span>
        </div>
        <div class="mk-card__jumlah">
            <img class="mk-card__icon" src="" alt="" />
            <span class="mk-card__jumlah-text"></span>
            <span class="mk-card__arrow">▾</span>
        </div>
        <div class="mk-card__keterangan" hidden></div>
        <div class="mk-card__photos" hidden>
            <div class="mk-card__before">
                <img src="" alt="" />
                <span>SETELAH BENCANA</span>
            </div>
            <div class="mk-card__divider">›</div>
            <div class="mk-card__after">
                <img src="" alt="" />
                <span>SAAT INI</span>
            </div>
        </div>
    </div>
</template>

{{-- Cat Section (Indikator row) --}}
<template id="tpl-cat-section">
    <div class="cat-section">
        <div class="cat-section-header" style="display: flex;justify-content: space-between;align-items: start;">
            <div>
                <span class="cat-section-icon"><img src="" alt="" /></span>
                <span class="cat-section-name"></span>
            </div>
            <div>
                <span class="cat-section-counter"></span>
                <span class="cat-section-arrow" hidden>▾</span>
            </div>
        </div>
        <div class="cat-seciton-info">
            <span class="cat-section-pct"></span>
            <div class="cat-section-amount"></div>
        </div>

        <div class="cat-card__progress">
            <div class="cat-card__bar">
                <div class="cat-bar-fill"></div>
            </div>
        </div>
        <div class="cat-body"></div>
    </div>
</template>
