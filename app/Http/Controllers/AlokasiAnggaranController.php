<?php

namespace App\Http\Controllers;

use App\Services\AnggaranDaerahService;
use App\Services\AlokasiAnggaranService;
use Illuminate\Http\Request;

class AlokasiAnggaranController extends IoResourceController
{
    public function __construct()
    {
        $this->service = new AlokasiAnggaranService();
        $this->viewPrefix = 'app.admin.alokasi_anggaran';
        $this->itemVariable = 'alokasi_anggaran';

        $anggaranDaerahService = new AnggaranDaerahService();
        view()->share('list_anggaran_daerah', $anggaranDaerahService->dropdown());
    }

    public function search(Request $request)
    {
        $request->merge(['with' => ['anggaran_daerah.wilayah.parent.parent']]);
        return parent::search($request);
    }
}
