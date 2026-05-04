<?php

namespace App\Services;

use App\Models\RincianPekerjaan;

class RincianPekerjaanService extends IoService
{
    public function __construct()
    {
        $this->model = new RincianPekerjaan();
        $this->sort_by = ['id' => 'asc'];
        $this->filters = ['paket_pekerjaan_id'];
    }

    public function dynamic_search($model, $params = [])
    {
        $nama = $params['nama'] ?? '';
        if ($nama !== '') $model = $model->where('nama', 'like', '%' . $nama . '%');

        return $model;
    }

    public function filter_params($params, $id = '')
    {
        return $this->cleanNumber($params, ['bobot']);
    }

}
