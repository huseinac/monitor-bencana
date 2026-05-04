<?php

namespace App\Http\Controllers;

use App\Services\DocumentService;
use App\Services\KategoriPaketPekerjaanService;
use Illuminate\Http\Request;

class KategoriPaketPekerjaanController extends IoResourceController
{
    public function __construct()
    {
        $this->service = new KategoriPaketPekerjaanService();
        $this->viewPrefix = 'app.admin.kategori_paket_pekerjaan';
        $this->itemVariable = 'kategori_paket_pekerjaan';
    }

    public function store(Request $request)
    {
        $filename = DocumentService::save_file($request, 'file_icon', 'indikator');
        if ($filename !== '') $request->merge(['icon' => $filename]);
        return parent::store($request);
    }

    public function update(Request $request, $id)
    {
        $filename = DocumentService::save_file($request, 'file_icon', 'indikator');
        if ($filename !== '') $request->merge(['icon' => $filename]);

        return parent::update($request, $id);
    }
}
