<?php

namespace App\Services;

use App\Models\Penyedia;

class PenyediaService extends IoService
{
    public function __construct()
    {
        $this->model = new Penyedia();
        $this->sort_by = ['id' => 'asc'];
    }

    public function dynamic_search($model, $params = [])
    {
        $nama = $params['nama'] ?? '';
        if ($nama !== '') $model = $model->where('nama', 'like', '%' . $nama . '%');

        return $model;
    }

}
