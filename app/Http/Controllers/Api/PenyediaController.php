<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\IoResourceApiController;
use App\Services\PenyediaService;

class PenyediaController extends IoResourceApiController
{
    public function __construct()
    {
        $this->service = new PenyediaService();
        $this->itemVariable = 'penyedia';
    }
}
