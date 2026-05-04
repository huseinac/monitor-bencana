<?php

namespace App\Http\Controllers;

use App\Exports\SektorTerdampakExport;
use App\Imports\SektorTerdampakImport;
use App\Services\DocumentService;
use App\Services\IndikatorService;
use App\Services\PelaksanaService;
use App\Services\SektorTerdampakService;
use App\Services\WilayahService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class SektorTerdampakController extends IoResourceController
{
    public function __construct()
    {
        $this->service = new SektorTerdampakService();
        $this->viewPrefix = 'app.admin.sektor_terdampak';
        $this->itemVariable = 'sektor_terdampak';

        $wilayahService = new WilayahService();
        $list_provinsi = $wilayahService->search(['kode_in' => ['11', '12', '13']]);
        $list_kabupaten = $wilayahService->search(['parent_kode_in' => $list_provinsi->pluck('kode')->toArray()]);
        $list_kecamatan = $wilayahService->search(['parent_kode_in' => $list_kabupaten->pluck('kode')->toArray()]);

        view()->share('list_kondisi', $this->service->list_kondisi);
        view()->share('list_provinsi', $list_provinsi);
        view()->share('list_kabupaten', $list_kabupaten);
        view()->share('list_provinsi_options', $wilayahService->dropdown(['kode_in' => ['11', '12', '13']]));
        view()->share('list_kabupaten_options', $wilayahService->dropdown(['parent_kode_in' => $list_provinsi->pluck('kode')->toArray()]));
        view()->share('list_kecamatan', $list_kecamatan);

        $indikatorService = new IndikatorService();
        view()->share('list_indikator', $indikatorService->dropdown(['no_children' => '1', 'with' => ['list_pelaksana']]));
        view()->share('list_indikator_data', $indikatorService->search(['no_children' => '1', 'with' => ['list_pelaksana']]));
        $pelaksanaService = new PelaksanaService();
        view()->share('list_pelaksana', $pelaksanaService->search());
    }

    public function search(Request $request)
    {
        $request->merge([
            'with' => ['indikator.parent', 'wilayah.parent.parent', 'list_perbaikan'],
        ]);
        return parent::search($request);
    }

    public function store(Request $request)
    {
        $latitude = $request->input('latitude') ?? '';
        $longitude = $request->input('longitude') ?? '';
        if ($latitude === '' || $longitude === '') $request = $this->getRandomlatitudeLongitude($request);

        $filename = DocumentService::save_file($request, 'file_foto_sebelum', 'sektor_terdampak');
        if ($filename !== '') $request->merge(['foto_sebelum' => $filename]);
        $filename = DocumentService::save_file($request, 'file_foto_sesudah', 'sektor_terdampak');
        if ($filename !== '') $request->merge(['foto_sesudah' => $filename]);

        return parent::store($request);
    }

    public function update(Request $request, $id)
    {
        $latitude = $request->input('latitude') ?? '';
        $longitude = $request->input('longitude') ?? '';
        if ($latitude === '' || $longitude === '') $request = $this->getRandomlatitudeLongitude($request);

        $filename = DocumentService::save_file($request, 'file_foto_sebelum', 'sektor_terdampak');
        if ($filename !== '') $request->merge(['foto_sebelum' => $filename]);
        $filename = DocumentService::save_file($request, 'file_foto_sesudah', 'sektor_terdampak');
        if ($filename !== '') $request->merge(['foto_sesudah' => $filename]);

        return parent::update($request, $id);
    }

    public function import_data(Request $request)
    {
        $kabupaten_id = $request->input('kabupaten_id') ?? '';
        if ($kabupaten_id === '') return redirect()->back()->with('error', 'Pilih Kabupaten');

        $wilayahService = new WilayahService();
        $kabupaten = $wilayahService->find($kabupaten_id);

        $uuid = Str::uuid();
        $zip_name = $request->file('file_zip')->getClientOriginalName();
        $filename_zip = DocumentService::save_file($request, 'file_zip', 'temp', $uuid);

        $temp_directory = 'temp/' . $uuid . '/' . str_replace('.zip', '', $zip_name);
        $new_directory = 'dampak_bencana/' . $kabupaten_id . '/' . str_replace('.zip', '', $zip_name);

        $zip = new \ZipArchive();
        $zip->open(storage_path('app/' . $filename_zip));
        $zip->extractTo(storage_path('app/' . $temp_directory));
        $zip->close();

        Storage::move($temp_directory, $new_directory);
        $files = Storage::allFiles($temp_directory);
        $file_excel = '';
        foreach ($files as $file) {
            if ($file_excel === '') {
                $ext = last(explode('.', $file));
                if ($ext == 'xls' || $ext == 'xlsx') $file_excel = $file;
            }
        }

        $indikatorService = new IndikatorService();
        $list_indikator = $indikatorService->search();
        $list_kecamatan = $wilayahService->search(['parent_kode' => $kabupaten->kode]);

        session()->forget('list_error');
        if ($file_excel !== '') Excel::import(new SektorTerdampakImport($new_directory, $kabupaten, $list_indikator, $list_kecamatan), storage_path('app/' . $file_excel));

        Storage::delete($filename_zip);
        Storage::deleteDirectory($temp_directory);

        $list_error = session('list_error', []);
        if (count($list_error) > 0) {
            return redirect()->back()->with('error', $list_error);
        }
        return redirect()->back();
    }

    public function export_data(Request $request)
    {
        $request->merge(['ajax' => 1]);
        $sektor_terdampaks = $this->search($request);
//        return view('app.admin.sektor_terdampak.export', compact('sektor_terdampaks'));
        return Excel::download(new SektorTerdampakExport($sektor_terdampaks), 'dampak_bencana_'. date('Ymd') .'.xlsx');
    }

}
