<form id="form_info">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><i class="bi bi-person-plus-fill me-2"></i>{{ !empty($masalah_kritis) ? 'Ubah' : 'Tambah' }} Masalah Kritis</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <x-io-select name="provinsi_id" caption="Provinsi" placeholder="-Pilih Provinsi-" :value="$masalah_kritis->wilayah->parent->parent->wilayah_id ?? ''" :viewtype="2" required />
        <x-io-select name="kabupaten_id" caption="Kabupaten / Kota" placeholder="-Pilih Kabupaten-" :value="$masalah_kritis->wilayah->parent->wilayah_id ?? ''" :viewtype="2" required />
        <x-io-select name="wilayah_id" caption="Kecamatan" placeholder="-Pilih Kecamatan-" :value="$masalah_kritis->wilayah_id ?? ''" :viewtype="2" required />
        <x-io-select name="indikator_id" caption="Indikator" :options="$list_indikator" placeholder="-Kosong-" :value="$masalah_kritis->indikator_id ?? ''" :viewtype="2" required />
        <x-io-select name="pelaksana_id" caption="Pelaksana" :options="$list_pelaksana" placeholder="-Kosong-" :value="$masalah_kritis->indikator_id ?? ''" :viewtype="2" required />
        <div class="row">
            <div class="col-lg-8">
                <x-io-input type="number" name="jumlah" caption="Jumlah" :value="$masalah_kritis->jumlah ?? ''" :viewtype="2" required />
            </div>
            <div class="col-lg-4">
                <x-io-input name="satuan" caption="Satuan" :value="$masalah_kritis->satuan ?? ''" :viewtype="2" required />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <x-io-input name="latitude" caption="Latitude" :value="$masalah_kritis->latitude ?? ''" :viewtype="2" />
            </div>
            <div class="col-lg-6">
                <x-io-input name="longitude" caption="Longitude" :value="$masalah_kritis->longitude ?? ''" :viewtype="2" />
            </div>
        </div>
        <x-io-textarea name="keterangan" caption="Keterangan" :value="$masalah_kritis->keterangan ?? ''" :viewtype="2" />
        <x-io-input type="file" name="file_foto" caption="Foto Lokasi" :value="$masalah_kritis->foto ?? ''" :viewtype="2" />
        <x-io-input type="file" name="file_foto_sesudah" caption="Foto Sesudah" :value="$masalah_kritis->foto_sesudah ?? ''" :viewtype="2" />
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
    init_form({{ $masalah_kritis->id ?? '' }});

    $provinsi_id = $('#provinsi_id');
    $provinsi_id.html('<option value="">-Pilih Provinsi-</option>');
    $.each(list_provinsi, (i, val) => {
        let selected_kode = '{{ $masalah_kritis->wilayah->parent->parent_kode ?? '' }}';
        console.log(selected_kode);
        $provinsi_id.append('<option value="'+ val.kode +'" ' + (val.kode === selected_kode ? 'selected' : '') + '>'+ val.nama +'</option>');
    });

    $kabupaten_id = $('#kabupaten_id');
    $provinsi_id.change(() => {
        $kabupaten_id.html('<option value="">-Pilih Kabupaten-</option>');
        let parent_kode = $provinsi_id.find('option:selected').val();

        let selected_kode = '{{ $masalah_kritis->wilayah->parent_kode ?? '' }}';
        $.each(list_kabupaten.filter(item => item.parent_kode.toString() === parent_kode.toString()), (i, val) => {
            $kabupaten_id.append('<option value="'+ val.kode +'" ' + (val.kode === selected_kode ? 'selected' : '') + '>'+ val.nama +'</option>');
        });
    })
    $provinsi_id.change();

    $kecamatan_id = $('#wilayah_id');
    $kabupaten_id.change(() => {
        $kecamatan_id.html('<option value="">-Pilih Kecamatan-</option>');
        let parent_kode = $kabupaten_id.find('option:selected').val();
        let selected_id = '{{ $masalah_kritis->wilayah_id ?? '' }}';
        $.each(list_kecamatan.filter(item => item.parent_kode.toString() === parent_kode.toString()), (i, val) => {
            $kecamatan_id.append('<option value="'+ val.id +'" ' + (val.id.toString() === selected_id ? 'selected' : '') + '>'+ val.nama +'</option>');
        });
    });
    $kabupaten_id.change();
</script>
