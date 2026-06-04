<div class="tbl-wrap">
    <table class="tbl" id="anggaran_daerah_table">
        <thead>
        <tr>
            <th style="width:44px">#</th>
            <th>Provinsi</th>
            <th>Kabupaten</th>
            <th>Anggaran 2025</th>
            <th>Anggaran 2026</th>
            <th>Selisih 2025-2026</th>
            <th>Penyesuaian</th>
            <th>Total TKD</th>
            <th style="width:80px; text-align:center">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @php($no = 1)
        @if($anggaran_daerahs instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php($no = (($anggaran_daerahs->currentPage()-1) * $anggaran_daerahs->perPage()) + 1)
        @endif
        @foreach($anggaran_daerahs as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td style="line-height: 1.2;white-space: nowrap;"><span>{{ isset($item->wilayah->parent->nama) ? $item->wilayah->parent->nama : 'Provinsi '.$item->wilayah->nama }}</span></td>
                <td style="line-height: 1.2;white-space: nowrap;"><span>{{ isset($item->wilayah->parent->nama) ? $item->wilayah->nama : '-' }}</span></td>
                <td>{{ format_number($item->anggaran_2025) }}</td>
                <td>{{ format_number($item->anggaran_2026) }}</td>
                <td>{{ format_number($item->anggaran_2026 - $item->anggaran_2025) }}</td>
                <td>{{ format_number($item->penyesuaian) }}</td>
                <td>{{ format_number($item->anggaran_2026 + $item->penyesuaian) }}</td>
                <td style="text-align:center;white-space: nowrap;padding: 0;">
                    <button class="btn-act edit me-1" onclick="info({{ $item->id }})" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                    <button class="btn-act del" onclick="confirm_delete({{ $item->id }})" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if($anggaran_daerahs instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $anggaran_daerahs->links('vendor.pagination.custom') }}
@endif
