<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\IoResourceApiController;
use App\Services\SektorTerdampakService;

class SektorTerdampakController extends IoResourceApiController
{
    public function __construct()
    {
        $this->service = new SektorTerdampakService();
        $this->itemVariable = 'sektor_terdampak';
    }
}
