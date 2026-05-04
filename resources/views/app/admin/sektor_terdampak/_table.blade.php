<div class="tbl-wrap">
    <table class="tbl" id="sektor_terdampakTable">
        <thead>
        <tr>
            <th style="width:44px">#</th>
            <th>Wilayah</th>
            <th>Indikator</th>
            <th>Nama Lokasi</th>
            <th>Keterangan</th>
            <th class="text-center">Foto</th>
            <th class="text-center">Foto After</th>
            <th>Koordinat</th>
            <th style="width:80px; text-align:center">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @php($no = 1)
        @if($sektor_terdampaks instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php($no = (($sektor_terdampaks->currentPage()-1) * $sektor_terdampaks->perPage()) + 1)
        @endif
        @foreach($sektor_terdampaks as $sektor_terdampak)
            <tr>
                <td>{{ $no++ }}</td>
                <td style="line-height: 1.2;white-space: nowrap;">
                    <span>{{ $sektor_terdampak->wilayah->nama }}</span> <br>
                    <span style="font-size: 12px;color: gray;">{{ $sektor_terdampak->wilayah->parent->nama ?? '' }}</span>
                    <span style="font-size: 12px;color: gray;">- {{ $sektor_terdampak->wilayah->parent->parent->nama ?? '' }}</span>
                </td>
                <td style="line-height: 1.2;white-space: nowrap;">
                    <span>{{ $sektor_terdampak->indikator->nama ?? '' }}</span> <br>
                    <span style="font-size: 12px;color: gray;">{{ $sektor_terdampak->indikator->parent->nama ?? '' }}</span>
                </td>
                <td style="line-height: 1.2;"><span>{{ $sektor_terdampak->nama_lokasi }}</span></td>
                <td style="line-height: 1.2;">
                    <span>{{ $sektor_terdampak->kondisi_awal }}</span> <br>
                    <span style="font-size: 12px;color: gray;">{{ $sektor_terdampak->keterangan }}</span>
                </td>
                <td class="text-center">
                    @if(($sektor_terdampak->foto_sebelum ?? '') !== '')
                        <a target="_blank" href="{{ asset('storage/' . $sektor_terdampak->foto_sebelum) }}">
                            <img src="{{ asset('storage/' . $sektor_terdampak->foto_sebelum) }}" alt="" style="height: 30px;width: 30px;">
                        </a>
                    @endif
                </td>
                <td class="text-center">
                    @if(($sektor_terdampak->foto_sesudah ?? '') !== '')
                        <a target="_blank" href="{{ asset('storage/' . $sektor_terdampak->foto_sesudah) }}">
                            <img src="{{ asset('storage/' . $sektor_terdampak->foto_sesudah) }}" alt="" style="height: 30px;width: 30px;">
                        </a>
                    @endif
                </td>
                <td style="white-space: nowrap;">
                    @if(($sektor_terdampak->latitude ?? '') != '')
                        <a class="btn-act edit me-1" target="_blank" href="https://www.google.com/maps/search/?api=1&query={{ ($sektor_terdampak->latitude ?? '') }},{{ ($sektor_terdampak->longitude ?? '') }}" title="Map"><i class="bi bi-geo-alt-fill"></i></a>
                        <a class="btn-act edit me-1" target="_blank" href="https://www.google.com/maps/@?api=1&map_action=pano&viewpoint={{ ($sektor_terdampak->latitude ?? '') }},{{ ($sektor_terdampak->longitude ?? '') }}&heading=0&pitch=0&fov=100" title="Map"><i class="bi bi-map-fill"></i></a>
                    @endif
                </td>
                <td style="text-align:center;white-space: nowrap;padding: 0;">
                    <button class="btn-act edit me-1" onclick="info({{ $sektor_terdampak->id }})" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                    <button class="btn-act del" onclick="confirm_delete({{ $sektor_terdampak->id }})" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if($sektor_terdampaks instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $sektor_terdampaks->links('vendor.pagination.custom') }}
@endif
