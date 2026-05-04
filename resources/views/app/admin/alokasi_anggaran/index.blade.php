@extends('layouts.index')

@section('title')
    Alokasi Anggaran -
@endsection

@section('content')
    <div class="page-header">
        <div>
            @include('layouts._breadcrumb')
            <h1>Alokasi Anggaran</h1>
            <p>Kelola data sektor terdampak bencana alam</p>
        </div>
        <button class="btn-primary-custom" onclick="info()" type="button">
            <i class="bi bi-plus-lg"></i> Tambah Alokasi Anggaran
        </button>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h5><i class="bi bi-table"></i> Data Alokasi Anggaran</h5>
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="modal_info_content">
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

        let selected_page = 1, _token = '{{ csrf_token() }}', base_url = '{{ route('alokasi_anggaran.index') }}', params_url = '{{ $params ?? '' }}';

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
                $.ajax({
                    url,
                    type: 'post',
                    data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: () => {
                        if (id === '') showToast('Alokasi Anggaran berhasil ditambahkan', 'success');
                        else showToast('Alokasi Anggaran berhasil diubah', 'success');

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
