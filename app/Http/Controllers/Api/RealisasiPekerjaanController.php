<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\IoResourceApiController;
use App\Services\RealisasiPekerjaanService;

class RealisasiPekerjaanController extends IoResourceApiController
{
    public function __construct()
    {
        $this->service = new RealisasiPekerjaanService();
        $this->itemVariable = 'realisasi_pekerjaan';
    }
}
