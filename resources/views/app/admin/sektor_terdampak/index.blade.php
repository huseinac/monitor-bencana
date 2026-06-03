@extends('layouts.index')

@section('title')
    Dampak Bencana -
@endsection

@section('content')
    <div class="page-header">
        <div>
            @include('layouts._breadcrumb')
            <h1>Dampak Bencana</h1>
            <p>Kelola data sektor terdampak bencana alam</p>
        </div>
        <div class="d-flex flex-row" style="gap: 12px;">
            <button class="btn-primary-custom" type="button" onclick="export_data()">
                <i class="bi bi-upload"></i> Export Excel
            </button>
            <button class="btn-primary-custom" type="button" data-bs-toggle="modal" data-bs-target="#modal_import_latlng">
                <i class="bi bi-upload"></i> Import Latitude Longitude
            </button>
            <button class="btn-primary-custom" type="button" data-bs-toggle="modal" data-bs-target="#modal_import">
                <i class="bi bi-upload"></i> Import ZIP
            </button>
            <button class="btn-primary-custom" onclick="info()" type="button">
                <i class="bi bi-plus-lg"></i> Tambah Dampak Bencana
            </button>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h5><i class="bi bi-table"></i> Data Dampak Bencana</h5>
            <form class="d-flex align-items-center gap-2" id="form_search">
                @csrf
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <x-input name="nama_lokasi" class="form-control-sm" caption="Cari nama" />
                </div>
                <div style="width: 300px;">
                    <x-select name="kondisi" prefix="search_" class="form-select form-select-sm" :options="$list_kondisi" caption="Cari Kondisi" onchange="search_data()" />
                </div>
                <div style="width: 300px;">
                    <x-select name="indikator_id" prefix="search_" class="form-select form-select-sm" :options="$list_indikator" caption="Cari Indikator" onchange="search_data()" />
                </div>
                <div style="width: 300px;">
                    <x-select name="provinsi_id" prefix="search_" class="form-select form-select-sm" :options="$list_provinsi_options" caption="Cari Provinsi" onchange="search_data()" />
                </div>
                <div style="width: 300px;">
                    <x-select name="kabupaten_id" prefix="search_" class="form-select form-select-sm" :options="$list_kabupaten_options" caption="Cari Kabupaten" onchange="search_data()" />
                </div>
            </form>
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
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" id="modal_info_content">
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_import" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('sektor_terdampak.import_data') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle"><i class="bi bi-person-plus-fill me-2"></i>Import Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <x-io-select name="kabupaten_id" :options="$list_kabupaten_options" caption="Cari Kabupaten" />
                        <x-io-input type="file" name="file_zip" caption="File ZIP" />
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

    <div class="modal fade" id="modal_import_latlng" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('sektor_terdampak.import_latlng') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle"><i class="bi bi-person-plus-fill me-2"></i>Import Data Update Latitude Longitude</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <x-io-select name="kabupaten_id" prefix="latlng_" :options="$list_kabupaten_options" caption="Cari Kabupaten" />
                        <x-io-input type="file" name="file_excel" caption="File ZIP" />
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

        let selected_page = 1, _token = '{{ csrf_token() }}', base_url = '{{ route('sektor_terdampak.index') }}', params_url = '{{ $params ?? '' }}';

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
                console.log(url);
                $.ajax({
                    url,
                    type: 'post',
                    data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: () => {
                        if (id === '') showToast('Dampak Bencana berhasil ditambahkan', 'success');
                        else showToast('Dampak Bencana berhasil diubah', 'success');

                        init()
                    },
                }).fail((xhr) => {
                    error_handle(xhr.responseText);
                });
            });
        }

        let export_data = () => {
            let data = $form_search.serialize();
            window.open(base_url + '/export/data?' + data, '_blank');
        }

        $form_search.submit((e) => {
            e.preventDefault();
            search_data();
        });

        init_form_element();
        init();
    </script>
@endpush
