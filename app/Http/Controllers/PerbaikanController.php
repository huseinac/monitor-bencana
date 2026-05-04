<?php

namespace App\Http\Controllers;

use App\Services\DocumentService;
use App\Services\PerbaikanService;
use App\Services\SektorTerdampakService;
use Illuminate\Http\Request;

class PerbaikanController extends IoResourceController
{
    public function __construct()
    {
        $this->service = new PerbaikanService();
        $this->viewPrefix = 'app.admin.perbaikan';
        $this->itemVariable = 'perbaikan';

        $sektorTerdampakService = new SektorTerdampakService();
        view()->share('list_sektor_terdampak', $sektorTerdampakService->dropdown(['with' => ['indikator', 'wilayah.parent.parent']]));
    }

    public function store(Request $request)
    {
        $filename = DocumentService::save_file($request, 'file_foto', 'perbaikan');
        if ($filename !== '') $request->merge(['foto' => $filename]);
        return parent::store($request);
    }

    public function update(Request $request, $id)
    {
        $filename = DocumentService::save_file($request, 'file_foto', 'perbaikan');
        if ($filename !== '') $request->merge(['foto' => $filename]);
        return parent::update($request, $id);
    }

}
