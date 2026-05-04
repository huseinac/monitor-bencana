<div class="tbl-wrap">
    <table class="tbl" id="masalah_kritis_table">
        <thead>
        <tr>
            <th style="width:44px">#</th>
            <th>Wilayah</th>
            <th>Indikator</th>
            <th>Pelaksana</th>
            <th>Jumlah</th>
            <th>Foto</th>
            <th>Foto Sesudah</th>
            <th>Koordinat</th>
            <th style="width:80px; text-align:center">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @php($no = 1)
        @if($masalah_kritis instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php($no = (($masalah_kritis->currentPage()-1) * $masalah_kritis->perPage()) + 1)
        @endif
        @foreach($masalah_kritis as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td style="line-height: 1.2;white-space: nowrap;">
                    <span>{{ $item->wilayah->nama }}</span> <br>
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
                <td style="line-height: 1.2;white-space: nowrap;">
                    <span>{{ $item->jumlah }}</span> <br>
                    <span style="font-size: 12px;color: gray;">{{ $item->satuan }}</span>
                </td>
                <td>
                    @if(($item->foto ?? '') !== '')
                        <a target="_blank" href="{{ asset('storage/' . $item->foto) }}">
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="" style="height: 30px;width: 30px;">
                        </a>
                    @endif
                </td>
                <td>
                    @if(($item->foto_sesudah ?? '') !== '')
                        <a target="_blank" href="{{ asset('storage/' . $item->foto_sesudah) }}">
                            <img src="{{ asset('storage/' . $item->foto_sesudah) }}" alt="" style="height: 30px;width: 30px;">
                        </a>
                    @endif
                </td>
                <td style="white-space: nowrap;">
                    @if(($item->latitude ?? '') != '')
                        <a class="btn-act edit me-1" target="_blank" href="https://www.google.com/maps/search/?api=1&query={{ ($item->latitude ?? '') }},{{ ($item->longitude ?? '') }}" title="Map"><i class="bi bi-geo-alt-fill"></i></a>
                        <a class="btn-act edit me-1" target="_blank" href="https://www.google.com/maps/@?api=1&map_action=pano&viewpoint={{ ($item->latitude ?? '') }},{{ ($item->longitude ?? '') }}&heading=0&pitch=0&fov=100" title="Map"><i class="bi bi-map-fill"></i></a>
                    @endif
                </td>
                <td style="text-align:center;white-space: nowrap;padding: 0;">
                    <button class="btn-act edit me-1" onclick="info({{ $item->id }})" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                    <button class="btn-act del" onclick="confirm_delete({{ $item->id }})" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if($masalah_kritis instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $masalah_kritis->links('vendor.pagination.custom') }}
@endif
