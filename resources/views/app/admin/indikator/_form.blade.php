<form id="form_info">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><i class="bi bi-person-plus-fill me-2"></i>{{ !empty($indikator) ? 'Ubah' : 'Tambah' }} Indikator</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <x-io-input name="nama" caption="Nama" :value="$indikator->nama ?? ''" :viewtype="2" required />
        <x-io-input name="kode" caption="Kode" :value="$indikator->kode ?? ''" :viewtype="2" required />
        <x-io-input name="parent_kode" caption="Parent Kode" :value="$indikator->parent_kode ?? ''" :viewtype="2" />
        <x-io-input name="satuan" caption="Satuan" :value="$indikator->satuan ?? ''" :viewtype="2" />
        <x-io-select name="pelaksana_id" caption="Pelaksana" placeholder="Kosong" :options="$list_pelaksana" :value="$indikator->list_pelaksana[0]->pelaksana_id ?? ''" :viewtype="2" />
        <div class="row">
            <div class="col-lg-4">
                <x-io-input type="file" name="file_icon" caption="Icon Normal" :value="$indikator->icon ?? ''" :viewtype="2" />
            </div>
            <div class="col-lg-4">
                <x-io-input type="file" name="file_icon2" caption="Icon Mendekati" :value="$indikator->icon2 ?? ''" :viewtype="2" />
            </div>
            <div class="col-lg-4">
                <x-io-input type="file" name="file_icon3" caption="Icon Atensi" :value="$indikator->icon3 ?? ''" :viewtype="2" />
            </div>
        </div>

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
    init_form({{ $indikator->id ?? '' }});
</script>
