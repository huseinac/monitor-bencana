<div class="tbl-wrap">
    <table class="tbl">
        <thead>
        <tr>
            <th style="width:44px">#</th>
            <th>Nama</th>
            <th>Target</th>
            <th>Satuan</th>
            <th style="width:80px; text-align:center">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @php($no = 1)
        @if($rincian_pekerjaans instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php($no = (($rincian_pekerjaans->currentPage()-1) * $rincian_pekerjaans->perPage()) + 1)
        @endif
        @foreach($rincian_pekerjaans as $rincian_pekerjaan)
            <tr>
                <td>{{ $no++ }}</td>
                <td class="text-nowrap">{{ $rincian_pekerjaan->nama }}</td>
                <td>{{ $rincian_pekerjaan->target }}</td>
                <td>{{ $rincian_pekerjaan->satuan }}</td>
                <td style="text-align:center;white-space: nowrap;padding: 0;">
                    <button class="btn-act edit me-1" onclick="info({{ $rincian_pekerjaan->id }})" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                    <button class="btn-act del" onclick="confirm_delete({{ $rincian_pekerjaan->id }})" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if($rincian_pekerjaans instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $rincian_pekerjaans->links('vendor.pagination.custom') }}
@endif
