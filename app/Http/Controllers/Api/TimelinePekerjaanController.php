<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\IoResourceApiController;
use App\Services\TimelinePekerjaanService;

class TimelinePekerjaanController extends IoResourceApiController
{
    public function __construct()
    {
        $this->service = new TimelinePekerjaanService();
        $this->itemVariable = 'timeline_pekerjaan';
    }
}
