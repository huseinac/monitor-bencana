<div class="tbl-wrap">
    <table class="tbl">
        <thead>
        <tr>
            <th style="width:44px">#</th>
            <th>Rincian Pekerjaan</th>
            <th class="text-center">Target</th>
            <th>Tanggal Awal</th>
            <th>Tanggal Akhir</th>
            <th style="width:80px; text-align:center">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @php($no = 1)
        @if($timeline_pekerjaans instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php($no = (($timeline_pekerjaans->currentPage()-1) * $timeline_pekerjaans->perPage()) + 1)
        @endif
        @foreach($timeline_pekerjaans as $timeline_pekerjaan)
            <tr>
                <td>{{ $no++ }}</td>
                <td class="text-nowrap">{{ $timeline_pekerjaan->rincian_pekerjaan->nama }}</td>
                <td class="text-center">{{ $timeline_pekerjaan->target }} {{ $timeline_pekerjaan->rincian_pekerjaan->satuan }}</td>
                <td>{{ format_date($timeline_pekerjaan->tanggal_awal) }}</td>
                <td>{{ format_date($timeline_pekerjaan->tanggal_akhir) }}</td>
                <td style="text-align:center;white-space: nowrap;padding: 0;">
                    <button class="btn-act edit me-1" onclick="info({{ $timeline_pekerjaan->id }})" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                    <button class="btn-act del" onclick="confirm_delete({{ $timeline_pekerjaan->id }})" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if($timeline_pekerjaans instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $timeline_pekerjaans->links('vendor.pagination.custom') }}
@endif
