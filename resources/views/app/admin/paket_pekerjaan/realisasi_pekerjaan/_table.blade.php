<div class="tbl-wrap">
    <table class="tbl">
        <thead>
        <tr>
            <th style="width:44px">#</th>
            <th>Rincian Pekerjaan</th>
            <th>Tanggal</th>
            <th>Realisasi</th>
            <th style="width:80px; text-align:center">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @php($no = 1)
        @if($realisasi_pekerjaans instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php($no = (($realisasi_pekerjaans->currentPage()-1) * $realisasi_pekerjaans->perPage()) + 1)
        @endif
        @foreach($realisasi_pekerjaans as $realisasi_pekerjaan)
            <tr>
                <td>{{ $no++ }}</td>
                <td class="text-nowrap">{{ $realisasi_pekerjaan->rincian_pekerjaan->nama }}</td>
                <td>{{ format_date($realisasi_pekerjaan->tanggal) }}</td>
                <td>{{ $realisasi_pekerjaan->realisasi }} {{ $realisasi_pekerjaan->rincian_pekerjaan->satuan }}</td>
                <td style="text-align:center;white-space: nowrap;padding: 0;">
                    <button class="btn-act edit me-1" onclick="info({{ $realisasi_pekerjaan->id }})" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                    <button class="btn-act del" onclick="confirm_delete({{ $realisasi_pekerjaan->id }})" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if($realisasi_pekerjaans instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $realisasi_pekerjaans->links('vendor.pagination.custom') }}
@endif
