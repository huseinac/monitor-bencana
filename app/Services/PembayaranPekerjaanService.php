<?php

namespace App\Services;

use App\Models\PembayaranPekerjaan;

class PembayaranPekerjaanService extends IoService
{
    public function __construct()
    {
        $this->model = new PembayaranPekerjaan();
        $this->sort_by = ['id' => 'asc'];
        $this->filters = ['paket_pekerjaan_id'];
    }

    public function filter_params($params, $id = '')
    {
        $params = $this->cleanNumber($params, ['nominal']);
        return $this->cleanDate($params, ['tanggal']);
    }

}
