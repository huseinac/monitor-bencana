<form id="form_info">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><i class="bi bi-person-plus-fill me-2"></i>{{ !empty($anggaran_daerah) ? 'Ubah' : 'Tambah' }} Anggaran Daerah</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <x-io-select name="provinsi_id" caption="Provinsi" placeholder="-Pilih Provinsi-" :options="$list_provinsi" :value="$anggaran_daerah->wilayah->parent->wilayah_id ?? ''" :viewtype="2" required />
        <x-io-select name="wilayah_id" caption="Kabupaten" placeholder="-Pilih Kabupaten-" :value="$anggaran_daerah->wilayah_id ?? ''" :viewtype="2" required />
        <x-io-input type="number" name="anggaran_2025" caption="Anggaran 2025" :value="$anggaran_daerah->anggaran_2025 ?? ''" :viewtype="2" required />
        <x-io-input type="number" name="anggaran_2026" caption="Anggaran 2026" :value="$anggaran_daerah->anggaran_2026 ?? ''" :viewtype="2" required />
        <x-io-input type="number" name="penyesuaian" caption="Penyesuaian" :value="$anggaran_daerah->penyesuaian ?? ''" :viewtype="2" required />
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
    init_form({{ $anggaran_daerah->id ?? '' }});

    $provinsi_id = $('#provinsi_id');
    $provinsi_id.html('<option value="">-Pilih Provinsi-</option>');

    console.log(list_provinsi);
    $.each(list_provinsi, (i, val) => {
        let selected_kode = '{{ $anggaran_daerah->wilayah->parent_kode ?? '' }}';
        $provinsi_id.append('<option value="'+ val.kode +'" ' + (val.kode === selected_kode ? 'selected' : '') + '>'+ val.nama +'</option>');
    });

    $wilayah_id = $('#wilayah_id');
    $provinsi_id.change(() => {
        $wilayah_id.html('<option value="">-Pilih Kabupaten-</option>');
        let parent_kode = $provinsi_id.find('option:selected').val();

        let selected_id = '{{ $anggaran_daerah->wilayah_id ?? '' }}';
        $.each(list_kabupaten.filter(item => item.parent_kode.toString() === parent_kode.toString()), (i, val) => {
            $wilayah_id.append('<option value="'+ val.id +'" ' + (val.id === selected_id ? 'selected' : '') + '>'+ val.nama +'</option>');
        });
    })
    $provinsi_id.change();
</script>
