<?php

namespace App\Services;

use App\Models\KategoriPaketPekerjaan;

class KategoriPaketPekerjaanService extends IoService
{
    public function __construct()
    {
        $this->model = new KategoriPaketPekerjaan();
        $this->sort_by = ['id' => 'asc'];
        $this->filters = [];
    }

    public function dynamic_search($model, $params = [])
    {
        $nama = $params['nama'] ?? '';
        if ($nama !== '') $model = $model->where('nama', 'like', '%' . $nama . '%');

        return $model;
    }

}
