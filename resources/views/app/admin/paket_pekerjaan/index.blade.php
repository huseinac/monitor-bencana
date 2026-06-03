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
            <button class="btn-primary-custom" type="button" data-bs-toggle="modal" data-bs-target="#modal_import">
                <i class="bi bi-file-arrow-down-fill"></i> Import Data Paket Pekerjaan
            </button>
            <button class="btn-primary-custom" onclick="info()" type="button">
                <i class="bi bi-plus-lg"></i> Tambah Paket Pekerjaan
            </button>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header d-flex flex-column justify-content-start align-items-start">
            <div class="row">
                <h5><i class="bi bi-table"></i> Data Paket Pekerjaan</h5>
            </div>
            <div class="row w-100">
                <div class="col-12">
                    <form class="d-flex align-items-center gap-2 p-3" id="form_search">
                        @csrf
                        <div class="panel w-100">
                            <div class="panel-header position-relative" 
                                 data-bs-toggle="collapse" 
                                 data-bs-target="#searchPanelBody" 
                                 style="cursor: pointer; padding-right: 2.5rem;" 
                                 role="button">
                                
                                <div class="row">
                                    <div class="col-12">
                                        <h5><i class="bi bi-search"></i> Pencarian data</h5>
                                    </div>
                                </div>

                                <i class="bi bi-chevron-down text-muted position-absolute top-50 end-0 translate-middle-y me-3"></i>
                            </div>
                            
                            <div class="panel-body row collapse hide" id="searchPanelBody">
                                <div class="col-lg-6 mb-2">
                                    <div class="search-box">
                                        <i class="bi bi-search"></i>
                                        <x-input name="nama" class="form-control-sm w-100" caption="Cari nama" onchange="search_data()" />
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <div>
                                        <x-input name="tahun_anggaran" class="form-control-sm" caption="Cari tahun" onchange="search_data()" />
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <div>
                                        <x-select name="provinsi_id" prefix="search_" class="form-select form-select-sm" :options="$list_provinsi_options" caption="Cari Provinsi" data-control="select2" onchange="search_data()" />
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <div>
                                        <x-select name="kabupaten_id" prefix="search_" class="form-select form-select-sm" :options="$list_kabupaten_options" caption="Cari Kabupaten" data-control="select2" onchange="search_data()" />
                                    </div>
                                </div>
                                <x-io-select name="pelaksana_id" caption="Pelaksana" :options="$list_pelaksana" placeholder="-Pilih pelaksana-" :viewtype="2" onchange="search_data()" />
                                <x-io-select name="status_anggaran_id" caption="Status Anggaran" :options="$list_status_anggaran" placeholder="-Pilih status anggaran-" :viewtype="2" onchange="search_data()" />
                                <x-io-select name="status_pelaksanaan_id" caption="Status Pelaksanaan" :options="$list_status_pelaksanaan" placeholder="-Pilih status pelaksanaan-" :viewtype="2" onchange="search_data()" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @php($error = session('error', []))
        @if(count($error) > 0)
            <div class="p-3">
                <h5>List Error</h5>
                @foreach($error as $item)
                    <p style="font-size: 10px;border-bottom: 1px solid #000;">Penyebab : {{ $item['penyebab'] }} <br> Baris : {{ json_encode($item['baris']) }}</p>
                @endforeach
            </div>
        @endif

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
    <div class="modal fade" id="modal_import" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('paket_pekerjaan.import_data') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle"><i class="bi bi-person-plus-fill me-2"></i>Import Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <x-io-select name="provinsi_id" prefix="import_" :options="$list_provinsi_options" caption="Cari Provinsi" data-control="select2" />
                        <x-io-input type="file" name="file_excel" caption="File Excel" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i> Batal
                        </button>
                        <button type="submit" class="btn-primary-custom">
                            <i class="bi bi-check-lg"></i> Upload
                        </button>
                    </div>
                </form>
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
            showLoadingToast('Loading...', 'success');
            let data = get_form_data($form_search);
            console.log(data);
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
