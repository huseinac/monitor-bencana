<form id="form_info">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><i class="bi bi-person-plus-fill me-2"></i>{{ !empty($sektor_terdampak) ? 'Ubah' : 'Tambah' }} Dampak Bencana</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-6">
                <x-io-select name="provinsi_id" caption="Provinsi" placeholder="-Pilih Provinsi-" :value="$sektor_terdampak->wilayah->parent->parent->wilayah_id ?? ''" :viewtype="2" required />
                <x-io-select name="kabupaten_id" caption="Kabupaten / Kota" placeholder="-Pilih Kabupaten-" :value="$sektor_terdampak->wilayah->parent->wilayah_id ?? ''" :viewtype="2" required />
                <x-io-select name="wilayah_id" caption="Kecamatan" placeholder="-Pilih Kecamatan-" :value="$sektor_terdampak->wilayah_id ?? ''" :viewtype="2" required />
                <x-io-select name="indikator_id" caption="Indikator" :options="$list_indikator" placeholder="-Pilih Indikator-" :value="$sektor_terdampak->indikator_id ?? ''" :viewtype="2" required />
                <x-io-select name="kondisi" caption="Kondisi" :options="$list_kondisi" placeholder="-Pilih Kondisi-" :value="$sektor_terdampak->kondisi ?? ''" :viewtype="2" required />
                <x-io-select name="status" caption="Status" :options="['Normal' => 'Normal', 'Mendekati' => 'Mendekati', 'Atensi' => 'Atensi']" placeholder="-Pilih Kondisi-" :value="$sektor_terdampak->status ?? ''" :viewtype="2" required />
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-6">
                        <x-io-input name="latitude" caption="Latitude" :value="$sektor_terdampak->latitude ?? ''" :viewtype="2" />
                    </div>
                    <div class="col-lg-6">
                        <x-io-input name="longitude" caption="Longitude" :value="$sektor_terdampak->longitude ?? ''" :viewtype="2" />
                    </div>
                </div>
                <x-io-input name="nama_lokasi" caption="Nama Lokasi" :value="$sektor_terdampak->nama_lokasi ?? ''" :viewtype="2" :rows="2" />
                <x-io-textarea name="kondisi_awal" caption="Kondisi Awal" :value="$sektor_terdampak->kondisi_awal ?? ''" :viewtype="2" :rows="2" />
                <x-io-textarea name="keterangan" caption="Keterangan" :value="$sektor_terdampak->keterangan ?? ''" :viewtype="2" :rows="2" />
                <div class="row">
                    <div class="col-lg-6">
                        <x-io-input type="file" name="file_foto_sebelum" caption="Foto Setelah Bencana" :value="$sektor_terdampak->foto_sebelum ?? ''" :viewtype="2" />
                    </div>
                    <div class="col-lg-6">
                        <x-io-input type="file" name="file_foto_sesudah" caption="Foto Saat Ini" :value="$sektor_terdampak->foto_sesudah ?? ''" :viewtype="2" />
                    </div>
                </div>
            </div>
        </div>
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
    init_form({{ $sektor_terdampak->id ?? '' }});

    $provinsi_id = $('#provinsi_id');
    $provinsi_id.html('<option value="">-Pilih Provinsi-</option>');
    $.each(list_provinsi, (i, val) => {
        let selected_kode = '{{ $sektor_terdampak->wilayah->parent->parent_kode ?? '' }}';
        $provinsi_id.append('<option value="'+ val.kode +'" ' + (val.kode === selected_kode ? 'selected' : '') + '>'+ val.nama +'</option>');
    });

    $kabupaten_id = $('#kabupaten_id');
    $provinsi_id.change(() => {
        $kabupaten_id.html('<option value="">-Pilih Kabupaten-</option>');
        let parent_kode = $provinsi_id.find('option:selected').val();

        let selected_kode = '{{ $sektor_terdampak->wilayah->parent_kode ?? '' }}';
        console.log(selected_kode);
        console.log(list_kabupaten.filter(item => item.parent_kode.toString() === parent_kode.toString()));
        $.each(list_kabupaten.filter(item => item.parent_kode.toString() === parent_kode.toString()), (i, val) => {
            $kabupaten_id.append('<option value="'+ val.kode +'" ' + (val.kode === selected_kode ? 'selected' : '') + '>'+ val.nama +'</option>');
        });
    })
    $provinsi_id.change();

    $kecamatan_id = $('#wilayah_id');
    $kabupaten_id.change(() => {
        $kecamatan_id.html('<option value="">-Pilih Kecamatan-</option>');
        let parent_kode = $kabupaten_id.find('option:selected').val();
        let selected_id = '{{ $sektor_terdampak->wilayah_id ?? '' }}';
        $.each(list_kecamatan.filter(item => item.parent_kode.toString() === parent_kode.toString()), (i, val) => {
            $kecamatan_id.append('<option value="'+ val.id +'" ' + (val.id.toString() === selected_id ? 'selected' : '') + '>'+ val.nama +'</option>');
        });
    });
    $kabupaten_id.change();
</script>
