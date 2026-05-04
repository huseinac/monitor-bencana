<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\IoResourceApiController;
use App\Services\MasalahKritisService;

class MasalahKritisController extends IoResourceApiController
{
    public function __construct()
    {
        $this->service = new MasalahKritisService();
        $this->itemVariable = 'masalah_kritis';
    }
}
