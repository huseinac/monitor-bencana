<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WilayahService;

class WilayahController extends IoResourceController
{
    public function __construct()
    {
        $this->service = new WilayahService();
        $this->viewPrefix = 'app.admin.wilayah';
        $this->itemVariable = 'wilayah';

        $list_kondisi = ['Normal', 'Mendekati', 'Atensi'];
        view()->share('list_kondisi', array_combine($list_kondisi, $list_kondisi));
    }

    public function search(Request $request)
    {
        $kode = $request->input('kode') ?? '';
        if ($kode === '') {
            $list_kode_in = ['11', '12', '13'];
            $list_kabupaten = $this->service->search(['parent_kode_in' => $list_kode_in]);
            $list_kode_in = array_merge($list_kode_in, $list_kabupaten->pluck('kode')->toArray());

            $list_kecamatan = $this->service->search(['parent_kode_in' => $list_kabupaten->pluck('kode')->toArray()]);
            $list_kode_in = array_merge($list_kode_in, $list_kecamatan->pluck('kode')->toArray());

            $request->merge(['parent_kode_in' => $list_kode_in]);
        }

        return parent::search($request);
    }
    
}
