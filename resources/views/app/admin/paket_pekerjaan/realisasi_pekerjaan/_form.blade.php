<form id="form_info">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><i class="bi bi-person-plus-fill me-2"></i>{{ !empty($realisasi_pekerjaan) ? 'Ubah' : 'Tambah' }} Penyedia</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <x-io-select name="rincian_pekerjaan_id" caption="Rincian Pekerjaan" :options="$list_rincian_pekerjaan" :value="$realisasi_pekerjaan->rincian_pekerjaan_id ?? ''" :viewtype="2" required />
        <x-io-input name="tanggal" caption="Tanggal" :value="$realisasi_pekerjaan->tanggal ?? ''" class="datepicker" :viewtype="2" required />
        <x-io-input name="realisasi" caption="Realisasi" :value="$realisasi_pekerjaan->realisasi ?? ''" :viewtype="2" required />
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
    init_form({{ $realisasi_pekerjaan->id ?? '' }});
</script>
