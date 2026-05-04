<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\IoResourceApiController;
use App\Services\KategoriPaketPekerjaanService;

class KategoriPaketPekerjaanController extends IoResourceApiController
{
    public function __construct()
    {
        $this->service = new KategoriPaketPekerjaanService();
        $this->itemVariable = 'kategori_paket_pekerjaan';
    }
}
