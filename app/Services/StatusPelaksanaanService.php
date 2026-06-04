<?php

namespace App\Services;

use App\Models\StatusPelaksanaan;

class StatusPelaksanaanService extends IoService
{
    public function __construct()
    {
        $this->model = new StatusPelaksanaan();
        $this->sort_by = ['id' => 'asc'];
    }

    public function dynamic_search($model, $params = [])
    {
        $nama = $params['nama'] ?? '';
        if ($nama !== '') $model = $model->where('nama', 'like', '%' . $nama . '%');

        return $model;
    }

}
