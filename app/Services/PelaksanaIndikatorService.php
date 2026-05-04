<?php

namespace App\Services;

use App\Models\PelaksanaIndikator;

class PelaksanaIndikatorService extends IoService
{
    public function __construct()
    {
        $this->model = new PelaksanaIndikator();
        $this->sort_by = ['id' => 'asc'];
        $this->filters = ['indikator_id', 'pelaksana_id'];
    }

}
