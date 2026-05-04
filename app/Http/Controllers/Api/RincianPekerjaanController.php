<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\IoResourceApiController;
use App\Services\RincianPekerjaanService;

class RincianPekerjaanController extends IoResourceApiController
{
    public function __construct()
    {
        $this->service = new RincianPekerjaanService();
        $this->itemVariable = 'rincian_pekerjaan';
    }
}
