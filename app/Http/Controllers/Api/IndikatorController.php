<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\IoResourceApiController;
use App\Services\IndikatorService;

class IndikatorController extends IoResourceApiController
{
    public function __construct()
    {
        $this->service = new IndikatorService();
        $this->itemVariable = 'indikator';
    }
}
