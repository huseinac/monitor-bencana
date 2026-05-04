<div class="tbl-wrap">
    <table class="tbl" id="indikatorTable">
        <thead>
        <tr>
            <th style="width:44px">#</th>
            <th>Nama</th>
            <th>Kode</th>
            <th>Pelaksana</th>
            <th>Satuan</th>
            <th>Icon</th>
            <th style="width:80px; text-align:center">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @php($no = 1)
        @if($indikators instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php($no = (($indikators->currentPage()-1) * $indikators->perPage()) + 1)
        @endif
        @foreach($indikators as $indikator)
            <tr>
                <td>{{ $no++ }}</td>
                <td class="text-nowrap">{{ $indikator->nama }}</td>
                <td>{{ $indikator->kode }}</td>
                <td>{{ join(',', $indikator->list_pelaksana->pluck('pelaksana.nama')->toArray()) }}</td>
                <td>{{ $indikator->satuan }}</td>
                <td class="py-0" style="vertical-align: middle;">
                    <div class="d-flex flex-row gap-2">
                        @if(($indikator->icon ?? '') !== '')
                            <a target="_blank" href="{{ asset('storage/' . $indikator->icon) }}">
                                <img src="{{ asset('storage/' . $indikator->icon) }}" alt="" style="height: 30px;width: 30px;">
                            </a>
                        @endif
                        @if(($indikator->icon2 ?? '') !== '')
                            <a target="_blank" href="{{ asset('storage/' . $indikator->icon2) }}">
                                <img src="{{ asset('storage/' . $indikator->icon2) }}" alt="" style="height: 30px;width: 30px;">
                            </a>
                        @endif
                        @if(($indikator->icon3 ?? '') !== '')
                            <a target="_blank" href="{{ asset('storage/' . $indikator->icon3) }}">
                                <img src="{{ asset('storage/' . $indikator->icon3) }}" alt="" style="height: 30px;width: 30px;">
                            </a>
                        @endif
                    </div>
                </td>
                <td style="text-align:center;white-space: nowrap;padding: 0;">
                    <button class="btn-act edit me-1" onclick="info({{ $indikator->id }})" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                    <button class="btn-act del" onclick="confirm_delete({{ $indikator->id }})" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if($indikators instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $indikators->links('vendor.pagination.custom') }}
@endif
