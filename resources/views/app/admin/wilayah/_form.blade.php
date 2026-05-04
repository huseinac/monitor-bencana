<form id="form_info">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><i class="bi bi-person-plus-fill me-2"></i>{{ !empty($wilayah) ? 'Ubah' : 'Tambah' }} Wilayah</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <x-io-input name="nama" caption="Nama" :value="$wilayah->nama ?? ''" :viewtype="2" required />
        <x-io-input name="kode" caption="Kode" :value="$wilayah->kode ?? ''" :viewtype="2" required />
        <x-io-input name="parent_kode" caption="Parent Kode" :value="$wilayah->parent_kode ?? ''" :viewtype="2" />
        <x-io-select name="kondisi" caption="Kondisi" :options="$list_kondisi" placeholder="-Pilih Kondisi-" :value="$wilayah->kondisi ?? ''" :viewtype="2" required />
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
    init_form({{ $wilayah->id ?? '' }});
</script>
