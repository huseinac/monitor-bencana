<div class="tbl-wrap">
    <table class="tbl">
        <thead>
        <tr>
            <th style="width:44px">#</th>
            <th>Tanggal</th>
            <th>Nominal</th>
            <th style="width:80px; text-align:center">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @php($no = 1)
        @if($pembayaran_pekerjaans instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php($no = (($pembayaran_pekerjaans->currentPage()-1) * $pembayaran_pekerjaans->perPage()) + 1)
        @endif
        @foreach($pembayaran_pekerjaans as $pembayaran_pekerjaan)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ format_date($pembayaran_pekerjaan->tanggal) }}</td>
                <td>{{ format_number($pembayaran_pekerjaan->nominal) }}</td>
                <td style="text-align:center;white-space: nowrap;padding: 0;">
                    <button class="btn-act edit me-1" onclick="info({{ $pembayaran_pekerjaan->id }})" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                    <button class="btn-act del" onclick="confirm_delete({{ $pembayaran_pekerjaan->id }})" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if($pembayaran_pekerjaans instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $pembayaran_pekerjaans->links('vendor.pagination.custom') }}
@endif
