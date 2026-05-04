<form id="form_info">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><i class="bi bi-person-plus-fill me-2"></i>{{ !empty($kategori_paket_pekerjaan) ? 'Ubah' : 'Tambah' }} Alokasi Anggaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <x-io-input name="nama" caption="Nama" :value="$kategori_paket_pekerjaan->nama ?? ''" :viewtype="2" required />
        <x-io-input type="file" name="file_icon" caption="Icon Normal" :value="$kategori_paket_pekerjaan->icon ?? ''" :viewtype="2" />
    </div>
    <div class="modal-footer">
        <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">
            <i class="bi bi-x-lg"></i> Batal
        </button>
        <button type="submit" class="btn-primary-custom">
            <i class="bi bi-check-lg"></i> Simpan
        </button>
    </div>
</form>

<script>
    init_form_element();
    init_form({{ $kategori_paket_pekerjaan->id ?? '' }});
</script>
