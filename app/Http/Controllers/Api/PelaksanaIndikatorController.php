<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\IoResourceApiController;
use App\Services\PelaksanaIndikatorService;

class PelaksanaIndikatorController extends IoResourceApiController
{
    public function __construct()
    {
        $this->service = new PelaksanaIndikatorService();
        $this->itemVariable = 'pelaksana_indikator';
    }
}
