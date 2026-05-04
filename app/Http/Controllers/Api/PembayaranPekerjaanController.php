<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\IoResourceApiController;
use App\Services\PembayaranPekerjaanService;

class PembayaranPekerjaanController extends IoResourceApiController
{
    public function __construct()
    {
        $this->service = new PembayaranPekerjaanService();
        $this->itemVariable = 'pembayaran_pekerjaan';
    }
}
