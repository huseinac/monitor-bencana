<form id="form_info">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><i class="bi bi-person-plus-fill me-2"></i>{{ !empty($rincian_pekerjaan) ? 'Ubah' : 'Tambah' }} Penyedia</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <x-io-input name="nama" caption="Nama" :value="$rincian_pekerjaan->nama ?? ''" :viewtype="2" required />
        <x-io-input name="target" caption="Target" :value="$rincian_pekerjaan->target ?? ''" :viewtype="2" required />
        <x-io-input name="satuan" caption="Satuan" :value="$rincian_pekerjaan->satuan ?? ''" :viewtype="2" required />
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
    init_form({{ $rincian_pekerjaan->id ?? '' }});
</script>
