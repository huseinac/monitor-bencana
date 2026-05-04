@extends('layouts.index')

@section('content')
    @yield('sub_header')

    <div class="d-flex flex-row" style="gap: 24px">
        <div style="flex: 1; display: flex; flex-direction: column; gap: 16px;">

            {{-- Sub Menu Navigation --}}
            <div class="panel">
                <div class="panel-body" style="padding: 8px;">
                    @php
                        $menu_items = [
                            ['label' => 'Rincian', 'route' => 'paket_pekerjaan.rincian_pekerjaan.index'],
                            ['label' => 'Timeline', 'route' => 'paket_pekerjaan.timeline_pekerjaan.index'],
                            ['label' => 'Realisasi', 'route' => 'paket_pekerjaan.realisasi_pekerjaan.index'],
                            ['label' => 'Pembayaran', 'route' => 'paket_pekerjaan.pembayaran_pekerjaan.index'],
                        ];
                    @endphp
                    <div class="d-flex flex-column" style="gap: 4px;">
                        @foreach($menu_items as $item)
                            @php
                                $is_active = request()->route()->getName() == $item['route'];
                            @endphp
                            <a href="{{ has_route($item['route'], $paket_pekerjaan->id) }}"
                               class="d-flex align-items-center"
                               style="
                                   padding: 8px 12px;
                                   border-radius: 6px;
                                   text-decoration: none;
                                   font-size: 14px;
                                   font-weight: {{ $is_active ? '600' : '400' }};
                                   color: {{ $is_active ? 'var(--bs-primary)' : 'inherit' }};
                                   background: {{ $is_active ? 'rgba(var(--bs-primary-rgb), 0.08)' : 'transparent' }};
                                   border-left: 3px solid {{ $is_active ? 'var(--bs-primary)' : 'transparent' }};
                               ">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Detail Paket Pekerjaan --}}
            <div class="panel">
                <div class="panel-body">
                    <div class="d-flex flex-column mb-2" style="gap: 12px;">
                        <h6># Detail Paket Pekerjaan</h6>
                        <p class="m-0">Provinsi<br><b>{{ $paket_pekerjaan->wilayah->parent->parent->nama ?? '' }}</b></p>
                        <p class="m-0">Kabupaten<br><b>{{ $paket_pekerjaan->wilayah->parent->nama ?? '' }}</b></p>
                        <p class="m-0">Kecamatan<br><b>{{ $paket_pekerjaan->wilayah->nama ?? '' }}</b></p>
                        <p class="m-0">Indikator<br><b>{{ $paket_pekerjaan->indikator->nama ?? '' }}</b></p>
                        <p class="m-0">Pelaksana<br><b>{{ $paket_pekerjaan->pelaksana->nama ?? '' }}</b></p>
                        <p class="m-0">Kategori<br><b>{{ $paket_pekerjaan->kategori_paket_pekerjaan->nama ?? '' }}</b></p>
                        <p class="m-0">Nama<br><b>{{ $paket_pekerjaan->nama }}</b></p>
                        <p class="m-0">
                            Koordinat<br>
                            @if(($paket_pekerjaan->latitude ?? '') != '')
                                <a class="btn-act edit me-1" target="_blank" href="https://www.google.com/maps/search/?api=1&query={{ ($paket_pekerjaan->latitude ?? '') }},{{ ($paket_pekerjaan->longitude ?? '') }}" title="Map"><i class="bi bi-geo-alt-fill"></i></a>
                                <a class="btn-act edit me-1" target="_blank" href="https://www.google.com/maps/@?api=1&map_action=pano&viewpoint={{ ($paket_pekerjaan->latitude ?? '') }},{{ ($paket_pekerjaan->longitude ?? '') }}&heading=0&pitch=0&fov=100" title="Map"><i class="bi bi-map-fill"></i></a>
                            @endif
                        </p>
                        <p class="m-0">Tahun Anggaran<br><b>{{ $paket_pekerjaan->tahun_anggaran }}</b></p>
                        <p class="m-0">Nama Program<br><b>{{ $paket_pekerjaan->nama_program }}</b></p>
                        <p class="m-0">Nama Kegiatan<br><b>{{ $paket_pekerjaan->nama_kegiatan }}</b></p>
                        <p class="m-0">Nama Sub Kegiatan<br><b>{{ $paket_pekerjaan->nama_sub_kegiatan }}</b></p>
                        <p class="m-0">Pagu Dana<br><b>{{ format_number($paket_pekerjaan->nama_rekening) }}</b></p>
                        <p class="m-0">No.Kontrak<br><b>{{ $paket_pekerjaan->no_kontrak }}</b></p>
                        <p class="m-0">Nama Paket Pekerjaan<br><b>{{ $paket_pekerjaan->nama_paket }}</b></p>
                        <p class="m-0">Jenis Pengadaan<br><b>{{ $paket_pekerjaan->jenis_pengadaan }}</b></p>
                        <p class="m-0">Model Pengadaan<br><b>{{ $paket_pekerjaan->model_pengadaan }}</b></p>
                        <p class="m-0">Tanggal Kontrak<br><b>{{ format_date($paket_pekerjaan->tanggal_kontrak) }}</b></p>
                        <p class="m-0">Tanggal Selesai<br><b>{{ format_date($paket_pekerjaan->tanggal_selesai) }}</b></p>
                        <p class="m-0">Penyedia<br><b>{{ $paket_pekerjaan->penyedia->nama ?? '' }}</b></p>
                        <p class="m-0">Nilai Pagu<br><b>{{ format_number($paket_pekerjaan->nilai_pagu ?? '') }}</b></p>
                        <p class="m-0">Nilai Kontrak<br><b>{{ format_number($paket_pekerjaan->nilai_kontrak ?? '') }}</b></p>
                    </div>
                </div>
            </div>

        </div>

        <div style="flex: 4;">
            @yield('sub_content')
        </div>
    </div>
@endsection
