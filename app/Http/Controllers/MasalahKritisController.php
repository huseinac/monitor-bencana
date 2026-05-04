<?php

namespace App\Http\Controllers;

use App\Services\DocumentService;
use App\Services\IndikatorService;
use App\Services\MasalahKritisService;
use App\Services\PelaksanaService;
use App\Services\WilayahService;
use Illuminate\Http\Request;

class MasalahKritisController extends IoResourceController
{
    public function __construct()
    {
        $this->service = new MasalahKritisService();
        $this->viewPrefix = 'app.admin.masalah_kritis';
        $this->itemVariable = 'masalah_kritis';

        $wilayahService = new WilayahService();
        $list_provinsi = $wilayahService->search(['kode_in' => ['11', '12', '13']]);
        $list_kabupaten = $wilayahService->search(['parent_kode_in' => $list_provinsi->pluck('kode')->toArray()]);
        $list_kecamatan = $wilayahService->search(['parent_kode_in' => $list_kabupaten->pluck('kode')->toArray()]);

        view()->share('list_provinsi', $list_provinsi);
        view()->share('list_kabupaten', $list_kabupaten);
        view()->share('list_kecamatan', $list_kecamatan);

        $indikatorService = new IndikatorService();
        view()->share('list_indikator', $indikatorService->dropdown(['no_children' => '1', 'with' => ['list_pelaksana']]));
        view()->share('list_indikator_data', $indikatorService->search(['no_children' => '1', 'with' => ['list_pelaksana']]));
        $pelaksanaService = new PelaksanaService();
        view()->share('list_pelaksana', $pelaksanaService->dropdown());
    }

    public function search(Request $request)
    {
        $request->merge([
            'with' => ['pelaksana', 'indikator.parent', 'wilayah.parent.parent'],
        ]);
        return parent::search($request);
    }

    public function store(Request $request)
    {
        $filename = DocumentService::save_file($request, 'file_foto', 'masalah_kritis');
        if ($filename !== '') $request->merge(['foto' => $filename]);

        $filename = DocumentService::save_file($request, 'file_foto_sesudah', 'masalah_kritis');
        if ($filename !== '') $request->merge(['foto_sesudah' => $filename]);

        $latitude = $request->input('latitude') ?? '';
        $longitude = $request->input('longitude') ?? '';
        if ($latitude === '' || $longitude === '') $request = $this->getRandomlatitudeLongitude($request);

        return parent::store($request);
    }

    public function update(Request $request, $id)
    {
        $filename = DocumentService::save_file($request, 'file_foto', 'masalah_kritis');
        if ($filename !== '') $request->merge(['foto' => $filename]);

        $filename = DocumentService::save_file($request, 'file_foto_sesudah', 'masalah_kritis');
        if ($filename !== '') $request->merge(['foto_sesudah' => $filename]);

        $latitude = $request->input('latitude') ?? '';
        $longitude = $request->input('longitude') ?? '';
        if ($latitude === '' || $longitude === '') $request = $this->getRandomlatitudeLongitude($request);

        return parent::update($request, $id);
    }

}
