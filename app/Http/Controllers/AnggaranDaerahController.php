<?php

namespace App\Http\Controllers;

use App\Services\DocumentService;
use App\Services\AnggaranDaerahService;
use App\Services\WilayahService;
use Illuminate\Http\Request;

class AnggaranDaerahController extends IoResourceController
{
    public function __construct()
    {
        $this->service = new AnggaranDaerahService();
        $this->viewPrefix = 'app.admin.anggaran_daerah';
        $this->itemVariable = 'anggaran_daerah';

        $wilayahService = new WilayahService();
        $list_provinsi = $wilayahService->search(['kode_in' => ['11', '12', '13']]);
        $list_kabupaten = $wilayahService->search(['parent_kode_in' => $list_provinsi->pluck('kode')->toArray()]);
        $list_kecamatan = $wilayahService->search(['parent_kode_in' => $list_kabupaten->pluck('kode')->toArray()]);

        view()->share('list_provinsi', $list_provinsi);
        view()->share('list_kabupaten', $list_kabupaten);
        view()->share('list_kecamatan', $list_kecamatan);
    }

    public function search(Request $request)
    {
        $request->merge(['with' => ['wilayah.parent.parent']]);
        return parent::search($request);
    }
}
