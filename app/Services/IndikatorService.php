<?php

namespace App\Services;

use App\Models\Indikator;

class IndikatorService extends IoService
{
    public function __construct()
    {
        $this->model = new Indikator();
        $this->sort_by = ['kode' => 'asc'];
        $this->filters = ['kode', 'parent_kode'];
    }

    public function dynamic_search($model, $params = [])
    {
        $nama = $params['nama'] ?? '';
        if ($nama !== '') $model = $model->where('nama', 'like', '%' . $nama . '%');

        $no_children = $params['no_children'] ?? '';
        if ($no_children !== '') $model = $model->doesntHave('children');

        return $model;
    }

}
