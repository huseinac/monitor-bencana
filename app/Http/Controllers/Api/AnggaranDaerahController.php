<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\IoResourceApiController;
use App\Services\AnggaranDaerahService;

class AnggaranDaerahController extends IoResourceApiController
{
    public function __construct()
    {
        $this->service = new AnggaranDaerahService();
        $this->itemVariable = 'anggaran_daerah';
    }
}
