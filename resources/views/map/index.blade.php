<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
    <title>Monitoring Penanganan Bencana — Sumatera & Aceh</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .maplibregl-ctrl-bottom-right {
            right: 18px !important;
            bottom: 18px !important;
        }
        .loc-card__info {
            display: flex;
            flex-direction: column;
        }
    </style>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
</head>
<body>

<div id="map" style="margin-top: 74px;"></div>

<div id="topbar">
    <div class="brand">
        <a href="{{ route('/') }}" class="brand-logo" style="display:flex;flex-direction:row;gap:6px;">
            <img src="{{ asset('logo.png') }}" alt=""/>
        </a>
    </div>
    <div class="d-flex flex-row align-items-center" style="gap:14px">
        <img src="{{ asset('logo2.png') }}" alt="" style="height:52px;"/>
        <img src="{{ asset('icon-info.png') }}" alt="" style="height:26px;margin-top:2px">
        <a href="{{ route('login') }}" class="btn btn-secondary rounded-4 py-2 px-4"
           style="background-color:#021e49;border:2px solid #c1daff;border-radius:8px">LOGIN</a>
    </div>
</div>

<div id="cat-sidebar">
    @foreach($buttons as $button)
        <div class="cat-btn" id="cat_btn_{{ $button['alt'] }}" data-alt="{{ $button['alt'] }}" onclick="open_button('{{ $button['alt'] }}')">
            <img width="24" height="24" src="{{ asset($button['icon']) }}" alt="{{ $button['alt'] }}"/>
            <span class="cat-label">{!! $button['label'] !!}</span>
        </div>
    @endforeach
</div>

<div style="position:fixed;right:28px;top:88px;display:flex;flex-direction:column;gap:12px;">
    @foreach($buttons as $button)
        <div id="cat_action_{{ $button['alt'] }}" class="cat-action" style="display:none;">
            <div style="display:flex;flex-direction:column;gap:12px">
{{--                <div class="cat-btn2" data-action="search">--}}
{{--                    <img width="30" height="30" src="{{ asset('images/icons/search_'. $button['alt'] .'.png') }}" alt="{{ $button['alt'] }}"/>--}}
{{--                </div>--}}
                <div class="cat-btn2" data-action="open-data" onclick="open_panel('{{ $button['alt'] }}')">
                    <img width="30" height="30" src="{{ asset('images/icons/database_'. $button['alt'] .'.png') }}" alt="{{ $button['alt'] }}"/>
                </div>
            </div>
        </div>
    @endforeach
</div>

@foreach($panels as $panel)
    <div id="panel-{{ $panel['id'] }}" class="summary-panel" style="display:none;@if($panel['id'] === 'tkd') width: 50vw; @endif">
        <div class="panel-header">
            <div class="panel-title">
                <span class="panel-title-text">{{ $panel['title'] }}</span>
                <div class="panel-close">✕</div>
            </div>
            <div class="panel-sub">{{ $panel['sub'] }}</div>
            <script>
                var selectedWilayahKode = '';
                @if ($panel['id'] == 'tkd' || $panel['id'] == 'pekerjaan')
                $(document).on("change", "#provinsi_{{ $panel['id'] }}", function (e) {
                    // body...
                    $provinsi_id = $("#provinsi_{{ $panel['id'] }}");
                    $kabupaten_id = $(document).find("#kabupaten_{{ $panel['id'] }}");
                    $kabupaten_id.html('<option value="">-Pilih Kabupaten-</option>');
                    let parent_kode = $provinsi_id.find('option:selected').data('id');
                    selectedWilayahKode = parent_kode;

                    if (parent_kode !== '') {
                        $.get(BASE_URL + 'map/get_wilayah?kode=' + parent_kode, function (r) {
                            // body...
                            $.each(r, function (index, value) {
                                // body...
                                $kabupaten_id.append('<option value="'+ value.kode +'">'+ value.nama +'</option>');
                            });
                        });

                        select_provinsi('{{ $panel['id'] }}');
                    }

                    @if ($panel['id'] == 'tkd')
                    get_anggaran($provinsi_id.find('option:selected').data('id'));
                    @elseif ($panel['id'] == 'pekerjaan')
                    filterPekerjaan("{{$panel['id']}}", selectedWilayahKode);
                    @endif

                    // $.each(list_kabupaten.filter(item => item.parent_kode.toString() === parent_kode.toString()), (i, val) => {
                    //     $kabupaten_id.append('<option value="'+ val.kode +'" ' + (val.kode === selected_kode ? 'selected' : '') + '>'+ val.nama +'</option>');
                    // });
                });
                $(document).on("change", "#kabupaten_{{ $panel['id'] }}", function (e) {
                    // body...

                    $kabupaten_id = $(document).find("#kabupaten_{{ $panel['id'] }}");
                    let parent_kode = $kabupaten_id.find('option:selected').val();
                    selectedWilayahKode = parent_kode;

                    if (parent_kode !== '') {
                        @if ($panel['id'] == 'tkd')
                        get_anggaran(parent_kode);
                        @elseif ($panel['id'] == 'pekerjaan')
                        filterPekerjaan("{{$panel['id']}}", selectedWilayahKode);
                        @endif
                    }
                    // display_kabupaten();
                });
                @endif
            </script>
            <div class="header-wilayah w-100">
                <div class="breadcrumb-row">
                    <div class="breadcrumb-cell" style="cursor: pointer;">Prop : <select id="provinsi_{{ $panel['id'] }}" class="provinsi_option" style="border: 0;background: transparent;"><option data-id=""></option><option data-id="11" value="Aceh">Aceh</option><option data-id="12" value="Sumatera Utara">Sumatera Utara</option><option data-id="13" value="Sumatera Barat">Sumatera Barat</option></select></div>
                    <div class="breadcrumb-cell" style="cursor: pointer;"> <!--onclick="display_kabupaten()"-->
                        Kab : 
                        <select id="kabupaten_{{ $panel['id'] }}" class="provinsi_option" style="border: 0;background: transparent;" onchange="">
                        </select>
                    </div>
                    @if ($panel['id'] !== 'tkd')
                    <div class="breadcrumb-cell" style="cursor: pointer;" onclick="display_kecamatan()">Kec : <span class="fw-500 kecamatan_name"></span></div>
                    @endif
                </div>
                @csrf
                @if ($panel['id'] == 'pekerjaan')
                <div class="panel w-100 mt-3 bg-transparent">
                    <div class="panel-header position-relative" 
                         data-bs-toggle="collapse" 
                         data-bs-target="#searchPanelBody_{{ $panel['id'] }}" 
                         style="cursor: pointer; padding-right: 2.5rem;" 
                         role="button">
                        
                        <div class="row">
                            <div class="col-12">
                                <h5><i class="bi bi-search"></i> Pencarian data</h5>
                            </div>
                        </div>

                        <i class="bi bi-chevron-down text-muted position-absolute top-50 end-0 translate-middle-y me-3"></i>
                    </div>
                    
                    <div class="panel-body row collapse hide" id="searchPanelBody_{{ $panel['id'] }}">
                        <div class="col-lg-12 mb-2">
                            <div>
                                <x-input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" oninput="if (this.value.length > 4) this.value = this.value.slice(0, 4);" name="tahun_anggaran" class="form-control-sm" caption="Cari tahun" />
                            </div>
                        </div>
                        <x-io-select name="pelaksana_id" caption="Pelaksana" :options="$list_pelaksana" placeholder="-Pilih pelaksana-" :viewtype="2" />
                        <x-io-select name="status_anggaran_id" caption="Status Anggaran" :options="$list_status_anggaran" placeholder="-Pilih status anggaran-" :viewtype="2" />
                        <x-io-select name="status_pelaksanaan_id" caption="Status Pelaksanaan" :options="$list_status_pelaksanaan" placeholder="-Pilih status pelaksanaan-" :viewtype="2" />
                    </div>
                </div>
                @endif
            </div>

            @if($panel['id'] === 'wilayah')
                <div class="status-legend">
                    <div class="status-item">
                        <div class="status-dot" style="background:#00C853"></div>
                        <span>NORMAL :</span>
                        <span id="normal_count" class="fw-500"></span>
                    </div>
                    <div class="status-item">
                        <div class="status-dot" style="background:#2962FF"></div>
                        <span>MENDEKATI NORMAL :</span>
                        <span id="mendekati_count" class="fw-500"></span>
                    </div>
                    <div class="status-item">
                        <div class="status-dot" style="background:#FFD600"></div>
                        <span>ATENSI KHUSUS :</span>
                        <span id="atensi_count" class="fw-500"></span>
                    </div>
                </div>
            @endif
        </div>

        <div class="panel-scroll" id="{{ $panel['content_id'] }}">
        </div>
    </div>
@endforeach

<div id="paket_pekerjaan_detail" style="display:none;z-index:200;position:fixed;top:88px;right:28px;">
    <div id="paket_pekerjaan_info" style="height:85dvh;width:70vw;position:relative;background: #fff;" >
    </div>
</div>

<div style="position:fixed;bottom:8px;left:8px;color: #fff;">
    Catatan : Update Data 25 Maret 2026
</div>

<div style="position:fixed;top:80px;left:10px;color: #fff;" id="loading_state"></div>

@include('map.partials.templates')

<script>
    const BASE_URL = "{{ url('/') }}/";
    const ASSET_PATH = "{{ asset('storage') }}/";

    $(document).ready(function() {
        
        // 💡 2. Dynamic event listener catching inputs/selects inside the panel blocks
        $(document).on('change input', '[id^="searchPanelBody_"] input, [id^="searchPanelBody_"] select', function() {
            // Find the closest parent panel container and extract its unique ID suffix
            let $container = $(this).closest('[id^="searchPanelBody_"]');
            let containerId = $container.attr('id'); // e.g., "searchPanelBody_pekerjaan"
            let panelId = containerId.replace('searchPanelBody_', ''); // Extracts: "pekerjaan"
            
            // Trigger your parsing filter
            alert(selectedWilayahKode);
            filterPekerjaan(panelId, selectedWilayahKode);
        });
    });
</script>

<script src="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.js"></script>
<!-- <script src="{{ asset('js/jquery.min.js') }}"></script> -->
<script src="{{ asset('js/sweetalert2.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/io.js') }}"></script>
<script src="{{ asset('js/templates/locationCard.js') }}"></script>
<script src="{{ asset('js/templates/masalahKritisCard.js') }}"></script>
<script src="{{ asset('js/templates/catSection.js') }}"></script>
<script src="{{ asset('js/templates/paketPekerjaanSection.js') }}"></script>
<script src="{{ asset('js/templates/markerPopup.js') }}"></script>
<script src="{{ asset('js/panels/indicators.js') }}"></script>
<script src="{{ asset('js/panels/pelaksana.js') }}"></script>
<script src="{{ asset('js/panels/paketPekerjaan.js') }}"></script>
<script src="{{ asset('js/map.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
