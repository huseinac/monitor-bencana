<?php

namespace App\Http\Controllers;

use App\Services\PenyediaService;

class PenyediaController extends IoResourceController
{
    public function __construct()
    {
        $this->service = new PenyediaService();
        $this->viewPrefix = 'app.admin.penyedia';
        $this->itemVariable = 'penyedia';
    }

}
