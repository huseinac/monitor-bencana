<?php

namespace App\Services;

use App\Models\SektorTerdampak;
use App\Models\Wilayah;

class WilayahService extends IoService
{
    public array $list_kondisi = SektorTerdampak::LIST_KONDISI;
    public function __construct()
    {
        $this->model = new Wilayah();
        $this->sort_by = ['kode' => 'asc'];
        $this->filters = ['kode', 'parent_kode', 'latitude'];
    }

    public function dynamic_search($model, $params = [])
    {
        $nama = $params['nama'] ?? '';
        if ($nama !== '') $model = $model->where('nama', 'like', '%' . $nama . '%');

        $kode_in = $params['kode_in'] ?? '';
        if ($kode_in !== '') $model = $model->whereIn('kode', $kode_in);

        $parent_kode_in = $params['parent_kode_in'] ?? '';
        if ($parent_kode_in !== '') $model = $model->whereIn('parent_kode', $parent_kode_in);

        return $model;
    }

}
