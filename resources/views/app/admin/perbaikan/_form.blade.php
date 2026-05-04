<form id="form_info">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><i class="bi bi-person-plus-fill me-2"></i>{{ !empty($perbaikan) ? 'Ubah' : 'Tambah' }} Perbaikan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <x-io-select name="sektor_terdampak_id" caption="Dampak Bencana" :options="$list_sektor_terdampak" :value="$perbaikan->sektor_terdampak_id ?? ''" :viewtype="2" required />
        <x-io-input name="tanggal" caption="Tanggal" :value="$perbaikan->tanggal ?? ''" class="datepicker" :viewtype="2" required />
        <x-io-input name="pelapor" caption="Pelapor" :value="$perbaikan->pelapor ?? ''" :viewtype="2" />
        <x-io-textarea name="keterangan" caption="Keterangan" :value="$perbaikan->keterangan ?? ''" :viewtype="2" />
        <x-io-input type="file" name="file_foto" caption="Foto" :value="$perbaikan->foto ?? ''" :viewtype="2" />
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
    init_form({{ $perbaikan->id ?? '' }});
</script>
