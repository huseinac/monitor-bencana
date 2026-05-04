<div class="tbl-wrap">
    <table class="tbl" id="userTable">
        <thead>
        <tr>
            <th style="width:44px">#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Hak Akses</th>
            <th style="width:80px; text-align:center">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @php($no = 1)
        @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php($no = (($users->currentPage()-1) * $users->perPage()) + 1)
        @endif
        @foreach($users as $user)
            <tr>
                <td>{{ $no++ }}</td>
                <td class="text-nowrap">{{ $user->nama }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->akses }}</td>
                <td style="text-align:center;white-space: nowrap;">
                    <button class="btn-act edit me-1" onclick="info({{ $user->id }})" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                    <button class="btn-act del" onclick="confirm_delete({{ $user->id }})" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $users->links('vendor.pagination.custom') }}
@endif
