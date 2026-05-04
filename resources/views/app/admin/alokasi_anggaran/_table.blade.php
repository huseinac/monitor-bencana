<div class="tbl-wrap">
    <table class="tbl" id="alokasi_anggaran_table">
        <thead>
        <tr>
            <th style="width:44px">#</th>
            <th>Provinsi</th>
            <th>Kabupaten</th>
            <th>Peruntukan</th>
            <th>Nominal</th>
            <th style="width:80px; text-align:center">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @php($no = 1)
        @if($alokasi_anggarans instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php($no = (($alokasi_anggarans->currentPage()-1) * $alokasi_anggarans->perPage()) + 1)
        @endif
        @foreach($alokasi_anggarans as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td style="line-height: 1.2;white-space: nowrap;"><span>{{ $item->anggaran_daerah->wilayah->parent->nama }}</span></td>
                <td style="line-height: 1.2;white-space: nowrap;"><span>{{ $item->anggaran_daerah->wilayah->nama }}</span></td>
                <td>{{ $item->keterangan }}</td>
                <td style="white-space: nowrap;">{{ format_number($item->nominal) }}</td>
                <td style="text-align:center;white-space: nowrap;padding: 0;">
                    <button class="btn-act edit me-1" onclick="info({{ $item->id }})" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                    <button class="btn-act del" onclick="confirm_delete({{ $item->id }})" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if($alokasi_anggarans instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $alokasi_anggarans->links('vendor.pagination.custom') }}
@endif
