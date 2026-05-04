<?php

namespace App\Services;

use App\Models\Perbaikan;

class PerbaikanService extends IoService
{
    public function __construct()
    {
        $this->model = new Perbaikan();
        $this->sort_by = ['id' => 'asc'];
        $this->filters = ['sektor_terdampak_id', 'sektor_terdampak_detail_id', 'tanggal'];
    }

    public function dynamic_search($model, $params = [])
    {
        $keterangan = $params['keterangan'] ?? '';
        if ($keterangan !== '') $model = $model->where('keterangan', 'like', '%' . $keterangan . '%');

        return $model;
    }

    public function filter_params($params, $id = '')
    {
        return $this->cleanDate($params, ['tanggal']);
    }

}
