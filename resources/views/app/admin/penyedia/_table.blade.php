<div class="tbl-wrap">
    <table class="tbl">
        <thead>
        <tr>
            <th style="width:44px">#</th>
            <th>Nama</th>
            <th>Singkatan</th>
            <th>Keterangan</th>
            <th style="width:80px; text-align:center">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @php($no = 1)
        @if($penyedias instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php($no = (($penyedias->currentPage()-1) * $penyedias->perPage()) + 1)
        @endif
        @foreach($penyedias as $penyedia)
            <tr>
                <td>{{ $no++ }}</td>
                <td class="text-nowrap">{{ $penyedia->nama }}</td>
                <td>{{ $penyedia->singkatan }}</td>
                <td>{{ $penyedia->keterangan }}</td>
                <td style="text-align:center;white-space: nowrap;padding: 0;">
                    <button class="btn-act edit me-1" onclick="info({{ $penyedia->id }})" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                    <button class="btn-act del" onclick="confirm_delete({{ $penyedia->id }})" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if($penyedias instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $penyedias->links('vendor.pagination.custom') }}
@endif
