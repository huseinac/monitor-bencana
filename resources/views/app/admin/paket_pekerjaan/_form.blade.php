<form id="form_info">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><i class="bi bi-person-plus-fill me-2"></i>{{ !empty($paket_pekerjaan) ? 'Ubah' : 'Tambah' }} Paket Pekerjaan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-4">
                <x-io-select name="provinsi_id" caption="Provinsi" placeholder="-Pilih Provinsi-" :value="$paket_pekerjaan->wilayah->parent->parent->wilayah_id ?? ''" :viewtype="2" required />
                <x-io-select name="kabupaten_id" caption="Kabupaten / Kota" placeholder="-Pilih Kabupaten-" :value="$paket_pekerjaan->wilayah->parent->wilayah_id ?? ''" :viewtype="2" required />
                <x-io-select name="wilayah_id" caption="Kecamatan" placeholder="-Pilih Kecamatan-" :value="$paket_pekerjaan->wilayah_id ?? ''" :viewtype="2" required />
                <x-io-select name="indikator_id" caption="Indikator" :options="$list_indikator" placeholder="-Kosong-" :value="$paket_pekerjaan->indikator_id ?? ''" :viewtype="2" required />
                <x-io-select name="pelaksana_id" caption="Pelaksana" :options="$list_pelaksana" placeholder="-Kosong-" :value="$paket_pekerjaan->indikator_id ?? ''" :viewtype="2" required />
                <x-io-select name="kategori_paket_pekerjaan_id" caption="Kategori Paket Pekerjaan" :options="$list_kategori" placeholder="-Kosong-" :value="$paket_pekerjaan->kategori_paket_pekerjaan_id ?? ''" :viewtype="2" />
                <x-io-input name="nama" caption="Nama Pekerjaan" :value="$paket_pekerjaan->nama ?? ''" :viewtype="2" />
                <x-io-input type="number" name="nominal" caption="Nominal" :value="$paket_pekerjaan->nominal ?? ''" :viewtype="2" required />
                <div class="row">
                    <div class="col-lg-6">
                        <x-io-input name="latitude" caption="Latitude" :value="$paket_pekerjaan->latitude ?? ''" :viewtype="2" />
                    </div>
                    <div class="col-lg-6">
                        <x-io-input name="longitude" caption="Longitude" :value="$paket_pekerjaan->longitude ?? ''" :viewtype="2" />
                    </div>
                </div>
                <x-io-textarea name="keterangan" caption="Keterangan" :value="$paket_pekerjaan->keterangan ?? ''" :viewtype="2" />
            </div>
            <div class="col-lg-4">
                <x-io-input name="tahun_anggaran" caption="Tahun Anggaran" :value="$paket_pekerjaan->tahun_anggaran ?? ''" :viewtype="2" />
                <x-io-input name="nama_program" caption="Nama Program" :value="$paket_pekerjaan->nama_program ?? ''" :viewtype="2" />
                <x-io-input name="nama_kegiatan" caption="Nama Kegiatan" :value="$paket_pekerjaan->nama_kegiatan ?? ''" :viewtype="2" />
                <x-io-input name="nama_sub_kegiatan" caption="Nama Sub Kegiatan" :value="$paket_pekerjaan->nama_sub_kegiatan ?? ''" :viewtype="2" />
                <x-io-input name="nama_rekening" caption="Nama Rekening" :value="$paket_pekerjaan->nama_rekening ?? ''" :viewtype="2" />
                <x-io-input type="number" name="pagu_dana" caption="Pagu Dana" :value="$paket_pekerjaan->pagu_dana ?? ''" :viewtype="2" />
            </div>
            <div class="col-lg-4">
                <x-io-select name="penyedia_id" caption="Penyedia" :options="$list_penyedia" placeholder="-Kosong-" :value="$paket_pekerjaan->penyedia_id ?? ''" :viewtype="2" />
                <x-io-input name="no_kontrak" caption="No.Kontrak" :value="$paket_pekerjaan->no_kontrak ?? ''" :viewtype="2" />
                <x-io-input name="nama_paket" caption="Nama Paket" :value="$paket_pekerjaan->nama_paket ?? ''" :viewtype="2" />
                <x-io-input name="jenis_pengadaan" caption="Jenis Pengadaan" :value="$paket_pekerjaan->jenis_pengadaan ?? ''" :viewtype="2" />
                <x-io-input name="model_pengadaan" caption="Model Pengadaan" :value="$paket_pekerjaan->model_pengadaan ?? ''" :viewtype="2" />
                <x-io-input name="tanggal_kontrak" caption="Tanggal Kontrak" :value="$paket_pekerjaan->tanggal_kontrak ?? ''" class="datepicker" :viewtype="2" />
                <x-io-input name="tanggal_selesai" caption="Tanggal Selesai" :value="$paket_pekerjaan->tanggal_selesai ?? ''" class="datepicker" :viewtype="2" />
                <x-io-input type="number" name="nilai_pagu" caption="Nilai Pagu" :value="$paket_pekerjaan->nilai_pagu ?? ''" :viewtype="2" />
                <x-io-input type="number" name="nilai_kontrak" caption="Nilai Kontrak" :value="$paket_pekerjaan->nilai_kontrak ?? ''" :viewtype="2" />
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
    init_form({{ $paket_pekerjaan->id ?? '' }});

    $provinsi_id = $('#provinsi_id');
    $provinsi_id.html('<option value="">-Pilih Provinsi-</option>');
    $.each(list_provinsi, (i, val) => {
        let selected_kode = '{{ $paket_pekerjaan->wilayah->parent->parent_kode ?? '' }}';
        console.log(selected_kode);
        $provinsi_id.append('<option value="'+ val.kode +'" ' + (val.kode === selected_kode ? 'selected' : '') + '>'+ val.nama +'</option>');
    });

    $kabupaten_id = $('#kabupaten_id');
    $provinsi_id.change(() => {
        $kabupaten_id.html('<option value="">-Pilih Kabupaten-</option>');
        let parent_kode = $provinsi_id.find('option:selected').val();

        let selected_kode = '{{ $paket_pekerjaan->wilayah->parent_kode ?? '' }}';
        $.each(list_kabupaten.filter(item => item.parent_kode.toString() === parent_kode.toString()), (i, val) => {
            $kabupaten_id.append('<option value="'+ val.kode +'" ' + (val.kode === selected_kode ? 'selected' : '') + '>'+ val.nama +'</option>');
        });
    })
    $provinsi_id.change();

    $kecamatan_id = $('#wilayah_id');
    $kabupaten_id.change(() => {
        $kecamatan_id.html('<option value="">-Pilih Kecamatan-</option>');
        let parent_kode = $kabupaten_id.find('option:selected').val();
        let selected_id = '{{ $paket_pekerjaan->wilayah_id ?? '' }}';
        $.each(list_kecamatan.filter(item => item.parent_kode.toString() === parent_kode.toString()), (i, val) => {
            $kecamatan_id.append('<option value="'+ val.id +'" ' + (val.id.toString() === selected_id ? 'selected' : '') + '>'+ val.nama +'</option>');
        });
    });
    $kabupaten_id.change();
</script>
