<form id="form_info">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><i class="bi bi-person-plus-fill me-2"></i>{{ !empty($user) ? 'Ubah' : 'Tambah' }} User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <x-io-select name="akses" caption="Akses" :options="array_combine($list_akses, $list_akses)" :value="$user->akses ?? ''" :viewtype="2" required />
        <x-io-input name="nama" caption="Nama" :value="$user->nama ?? ''" :viewtype="2" required />
        <x-io-input name="email" caption="Username" :value="$user->email ?? ''" :viewtype="2" required />
        <x-io-input type="password" name="password" caption="Password" placeholder="{{ !empty($user) ? 'Kosongi apabila tidak diubah' : '' }}" :viewtype="2" />
        <x-io-input type="password" name="password_confirmation" caption="Ulangi Password" placeholder="{{ !empty($user) ? 'Kosongi apabila tidak diubah' : '' }}" :viewtype="2" />
    </div>
    <div class="modal-footer">
        <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">
            <i class="bi bi-x-lg"></i> Batal
        </button>
        <button type="submit" class="btn-primary-custom">
            <i class="bi bi-check-lg"></i>Simpan</span>
        </button>
    </div>
</form>

<script>
    init_form_element();
    init_form({{ $user->id ?? '' }});
</script>
