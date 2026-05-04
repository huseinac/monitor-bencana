<div class="tbl-wrap">
    <table class="tbl" id="wilayahTable">
        <thead>
        <tr>
            <th style="width:44px">#</th>
            <th>Nama</th>
            <th>Kode</th>
            <th>Parent Kode</th>
            <th>Koordinat</th>
            <th style="width:80px; text-align:center">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @php($no = 1)
        @if($wilayahs instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php($no = (($wilayahs->currentPage()-1) * $wilayahs->perPage()) + 1)
        @endif
        @foreach($wilayahs as $wilayah)
            <tr>
                <td>{{ $no++ }}</td>
                <td class="text-nowrap">{{ $wilayah->nama }}</td>
                <td>{{ $wilayah->kode }}</td>
                <td>{{ $wilayah->parent_kode }}</td>
                <td style="white-space: nowrap;">
                    @if(($wilayah->latitude ?? '') != '')
                        <a class="btn-act edit me-1" target="_blank" href="https://www.google.com/maps/search/?api=1&query={{ ($wilayah->latitude ?? '') }},{{ ($wilayah->longitude ?? '') }}" title="Map"><i class="bi bi-geo-alt-fill"></i></a>
                        <a class="btn-act edit me-1" target="_blank" href="https://www.google.com/maps/@?api=1&map_action=pano&viewpoint={{ ($wilayah->latitude ?? '') }},{{ ($wilayah->longitude ?? '') }}&heading=0&pitch=0&fov=100" title="Map"><i class="bi bi-map-fill"></i></a>
                    @endif
                </td>
                <td style="text-align:center;white-space: nowrap;padding: 0;">
                    <button class="btn-act edit me-1" onclick="info({{ $wilayah->id }})" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                    <button class="btn-act del" onclick="confirm_delete({{ $wilayah->id }})" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if($wilayahs instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $wilayahs->links('vendor.pagination.custom') }}
@endif
