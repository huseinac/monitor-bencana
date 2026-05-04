<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\IoResourceApiController;
use App\Services\SektorTerdampakDetailService;

class SektorTerdampakDetailController extends IoResourceApiController
{
    public function __construct()
    {
        $this->service = new SektorTerdampakDetailService();
        $this->itemVariable = 'sektor_terdampak_detail';
    }
}
