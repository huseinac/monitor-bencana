<?php

namespace App\Http\Controllers;

use App\Services\SektorTerdampakDetailDetailService;

class SektorTerdampakDetailController extends IoResourceController
{
    public function __construct()
    {
        $this->service = new SektorTerdampakDetailDetailService();
        $this->viewPrefix = 'app.admin.sektor_terdampak.detail';
        $this->itemVariable = 'sektor_terdampak_detail';
    }

}
