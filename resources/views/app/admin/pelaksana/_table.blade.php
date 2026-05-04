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
        @if($pelaksanas instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php($no = (($pelaksanas->currentPage()-1) * $pelaksanas->perPage()) + 1)
        @endif
        @foreach($pelaksanas as $pelaksana)
            <tr>
                <td>{{ $no++ }}</td>
                <td class="text-nowrap">{{ $pelaksana->nama }}</td>
                <td>{{ $pelaksana->singkatan }}</td>
                <td>{{ $pelaksana->keterangan }}</td>
                <td style="text-align:center;white-space: nowrap;padding: 0;">
                    <button class="btn-act edit me-1" onclick="info({{ $pelaksana->id }})" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                    <button class="btn-act del" onclick="confirm_delete({{ $pelaksana->id }})" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if($pelaksanas instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $pelaksanas->links('vendor.pagination.custom') }}
@endif
