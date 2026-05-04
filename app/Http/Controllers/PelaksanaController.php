<?php

namespace App\Http\Controllers;

use App\Services\PelaksanaService;

class PelaksanaController extends IoResourceController
{
    public function __construct()
    {
        $this->service = new PelaksanaService();
        $this->viewPrefix = 'app.admin.pelaksana';
        $this->itemVariable = 'pelaksana';
    }

}
