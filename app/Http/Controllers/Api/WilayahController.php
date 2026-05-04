<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\IoResourceApiController;
use App\Services\WilayahService;

class WilayahController extends IoResourceApiController
{
    public function __construct()
    {
        $this->service = new WilayahService();
        $this->itemVariable = 'wilayah';
    }
}
