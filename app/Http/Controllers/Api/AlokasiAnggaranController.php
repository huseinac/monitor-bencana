<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\IoResourceApiController;
use App\Services\AlokasiAnggaranService;

class AlokasiAnggaranController extends IoResourceApiController
{
    public function __construct()
    {
        $this->service = new AlokasiAnggaranService();
        $this->itemVariable = 'alokasi_anggaran';
    }
}
