<div class="tbl-wrap">
    <table class="tbl" id="paket_pekerjaan_table">
        <thead>
        <tr>
            <th style="width:44px">#</th>
            <th>Wilayah</th>
            <th>Indikator</th>
            <th>Pelaksana</th>
            <th>Nama Pekerjaan</th>
            <th>Kategori</th>
            <th>Nominal</th>
            <th>Koordinat</th>
            <th style="width:80px; text-align:center;">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @php($no = 1)
        @if($paket_pekerjaans instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php($no = (($paket_pekerjaans->currentPage()-1) * $paket_pekerjaans->perPage()) + 1)
        @endif
        @foreach($paket_pekerjaans as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td style="line-height: 1.2;white-space: nowrap;">
                    <span>{{ $item->wilayah->nama ?? '' }}</span> <br>
                    <span style="font-size: 12px;color: gray;">{{ $item->wilayah->parent->nama ?? '' }}</span>
                    <span style="font-size: 12px;color: gray;">- {{ $item->wilayah->parent->parent->nama ?? '' }}</span>
                </td>
                <td style="line-height: 1.2;white-space: nowrap;">
                    <span>{{ $item->indikator->nama ?? '' }}</span> <br>
                    <span style="font-size: 12px;color: gray;">{{ $item->indikator->parent->nama ?? '' }}</span>
                </td>
                <td style="line-height: 1.2;">
                    <span>{{ $item->pelaksana->singkatan ?? '' }}</span>
                </td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->kategori_paket_pekerjaan->nama ?? '' }}</td>
                <td style="line-height: 1.2;white-space: nowrap;">
                    <span>{{ format_number($item->nominal) }}</span>
                </td>
                <td style="white-space: nowrap;">
                    @if(($item->latitude ?? '') != '')
                        <a class="btn-act edit me-1" target="_blank" href="https://www.google.com/maps/search/?api=1&query={{ ($item->latitude ?? '') }},{{ ($item->longitude ?? '') }}" title="Map"><i class="bi bi-geo-alt-fill"></i></a>
                        <a class="btn-act edit me-1" target="_blank" href="https://www.google.com/maps/@?api=1&map_action=pano&viewpoint={{ ($item->latitude ?? '') }},{{ ($item->longitude ?? '') }}&heading=0&pitch=0&fov=100" title="Map"><i class="bi bi-map-fill"></i></a>
                    @endif
                </td>
                <td style="text-align:center;white-space: nowrap;padding: 0 10px 0 0;">
                    <a class="btn-act edit me-1" href="{{ route('paket_pekerjaan.rincian_pekerjaan.index', $item->id) }}" title="Edit"><i class="bi bi-list-ul"></i></a>
                    <button class="btn-act edit me-1" onclick="info({{ $item->id }})" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                    <button class="btn-act del" onclick="confirm_delete({{ $item->id }})" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if($paket_pekerjaans instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $paket_pekerjaans->links('vendor.pagination.custom') }}
@endif
