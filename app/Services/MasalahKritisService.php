<?php

namespace App\Services;

use App\Models\MasalahKritis;

class MasalahKritisService extends IoService
{
    public function __construct()
    {
        $this->model = new MasalahKritis();
        $this->sort_by = ['id' => 'asc'];
        $this->filters = ['indikator_id', 'wilayah_id'];
    }

    public function dynamic_search($model, $params = [])
    {
        $nama = $params['nama'] ?? '';
        if ($nama !== '') $model = $model->where('nama', 'like', '%' . $nama . '%');

        $kabupaten_id = $params['kabupaten_id'] ?? '';
        if ($kabupaten_id !== '') $model = $model->whereHas('wilayah.parent', fn($parent) => $parent->where('id', $kabupaten_id));

        $provinsi_id = $params['provinsi_id'] ?? '';
        if ($provinsi_id !== '') $model = $model->whereHas('wilayah.parent.parent', fn($parent) => $parent->where('id', $provinsi_id));

        return $model;
    }

}
