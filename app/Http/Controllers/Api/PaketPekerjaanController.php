<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\IoResourceApiController;
use App\Services\PaketPekerjaanService;

class PaketPekerjaanController extends IoResourceApiController
{
    public function __construct()
    {
        $this->service = new PaketPekerjaanService();
        $this->itemVariable = 'paket_pekerjaan';
    }
}
