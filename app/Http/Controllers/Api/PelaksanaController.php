<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\IoResourceApiController;
use App\Services\PelaksanaService;

class PelaksanaController extends IoResourceApiController
{
    public function __construct()
    {
        $this->service = new PelaksanaService();
        $this->itemVariable = 'pelaksana';
    }
}
