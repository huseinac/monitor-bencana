<form id="form_info">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><i class="bi bi-person-plus-fill me-2"></i>{{ !empty($alokasi_anggaran) ? 'Ubah' : 'Tambah' }} Alokasi Anggaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <x-io-select name="anggaran_daerah_id" caption="Anggaran" :options="$list_anggaran_daerah" placeholder="-Pilih Anggaran-" :value="$alokasi_anggaran->anggaran_daerah_id ?? ''" :viewtype="2" required />
        <x-io-input type="text" name="nama_realisasi" caption="Nama realisasi" :value="$alokasi_anggaran->nama_realisasi ?? ''" :viewtype="2" required />
        <x-io-input type="number" name="nominal" caption="Nominal" :value="$alokasi_anggaran->nominal ?? ''" :viewtype="2" required />
        <x-io-textarea name="keterangan" caption="Keterangan" :value="$alokasi_anggaran->keterangan ?? ''" :viewtype="2" required />
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
    init_form({{ $alokasi_anggaran->id ?? '' }});
</script>
