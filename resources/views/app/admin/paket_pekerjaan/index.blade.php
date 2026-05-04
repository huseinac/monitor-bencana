@extends('layouts.index')

@section('title')
    Paket Pekerjaan -
@endsection

@section('content')
    <div class="page-header">
        <div>
            @include('layouts._breadcrumb')
            <h1>Paket Pekerjaan</h1>
            <p>Kelola data sektor terdampak bencana alam</p>
        </div>
        <div>
            <button class="btn-primary-custom" onclick="importExcel()" type="button">
                <i class="bi bi-file-arrow-down-fill"></i> Import Data Paket Pekerjaan
            </button>
            <button class="btn-primary-custom" onclick="info()" type="button">
                <i class="bi bi-plus-lg"></i> Tambah Paket Pekerjaan
            </button>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h5><i class="bi bi-table"></i> Data Paket Pekerjaan</h5>
            <form class="d-flex align-items-center gap-2" id="form_search">
                @csrf
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <x-input name="nama" class="form-control-sm" caption="Cari nama" />
                </div>
            </form>
        </div>

        <div id="table"></div>
    </div>
@endsection

@push('modals')
    <div class="modal fade" id="modal_info" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" id="modal_info_content">
            </div>
        </div>
    </div>
@endpush

@push('modals')
    <div class="modal fade" id="modal_impor_excel" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" id="modal_import_content">
                <form id="formImport">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle"><i class="bi bi-file-arrow-down-fill"></i> Import data paket pekerjaan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row mb-3">
                                    <div class="col-8">
                                        <label for="aaWqadw" class="block text-sm font-medium text-gray-700 required">Pilih file</label><br>
                                    </div>
                                    <div class="col-4 d-flex justify-content-end">
                                        <a href="{{ asset('assets/impordata.xlsm') }}" download class="btn btn-sm btn-success">
                                            <i class="bi bi-cloud-arrow-down"></i> Unduh template
                                        </a>
                                    </div>
                                </div>
                                <div class="form-control">
                                    <input type="file" name="aaWqadw" id="aaWqadw" accept=".xlsm" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i> Batal
                        </button>
                        <button type="button" class="btn-primary-custom" onclick="importExcelProcess()">
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
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        let $form_search = $('#form_search'),
            $table = $('#table'),
            $modal_info = $('#modal_info'),
            $modal_info_content = $('#modal_info_content');

        let list_provinsi = JSON.parse(`@json($list_provinsi)`);
        let list_kabupaten = JSON.parse(`@json($list_kabupaten)`);
        let list_kecamatan = JSON.parse(`@json($list_kecamatan)`);
        let list_indikator = JSON.parse(`@json($list_indikator_data)`);
        let list_pelaksana = JSON.parse(`@json($list_pelaksana)`);

        let selected_page = 1, _token = '{{ csrf_token() }}', base_url = '{{ route('paket_pekerjaan.index') }}', params_url = '{{ $params ?? '' }}';

        let init = () => {
            $modal_info_content.html('');
            try {
                bootstrap.Modal.getInstance(document.getElementById('modal_info')).hide();
            } catch (e) { }
            search_data(selected_page);
        }

        let search_data = (page = 1) => {
            let data = get_form_data($form_search);
            data.paginate = 10;
            data.page = selected_page = get_selected_page(page, selected_page);
            $.post(base_url + '/search?' + params_url, data, (result) => $table.html(result)).fail((xhr) => $table.html(xhr.responseText));
        }

        let display_modal_info = (item) => {
            $modal_info_content.html(item);
            const m = new bootstrap.Modal(document.getElementById('modal_info'));
            m.show();
        }

        let info = (id = '') => {
            $.get(base_url + '/' + (id === '' ? 'create' : (id + '/edit')) + '?' + params_url, (result) => display_modal_info(result)).fail((xhr) => display_modal_info(xhr.responseText));
        }

        let importExcel = () => {
            $('#modal_impor_excel').modal('toggle');
        }

        let importExcelProcess = () => {
            var file = $('#aaWqadw').prop('files')[0];
                    
            if (!file) {
                alert('Mohon memilih file terlebih dahulu');
                return false;  
            }

            const validExtensions = ['xlsm'];
            const extension = file.name.split('.').pop().toLowerCase();

            if ($.inArray(extension, validExtensions) === -1) {
                alert("Format file salah! Mohon menggunakan file template sistem");
                $(this).val('');
                return false;
            }

            let formData = new FormData();
            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: "{{ url('paket_pekerjaan/xclimp') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function (argument) {
                    // body...
                    showLoadingToast('Proses menyimpan data...', 'success');
                },
                success: function(response) {
                    $('#modal_impor_excel').modal('toggle');
                    showToast(response.message, 'success');
                    $('#aaWqadw').val(null);
                },
                error: function(xhr) {
                    $('#aaWqadw').val(null);
                    alert("Error: " + xhr.responseJSON.message);
                }
            });
        }

        let confirm_delete = (id) => {
            Swal.fire(swal_delete_params).then((result) => {
                if (result.isConfirmed) $.post(base_url + '/' + id, {_method: 'delete', _token}, (data) => {
                    if (data.error) swal.fire({icon: 'error', title: data.error}).then(() => init());
                    else swal.fire('Berhasil Dihapus').then(() => init());
                }).fail((xhr) => $table.html(xhr.responseText));
            });
        }

        let confirm_restore = (id) => {
            Swal.fire(swal_restore_params).then((result) => {
                if (result.isConfirmed) $.post(base_url + '/' + id + '/restore', {_method: 'put', _token}, () => swal.fire('Berhasil Dikembalikan').then(() => init())).fail((xhr) => $table.html(xhr.responseText));
            });
        }

        let init_form = (id = '') => {
            let $form_info = $('#form_info');
            $form_info.submit((e) => {
                e.preventDefault();
                let url = base_url;
                let data = new FormData($form_info.get(0));
                if (id !== '') url += '/' + id + '?_method=put';
                $.ajax({
                    url,
                    type: 'post',
                    data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: () => {
                        if (id === '') showToast('Paket Pekerjaan berhasil ditambahkan', 'success');
                        else showToast('Paket Pekerjaan berhasil diubah', 'success');

                        init()
                    },
                }).fail((xhr) => {
                    error_handle(xhr.responseText);
                });
            });
        }

        $form_search.submit((e) => {
            e.preventDefault();
            search_data();
        });

        init_form_element();
        init();
    </script>
@endpush
