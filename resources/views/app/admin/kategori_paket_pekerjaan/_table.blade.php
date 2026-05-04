<div class="tbl-wrap">
    <table class="tbl" id="kategori_paket_pekerjaan_table">
        <thead>
        <tr>
            <th style="width:44px">#</th>
            <th>Nama</th>
            <th>Icon</th>
            <th style="width:80px; text-align:center">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @php($no = 1)
        @if($kategori_paket_pekerjaans instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php($no = (($kategori_paket_pekerjaans->currentPage()-1) * $kategori_paket_pekerjaans->perPage()) + 1)
        @endif
        @foreach($kategori_paket_pekerjaans as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item->nama }}</td>
                <td class="py-0" style="vertical-align: middle;">
                    @if(($item->icon ?? '') !== '')
                        <a target="_blank" href="{{ asset('storage/' . $item->icon) }}">
                            <img src="{{ asset('storage/' . $item->icon) }}" alt="" style="height: 30px;width: 30px;">
                        </a>
                    @endif
                </td>
                <td style="text-align:center;white-space: nowrap;padding: 0;">
                    <button class="btn-act edit me-1" onclick="info({{ $item->id }})" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                    <button class="btn-act del" onclick="confirm_delete({{ $item->id }})" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if($kategori_paket_pekerjaans instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $kategori_paket_pekerjaans->links('vendor.pagination.custom') }}
@endif
