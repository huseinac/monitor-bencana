<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\IoResourceApiController;
use App\Services\PerbaikanService;

class PerbaikanController extends IoResourceApiController
{
    public function __construct()
    {
        $this->service = new PerbaikanService();
        $this->itemVariable = 'perbaikan';
    }
}
