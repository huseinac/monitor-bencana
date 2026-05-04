<?php

namespace App\Services;

use App\Models\AnggaranDaerah;

class AnggaranDaerahService extends IoService
{
    public function __construct()
    {
        $this->model = new AnggaranDaerah();
        $this->sort_by = ['id' => 'asc'];
        $this->filters = ['wilayah_id'];
    }

    public function dynamic_search($model, $params = [])
    {
        $keterangan = $params['keterangan'] ?? '';
        if ($keterangan !== '') $model = $model->where('keterangan', 'like', '%' . $keterangan . '%');

        $provinsi_id = $params['provinsi_id'] ?? '';
        if ($provinsi_id !== '') $model = $model->whereHas('wilayah.parent', fn($parent) => $parent->where('id', $provinsi_id));

        return $model;
    }

    public function dropdown($params = []): array
    {
        $params['with'] = ['wilayah'];
        $result = [];
        foreach ($this->search($params) as $value) $result[$value->id] = $value->wilayah->nama . ' - ' . $value->wilayah->parent->nama;
        return $result;
    }

    public function filter_params($params, $id = '')
    {
        return $this->cleanNumber($params, ['anggaran_2025', 'anggaran_2026', 'penyesuaian']);
    }

}
