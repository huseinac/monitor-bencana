<?php

namespace App\Services;

use App\Models\SektorTerdampakDetail;

class SektorTerdampakDetailService extends IoService
{
    public function __construct()
    {
        $this->model = new SektorTerdampakDetail();
        $this->sort_by = ['id' => 'asc'];
        $this->filters = ['sektor_terdampak_id'];
    }

    public function dynamic_search($model, $params = [])
    {
        $keterangan = $params['keterangan'] ?? '';
        if ($keterangan !== '') $model = $model->where('keterangan', 'like', '%' . $keterangan . '%');

        return $model;
    }

}
