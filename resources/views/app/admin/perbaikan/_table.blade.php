<div class="tbl-wrap">
    <table class="tbl" id="perbaikanTable">
        <thead>
        <tr>
            <th style="width:44px">#</th>
            <th>Dampak Bencana</th>
            <th>Wilayah</th>
            <th>Pelaksana</th>
            <th>Tanggal</th>
            <th>Jumlah</th>
            <th>Foto</th>
            <th style="width:80px; text-align:center">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @php($no = 1)
        @if($perbaikans instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php($no = (($perbaikans->currentPage()-1) * $perbaikans->perPage()) + 1)
        @endif
        @foreach($perbaikans as $perbaikan)
            <tr>
                <td>{{ $no++ }}</td>
                <td style="line-height: 1.2;white-space: nowrap;">
                    <span>{{ $perbaikan->sektor_terdampak->wilayah->nama }}</span> <br>
                    <span style="font-size: 12px;color: gray;">{{ $perbaikan->sektor_terdampak->wilayah->parent->nama ?? '' }}</span>
                    <span style="font-size: 12px;color: gray;">- {{ $perbaikan->sektor_terdampak->wilayah->parent->parent->nama ?? '' }}</span>
                </td>
                <td style="line-height: 1.2;white-space: nowrap;">
                    <span>{{ $perbaikan->sektor_terdampak->indikator->nama }}</span> <br>
                    <span style="font-size: 12px;color: gray;">{{ $perbaikan->sektor_terdampak->indikator->parent->nama ?? '' }}</span>
                </td>
                <td style="line-height: 1.2;white-space: nowrap;">
                    <span>{{ $perbaikan->sektor_terdampak->pelaksana->nama }}</span> <br>
                    <span style="font-size: 12px;color: gray;">{{ $perbaikan->sektor_terdampak->pelaksana->singkatan ?? '' }}</span>
                </td>
                <td>{{ format_date($perbaikan->tanggal) }}</td>
                <td style="line-height: 1.2;white-space: nowrap;">
                    <span>{{ $perbaikan->jumlah }}</span> <br>
                    <span style="font-size: 12px;color: gray;">{{ $perbaikan->sektor_terdampak->satuan }}</span>
                </td>
                <td>
                    @if(($perbaikan->foto ?? '') !== '')
                        <a target="_blank" href="{{ asset('storage/' . $perbaikan->foto) }}">
                            <img src="{{ asset('storage/' . $perbaikan->foto) }}" alt="" style="height: 30px;width: 30px;">
                        </a>
                    @endif
                </td>
                <td style="text-align:center;white-space: nowrap;padding: 0;">
                    <button class="btn-act edit me-1" onclick="info({{ $perbaikan->id }})" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                    <button class="btn-act del" onclick="confirm_delete({{ $perbaikan->id }})" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if($perbaikans instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $perbaikans->links('vendor.pagination.custom') }}
@endif
