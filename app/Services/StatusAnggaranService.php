<?php

namespace App\Services;

use App\Models\StatusAnggaran;

class StatusAnggaranService extends IoService
{
    public function __construct()
    {
        $this->model = new StatusAnggaran();
        $this->sort_by = ['id' => 'asc'];
    }

    public function dynamic_search($model, $params = [])
    {
        $nama = $params['nama'] ?? '';
        if ($nama !== '') $model = $model->where('nama', 'like', '%' . $nama . '%');

        return $model;
    }

}
